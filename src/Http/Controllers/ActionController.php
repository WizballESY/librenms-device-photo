<?php

namespace WizballEsy\LibreNmsDevicePhoto\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use WizballEsy\LibreNmsDevicePhoto\Services\DeletedPhotoStorageMigrationService;
use WizballEsy\LibreNmsDevicePhoto\Services\PhotoImageService;
use WizballEsy\LibreNmsDevicePhoto\Services\PhotoDateService;
use WizballEsy\LibreNmsDevicePhoto\Services\PhotoLinkService;
use WizballEsy\LibreNmsDevicePhoto\Services\PhotoListService;
use WizballEsy\LibreNmsDevicePhoto\Services\PhotoMetadataService;
use WizballEsy\LibreNmsDevicePhoto\Services\PhotoOrderService;
use WizballEsy\LibreNmsDevicePhoto\Services\PhotoPathService;
use WizballEsy\LibreNmsDevicePhoto\Services\PhotoPermissionService;
use WizballEsy\LibreNmsDevicePhoto\Services\PhotoSettingsService;

class ActionController extends Controller
{
    public function __construct(
        private readonly PhotoImageService $images,
        private readonly PhotoDateService $dates,
        private readonly PhotoListService $photos,
        private readonly PhotoLinkService $links,
        private readonly PhotoMetadataService $metadata,
        private readonly PhotoOrderService $order,
        private readonly PhotoPathService $paths,
        private readonly PhotoPermissionService $permissions,
        private readonly PhotoSettingsService $settings,
        private readonly DeletedPhotoStorageMigrationService $deletedStorageMigration,
    ) {
    }

    public function handle(Request $request)
    {
        $user = auth()->user();

        if (! $user || ! $user->can('global-read')) {
            abort(403, 'Forbidden');
        }

        $action = (string) $request->input('action', '');
        $deviceId = (int) $request->input('device_id', 0);

        return match ($action) {
            'save_order' => $this->saveOrder($request, $deviceId),
            'remove_link' => $this->removeLink($request, $deviceId),
            'remove_outgoing_link' => $this->removeOutgoingLink($request, $deviceId),
            'add_link' => $this->addLink($request, $deviceId),
            'add_incoming_link' => $this->addIncomingLink($request, $deviceId),
            'clean_stale_thumbnails' => $this->cleanStaleThumbnails($request),
            'generate_missing_thumbnails' => $this->generateMissingThumbnails($request),
            'empty_deleted_photos' => $this->emptyDeletedPhotos($request),
            'migrate_deleted_photos_storage' => $this->migrateDeletedPhotosStorage($request),
            'restore_deleted_photo' => $this->restoreDeletedPhoto($request),
            'delete_deleted_photo' => $this->deleteDeletedPhoto($request),
            'remove_broken_link' => $this->removeBrokenLink($request),
            'set_photo_taken' => $this->setPhotoTaken($request, $deviceId),
            'change_photo_owner' => $this->changePhotoOwner($request, $deviceId),
            'delete' => $this->deletePhoto($request, $deviceId),
            'assign_orphan_photo' => $this->assignOrphanPhoto($request),
            'delete_orphan_photo' => $this->deleteOrphanPhoto($request),
            'upload' => $this->uploadPhotos($request, $deviceId),
            default => $this->redirect($deviceId, 'unknown_action'),
        };
    }

    private function saveOrder(Request $request, int $deviceId)
    {
        if (! $this->findExistingDevice($deviceId)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('device_not_found', false, 404);
            }

            return $this->redirect($deviceId, 'device_not_found');
        }

        $settings = $this->settings->settings();

        if (! $this->permissions->userCanAction(auth()->user(), $settings, 'reorder_roles')) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('permission_denied', false, 403);
            }

            return $this->redirect($deviceId, 'permission_denied');
        }

        $orderJson = (string) $request->input('order_json', '[]');
        $decoded = json_decode($orderJson, true);

        if (! is_array($decoded)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('invalid_order', false, 422);
            }

            return $this->redirect($deviceId, 'invalid_order');
        }

        $safeShortName = $this->photos->safeDevicePrefix($deviceId);

        /*
         * Mixed display order supports both owned and linked photos.
         *
         * Owned photo key:
         *   device-108-1.jpg
         *
         * Linked photo key:
         *   linked:109:device-109-2.jpg
         */
        $existing = [];

        foreach ($this->photos->listFilenamesForDevice($deviceId) as $filename) {
            $existing[$filename] = true;
        }

        $linksFile = $this->paths->linksDir() . '/device-' . $deviceId . '.json';
        $links = [];

        if (is_file($linksFile)) {
            $decodedLinks = json_decode((string) file_get_contents($linksFile), true);
            $links = is_array($decodedLinks) ? $decodedLinks : [];
        }

        foreach ($links as $link) {
            if (! is_array($link)) {
                continue;
            }

            $ownerDeviceId = (int) ($link['owner_device_id'] ?? 0);
            $filename = basename((string) ($link['filename'] ?? ''));

            if ($ownerDeviceId < 1 || $filename === '') {
                continue;
            }

            if (! preg_match('/^device-' . preg_quote((string) $ownerDeviceId, '/') . '-[0-9]{1,3}\.(jpg|jpeg|png|webp)$/i', $filename)) {
                continue;
            }

            if (! is_file($this->paths->photoPath($filename))) {
                continue;
            }

            $existing['linked:' . $ownerDeviceId . ':' . $filename] = true;
        }

        $newOrder = [];

        foreach ($decoded as $item) {
            if (! is_string($item)) {
                continue;
            }

            $item = trim($item);

            if ($item !== '' && isset($existing[$item])) {
                $newOrder[] = $item;
            }
        }

        foreach (array_keys($existing) as $item) {
            if (! in_array($item, $newOrder, true)) {
                $newOrder[] = $item;
            }
        }

        if (! $this->order->save($safeShortName, $newOrder)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('order_update_failed', false, 500);
            }

            return $this->redirectAfterAction($request, $deviceId, 'order_update_failed');
        }

        if ($this->wantsJsonResponse($request)) {
            return $this->jsonStatus('order_updated', true, 200, [
                'message' => 'Photo order saved.',
                'order' => $newOrder,
            ]);
        }

        return $this->redirectAfterAction($request, $deviceId, 'order_updated');
    }

    private function removeLink(Request $request, int $deviceId)
    {
        if (! $this->findExistingDevice($deviceId)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('device_not_found', false, 404);
            }

            return $this->redirect($deviceId, 'device_not_found');
        }

        $settings = $this->settings->settings();

        if (! $this->permissions->userCanAction(auth()->user(), $settings, 'delete_roles')) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('permission_denied', false, 403);
            }

            return $this->redirect($deviceId, 'permission_denied');
        }

        $filename = basename((string) $request->input('filename', ''));
        $ownerDeviceId = (int) $request->input('owner_device_id', 0);

        $removeLinkPattern = '/^device-' . preg_quote((string) $ownerDeviceId, '/') . '-[0-9]{1,3}\.(jpg|jpeg|png|webp)$/i';

        if ($ownerDeviceId < 1 || ! preg_match($removeLinkPattern, $filename)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('invalid_filename', false, 422);
            }

            return $this->redirect($deviceId, 'invalid_filename');
        }

        if (! $this->links->remove($deviceId, $ownerDeviceId, $filename)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('link_remove_failed', false, 500);
            }

            return $this->redirectAfterAction($request, $deviceId, 'link_remove_failed');
        }

        $this->pruneOrderForDevice($deviceId);

        if ($this->wantsJsonResponse($request)) {
            return $this->jsonStatus('link_removed');
        }

        return $this->redirectAfterAction($request, $deviceId, 'link_removed');
    }

    private function removeOutgoingLink(Request $request, int $deviceId)
    {
        if (! $this->findExistingDevice($deviceId)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('device_not_found', false, 404);
            }

            return $this->redirect($deviceId, 'device_not_found');
        }

        $settings = $this->settings->settings();

        if (! $this->permissions->userCanAction(auth()->user(), $settings, 'delete_roles')) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('permission_denied', false, 403);
            }

            return $this->redirect($deviceId, 'permission_denied');
        }

        $filename = basename((string) $request->input('filename', ''));
        $targetDeviceId = (int) $request->input('target_device_id', 0);
        $safeShortName = $this->photos->safeDevicePrefix($deviceId);

        $pattern = '/^' . preg_quote($safeShortName, '/') . '-[0-9]{1,3}\.(jpg|jpeg|png|webp)$/i';

        if (! preg_match($pattern, $filename)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('invalid_filename', false, 422);
            }

            return $this->redirect($deviceId, 'invalid_filename');
        }

        if ($targetDeviceId < 1 || ! Device::find($targetDeviceId)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('invalid_target_device', false, 422);
            }

            return $this->redirect($deviceId, 'invalid_target_device');
        }

        if (! $this->links->remove($targetDeviceId, $deviceId, $filename)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('link_remove_failed', false, 500);
            }

            return $this->redirectAfterAction($request, $deviceId, 'link_remove_failed');
        }

        $this->pruneOrderForDevice($targetDeviceId);

        if ($this->wantsJsonResponse($request)) {
            return $this->jsonStatus('link_removed');
        }

        return $this->redirectAfterAction($request, $deviceId, 'link_removed');
    }

    private function uploadPhotos(Request $request, int $deviceId)
    {
        /*
         * Supports both:
         *   photo      = single upload, old field name
         *   photos[]   = multi upload, new field name
         */
        if (! $this->findExistingDevice($deviceId)) {
            return $this->redirect($deviceId, 'device_not_found');
        }

        $settings = $this->settings->settings();

        if (! $this->permissions->userCanAction(auth()->user(), $settings, 'upload_roles')) {
            return $this->redirect($deviceId, 'permission_denied');
        }

        $uploadedFiles = $request->file('photos');

        if (empty($uploadedFiles)) {
            $uploadedFiles = $request->file('photo');
        }

        if (empty($uploadedFiles)) {
            return $this->redirect($deviceId, 'no_file');
        }

        if (! is_array($uploadedFiles)) {
            $uploadedFiles = [$uploadedFiles];
        }

        if (! is_dir($this->paths->photosDir())) {
            @mkdir($this->paths->photosDir(), 02775, true);
        }

        $allowedExt = array_values(array_filter(
            config('device-photo.allowed_extensions', ['jpg', 'jpeg', 'png', 'webp', 'heic', 'heif']),
            fn ($ext) => is_string($ext) && trim($ext) !== ''
        ));
        $allowedExt = array_map(fn ($ext) => strtolower(trim($ext)), $allowedExt);
        $maxUploadBytes = (int) config('device-photo.max_upload_bytes', 10 * 1024 * 1024);
        $maxPixels = (int) config('device-photo.max_pixels', 40000000);

        $safeShortName = $this->photos->safeDevicePrefix($deviceId);
        $validatedUploads = [];

        foreach ($uploadedFiles as $file) {
            if (! $file || ! $file->isValid()) {
                return $this->redirect($deviceId, 'upload_failed');
            }

            $ext = strtolower((string) $file->getClientOriginalExtension());

            if (! in_array($ext, $allowedExt, true)) {
                return $this->redirect($deviceId, 'invalid_type');
            }

            if ($file->getSize() > $maxUploadBytes) {
                return $this->redirect($deviceId, 'too_large');
            }

            $isHeicUpload = in_array($ext, ['heic', 'heif'], true);

            if (! $isHeicUpload) {
                $imageInfo = @getimagesize($file->getRealPath());

                if ($imageInfo === false || empty($imageInfo[0]) || empty($imageInfo[1])) {
                    return $this->redirect($deviceId, 'invalid_image');
                }

                $allowedMimeByExt = [
                    'jpg' => ['image/jpeg'],
                    'jpeg' => ['image/jpeg'],
                    'png' => ['image/png'],
                    'webp' => ['image/webp'],
                ];

                $imageMime = strtolower((string) ($imageInfo['mime'] ?? ''));

                if (! in_array($imageMime, $allowedMimeByExt[$ext] ?? [], true)) {
                    return $this->redirect($deviceId, 'invalid_image');
                }

                $imageWidth = (int) $imageInfo[0];
                $imageHeight = (int) $imageInfo[1];
                $imagePixels = $imageWidth * $imageHeight;

                if ($imageWidth < 1 || $imageHeight < 1 || $imagePixels > $maxPixels) {
                    return $this->redirect($deviceId, 'image_too_large_pixels');
                }
            }

            if ($isHeicUpload && ! $this->images->heicConversionAvailable()) {
                return $this->redirect($deviceId, 'heic_unavailable');
            }

            $validatedUploads[] = [
                'file' => $file,
                'ext' => $ext,
                'is_heic_upload' => $isHeicUpload,
            ];
        }

        if (empty($validatedUploads)) {
            return $this->redirect($deviceId, 'no_file');
        }

        $usedNumbers = [];

        foreach ($allowedExt as $checkExt) {
            foreach (glob($this->paths->photosDir() . '/' . $safeShortName . '-*.' . $checkExt) ?: [] as $existingPhotoPath) {
                $existingFilename = basename($existingPhotoPath);

                if (preg_match('/^' . preg_quote($safeShortName, '/') . '-(\d+)\./i', $existingFilename, $matches)) {
                    $usedNumbers[(int) $matches[1]] = true;
                }
            }
        }

        foreach ($validatedUploads as $index => $upload) {
            $targetName = null;
            $targetExt = ! empty($upload['is_heic_upload']) ? 'jpg' : (string) $upload['ext'];

            /*
             * Always use numbered filenames:
             *   device-23-1.jpg
             *   device-23-2.jpg
             *   device-23-3.jpg
             *
             * Numbering is global per device, regardless of file extension.
             */
            for ($i = 1; $i <= 999; $i++) {
                if (! isset($usedNumbers[$i])) {
                    $usedNumbers[$i] = true;
                    $targetName = $safeShortName . '-' . $i . '.' . $targetExt;
                    break;
                }
            }

            if (! $targetName) {
                return $this->redirect($deviceId, 'no_filename');
            }

            $validatedUploads[$index]['target_name'] = $targetName;
            $validatedUploads[$index]['target_path'] = $this->paths->photoPath($targetName);
        }

        $uploadedCount = 0;

        foreach ($validatedUploads as $upload) {
            $file = $upload['file'];
            $targetName = (string) $upload['target_name'];
            $targetPath = (string) $upload['target_path'];
            $isHeicUpload = ! empty($upload['is_heic_upload']);

            $temporaryPath = $this->temporaryUploadPath($targetExt);

            if ($temporaryPath === '') {
                return $this->redirect($deviceId, 'upload_failed');
            }

            if ($isHeicUpload) {
                if (! $this->images->convertHeicToJpeg($file->getRealPath(), $temporaryPath)) {
                    @unlink($temporaryPath);

                    return $this->redirect($deviceId, 'heic_convert_failed');
                }

                $convertedInfo = @getimagesize($temporaryPath);

                if (
                    $convertedInfo === false ||
                    empty($convertedInfo[0]) ||
                    empty($convertedInfo[1]) ||
                    ((int) $convertedInfo[0] * (int) $convertedInfo[1]) > $maxPixels
                ) {
                    @unlink($temporaryPath);

                    return $this->redirect($deviceId, 'image_too_large_pixels');
                }
            } else {
                try {
                    $file->move(dirname($temporaryPath), basename($temporaryPath));
                } catch (\Throwable $e) {
                    @unlink($temporaryPath);

                    return $this->redirect($deviceId, 'upload_failed');
                }

                if (! is_file($temporaryPath)) {
                    return $this->redirect($deviceId, 'upload_failed');
                }
            }

            if (! $this->moveFileWithoutOverwrite($temporaryPath, $targetPath)) {
                @unlink($temporaryPath);

                return $this->redirect($deviceId, 'upload_failed');
            }

            @chmod($targetPath, 0664);

            /*
             * If this filename has been used before, remove stale links after the
             * new file has safely reached its final no-overwrite target.
             */
            $this->links->removeAllForFilename($targetName);

            /*
             * Thumbnail generation is optional and fail-safe.
             * If GD is missing or thumbnail generation fails, the original image is used as fallback.
             */
            $this->images->createThumbnail($targetPath, $targetName);

            $uploadedCount++;
        }

        if ($uploadedCount < 1) {
            return $this->redirect($deviceId, 'no_file');
        }

        /*
         * Do not rewrite the order file after upload.
         * The order file may contain mixed owned/linked photo keys.
         * New uploaded photos are appended automatically when rendering if
         * they do not already exist in the saved order.
         */
        return $this->redirect($deviceId, 'uploaded');
    }

    private function assignOrphanPhoto(Request $request)
    {
        /*
         * Assign an orphaned photo to an existing device.
         * The file is renamed to the target device ID and next available number.
         * Existing links pointing to the old filename are updated to the new owner/file.
         */
        $settings = $this->settings->settings();

        if (! $this->permissions->userCanAction(auth()->user(), $settings, 'upload_roles')) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('permission_denied', false, 403);
            }

            return $this->redirect(0, 'permission_denied');
        }

        $filename = basename((string) $request->input('filename', ''));
        $targetInput = trim((string) $request->input('target_device_query', $request->input('target_device_id', '')));

        $targetDevice = $this->findDeviceFromInput($targetInput);
        $targetDeviceId = $targetDevice ? (int) $targetDevice->device_id : 0;

        if (! preg_match('/^device-\d+-\d+\.(jpg|jpeg|png|webp)$/i', $filename)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('invalid_filename', false, 422);
            }

            return $this->redirect(0, 'invalid_filename');
        }

        if (! is_file($this->paths->photoPath($filename))) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('not_found', false, 404);
            }

            return $this->redirect(0, 'not_found');
        }

        if (! preg_match('/^device-(\d+)-\d+\.(jpg|jpeg|png|webp)$/i', $filename, $matches)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('invalid_filename', false, 422);
            }

            return $this->redirect(0, 'invalid_filename');
        }

        $oldDeviceId = (int) $matches[1];

        /*
         * Only allow assigning photos whose original owner device no longer exists.
         */
        if ($oldDeviceId > 0 && Device::find($oldDeviceId)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('not_orphaned', false, 409);
            }

            return $this->redirect(0, 'not_orphaned');
        }

        if (! $targetDevice) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('invalid_target_device', false, 422);
            }

            return $this->redirect(0, 'invalid_target_device');
        }

        $pathInfo = pathinfo($filename);
        $ext = strtolower((string) ($pathInfo['extension'] ?? ''));

        if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('invalid_type', false, 422);
            }

            return $this->redirect(0, 'invalid_type');
        }

        $targetName = $this->nextAvailablePhotoFilename($targetDeviceId, $ext);

        if ($targetName === '' || is_file($this->paths->photoPath($targetName))) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('assign_failed', false, 500);
            }

            return $this->redirect(0, 'assign_failed');
        }

        $sourcePath = $this->paths->photoPath($filename);
        $targetPath = $this->paths->photoPath($targetName);

        if (! $this->moveFileWithoutOverwrite($sourcePath, $targetPath)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('assign_failed', false, 500);
            }

            return $this->redirect(0, 'assign_failed');
        }

        @chmod($targetPath, 0664);

        /*
         * Update existing linked-photo JSON entries that pointed to the old orphaned filename.
         * PhotoLinkService performs this as a locked read-modify-write operation.
         */
        $metadataUpdated = $this->links->updateFilenameReferences($filename, $targetDeviceId, $targetName);

        $metadataUpdated = $this->appendOwnedPhotoToOrder($targetDeviceId, $targetName)
            && $metadataUpdated;

        /*
         * Thumbnails are cache only. Run thumbnail work after link/order state has
         * been updated, and never let thumbnail failures break a completed orphan
         * assignment after the original photo has already been moved.
         */
        try {
            $oldThumbPath = $this->paths->thumbPath($filename);
            $newThumbPath = $this->paths->thumbPath($targetName);

            if (is_file($oldThumbPath)) {
                @rename($oldThumbPath, $newThumbPath);
                @chmod($newThumbPath, 0664);
            }

            $this->images->createThumbnail($targetPath, $targetName);
        } catch (\Throwable $e) {
            /*
             * Ignore thumbnail failures here. Missing thumbnails can be regenerated
             * by thumbnail maintenance, and the original photo is the source of truth.
             */
        }

        $status = $metadataUpdated
            ? 'assigned'
            : 'assigned_with_warnings';

        if ($this->wantsJsonResponse($request)) {
            return $this->jsonStatus($status);
        }

        return $this->redirect(0, $status);
    }

    private function nextAvailablePhotoFilename(int $deviceId, string $ext): string
    {
        $ext = strtolower(trim($ext));

        if ($deviceId < 1 || ! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            return '';
        }

        $targetPrefix = 'device-' . $deviceId;
        $nextNumber = 1;

        foreach (glob($this->paths->photosDir() . '/' . $targetPrefix . '-*.*') ?: [] as $existingPath) {
            $existingName = basename($existingPath);

            if (preg_match('/^' . preg_quote($targetPrefix, '/') . '-(\d+)\.(jpg|jpeg|png|webp)$/i', $existingName, $numberMatches)) {
                $nextNumber = max($nextNumber, ((int) $numberMatches[1]) + 1);
            }
        }

        $targetName = $targetPrefix . '-' . $nextNumber . '.' . $ext;

        while (is_file($this->paths->photoPath($targetName))) {
            $nextNumber++;
            $targetName = $targetPrefix . '-' . $nextNumber . '.' . $ext;
        }

        return $targetName;
    }

    private function appendOwnedPhotoToOrder(int $deviceId, string $filename): bool
    {
        if ($deviceId < 1 || $filename === '') {
            return false;
        }

        /*
         * Preserve existing mixed order and append the new owned photo at the end.
         *
         * Important:
         * Do not rebuild order from listFilenamesForDevice(), because that only
         * contains owned photos and would drop linked:<owner>:<filename> entries.
         */
        $this->pruneOrderForDevice($deviceId);

        $safeShortName = $this->photos->safeDevicePrefix($deviceId);

        return $this->order->appendOwnedPhoto($safeShortName, $filename);
    }

    private function restoreDeletedPhoto(Request $request)
    {
        /*
         * Restore a deleted photo to a selected target device.
         * The restored file is renamed to the target device ID and next available number.
         */
        $settings = $this->settings->settings();

        if (! $this->permissions->userCanAction(auth()->user(), $settings, 'delete_roles')) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('permission_denied', false, 403);
            }

            return $this->redirectRestoreDeleted('permission_denied');
        }

        $filename = basename((string) $request->input('filename', ''));
        $targetInput = trim((string) $request->input('target_device_query', $request->input('target_device_id', '')));

        if (! preg_match('/^device-\d+-\d+\.deleted-\d{8}-\d{6}\.(jpg|jpeg|png|webp)$/i', $filename)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('invalid_filename', false, 422);
            }

            return $this->redirectRestoreDeleted('invalid_filename');
        }

        if (! is_file($this->paths->deletedPath($filename))) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('not_found', false, 404);
            }

            return $this->redirectRestoreDeleted('not_found');
        }

        $targetDevice = $this->findDeviceFromInput($targetInput);
        $targetDeviceId = $targetDevice ? (int) $targetDevice->device_id : 0;

        if (! $targetDevice || $targetDeviceId < 1) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('invalid_target_device', false, 422);
            }

            return $this->redirectRestoreDeleted('invalid_target_device');
        }

        $pathInfo = pathinfo($filename);
        $ext = strtolower((string) ($pathInfo['extension'] ?? ''));

        if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('invalid_type', false, 422);
            }

            return $this->redirectRestoreDeleted('invalid_type');
        }

        $targetName = $this->nextAvailablePhotoFilename($targetDeviceId, $ext);

        if ($targetName === '' || is_file($this->paths->photoPath($targetName))) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('restore_failed', false, 500);
            }

            return $this->redirectRestoreDeleted('restore_failed');
        }

        $sourcePath = $this->paths->deletedPath($filename);
        $targetPath = $this->paths->photoPath($targetName);

        if (! $this->moveFileWithoutOverwrite($sourcePath, $targetPath)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('restore_failed', false, 500);
            }

            return $this->redirectRestoreDeleted('restore_failed');
        }

        @chmod($targetPath, 0664);

        $this->appendOwnedPhotoToOrder($targetDeviceId, $targetName);

        /*
         * Thumbnails are cache only. Run thumbnail work after active photo/order
         * state has been updated, and never let thumbnail failures break a
         * completed restore after the deleted original has already been moved.
         */
        try {
            $oldThumbPath = $this->paths->deletedThumbPath($filename);
            $newThumbPath = $this->paths->thumbPath($targetName);

            if (is_file($oldThumbPath)) {
                @rename($oldThumbPath, $newThumbPath);
                @chmod($newThumbPath, 0664);
            }

            $this->images->createThumbnail($targetPath, $targetName);
        } catch (\Throwable $e) {
            /*
             * Ignore thumbnail failures here. Missing thumbnails can be regenerated
             * by thumbnail maintenance, and the original photo is the source of truth.
             */
        }

        if ($this->wantsJsonResponse($request)) {
            return $this->jsonStatus('restored', true, 200, [
                'deleted_stats' => $this->deletedFolderStats(),
            ]);
        }

        return $this->redirectRestoreDeleted('restored');
    }

    private function deleteDeletedPhoto(Request $request)
    {
        /*
         * Permanently delete one photo from the deleted folder.
         * This removes both the deleted original and its deleted thumbnail if present.
         */
        $settings = $this->settings->settings();

        if (! $this->permissions->userCanAction(auth()->user(), $settings, 'delete_roles')) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('permission_denied', false, 403);
            }

            return $this->redirectRestoreDeleted('permission_denied');
        }

        $filename = basename((string) $request->input('filename', ''));

        if (! preg_match('/^device-\d+-\d+\.deleted-\d{8}-\d{6}\.(jpg|jpeg|png|webp)$/i', $filename)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('invalid_filename', false, 422);
            }

            return $this->redirectRestoreDeleted('invalid_filename');
        }

        $deletedPath = $this->paths->deletedPath($filename);
        $deletedThumbPath = $this->paths->deletedThumbPath($filename);

        if (! is_file($deletedPath)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('not_found', false, 404);
            }

            return $this->redirectRestoreDeleted('not_found');
        }

        $deletedOriginal = @unlink($deletedPath);

        if (! $deletedOriginal) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('delete_failed', false, 500);
            }

            return $this->redirectRestoreDeleted('delete_failed');
        }

        if (is_file($deletedThumbPath)) {
            @unlink($deletedThumbPath);
        }

        if ($this->wantsJsonResponse($request)) {
            return $this->jsonStatus('deleted_photo_permanently_deleted', true, 200, [
                'deleted_stats' => $this->deletedFolderStats(),
            ]);
        }

        return $this->redirectRestoreDeleted('deleted_photo_permanently_deleted');
    }

    private function deletedFolderStats(): array
    {
        $photoCount = 0;
        $thumbnailCount = 0;
        $totalBytes = 0;

        foreach ([$this->paths->deletedDir(), $this->paths->deletedThumbsDir()] as $index => $dir) {
            foreach (glob($dir . '/*') ?: [] as $path) {
                if (! is_file($path)) {
                    continue;
                }

                if ($index === 0) {
                    $photoCount++;
                } else {
                    $thumbnailCount++;
                }

                $totalBytes += filesize($path) ?: 0;
            }
        }

        return [
            'photo_count' => $photoCount,
            'thumbnail_count' => $thumbnailCount,
            'total_bytes' => $totalBytes,
            'total_mb' => round($totalBytes / 1024 / 1024, 2),
        ];
    }

    private function redirectRestoreDeleted(?string $status = null)
    {
        $query = [
            'view' => 'restore-deleted',
        ];

        if ($status !== null) {
            $query['status'] = $status;
        }

        return redirect(url('plugin/device-photo') . '?' . http_build_query($query));
    }

    private function migrateDeletedPhotosStorage(Request $request)
    {
        $settings = $this->settings->settings();

        if (! $this->permissions->userCanAction(auth()->user(), $settings, 'delete_roles')) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('permission_denied', false, 403);
            }

            return $this->redirect(0, 'permission_denied');
        }

        $result = $this->deletedStorageMigration->migrate();

        $status = 'deleted_storage_migrated';

        if (((int) ($result['failed'] ?? 0)) > 0 || ! empty($result['legacy_deleted_storage_detected_after'])) {
            $status = 'deleted_storage_migration_manual_check';
        } elseif (((int) ($result['skipped_existing'] ?? 0)) > 0) {
            $status = 'deleted_storage_migrated_with_skips';
        }

        $message = 'Deleted photo storage migration completed. Moved '
            . (int) ($result['moved_photos'] ?? 0)
            . ' photos and '
            . (int) ($result['moved_thumbnails'] ?? 0)
            . ' thumbnails.';

        if ($status === 'deleted_storage_migration_manual_check') {
            $message .= ' Some files could not be moved or were left in the old storage location. Please check the old deleted photo storage manually.';
        } elseif ($status === 'deleted_storage_migrated_with_skips') {
            $message .= ' Some files already existed in the new storage location and were skipped.';
        }

        if ($this->wantsJsonResponse($request)) {
            return $this->jsonStatus($status, true, 200, [
                'message' => $message,
                'migration_result' => $result,
                'deleted_stats' => $this->deletedFolderStats(),
            ]);
        }

        return $this->redirect(0, $status);
    }

    private function emptyDeletedPhotos(Request $request)
    {
        $settings = $this->settings->settings();

        if (! $this->permissions->userCanAction(auth()->user(), $settings, 'delete_roles')) {
            return $this->redirect(0, 'permission_denied');
        }

        $confirmCode = (string) $request->input('confirm_code', '');
        $confirmInput = (string) $request->input('confirm_input', '');

        if ($confirmCode === '' || ! preg_match('/^\d{4}$/', $confirmCode) || ! hash_equals($confirmCode, $confirmInput)) {
            return $this->redirect(0, 'invalid_confirm_code');
        }

        $deletedDirs = [
            $this->paths->deletedDir(),
            $this->paths->deletedThumbsDir(),
        ];

        $deletedCount = 0;

        foreach ($deletedDirs as $dir) {
            foreach (glob($dir . '/*') ?: [] as $path) {
                if (! is_file($path)) {
                    continue;
                }

                if (@unlink($path)) {
                    $deletedCount++;
                }
            }
        }

        return $this->redirect(0, $deletedCount > 0 ? 'deleted_photos_emptied' : 'deleted_photos_empty');
    }

    private function deleteOrphanPhoto(Request $request)
    {
        /*
         * Move an orphaned photo to deleted folder.
         * This does not permanently delete the file.
         */
        $settings = $this->settings->settings();

        if (! $this->permissions->userCanAction(auth()->user(), $settings, 'delete_roles')) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('permission_denied', false, 403);
            }

            return $this->redirect(0, 'permission_denied');
        }

        $filename = basename((string) $request->input('filename', ''));

        if (! preg_match('/^device-\d+-\d+\.(jpg|jpeg|png|webp)$/i', $filename)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('invalid_filename', false, 422);
            }

            return $this->redirect(0, 'invalid_filename');
        }

        if (! is_file($this->paths->photoPath($filename))) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('not_found', false, 404);
            }

            return $this->redirect(0, 'not_found');
        }

        if (! preg_match('/^device-(\d+)-\d+\.(jpg|jpeg|png|webp)$/i', $filename, $matches)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('invalid_filename', false, 422);
            }

            return $this->redirect(0, 'invalid_filename');
        }

        $oldDeviceId = (int) $matches[1];

        /*
         * Only allow delete_orphan_photo if the owner device no longer exists.
         */
        if ($oldDeviceId > 0 && Device::find($oldDeviceId)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('not_orphaned', false, 409);
            }

            return $this->redirect(0, 'not_orphaned');
        }

        if (! is_dir($this->paths->deletedDir())) {
            @mkdir($this->paths->deletedDir(), 02775, true);
        }

        $deletedName = $this->nextAvailableDeletedFilename($filename);

        if ($deletedName === '') {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('delete_failed', false, 500);
            }

            return $this->redirect(0, 'delete_failed');
        }

        $sourcePath = $this->paths->photoPath($filename);
        $deletedPath = $this->paths->deletedPath($deletedName);

        if ($this->moveFileWithoutOverwrite($sourcePath, $deletedPath)) {
            @chmod($deletedPath, 0664);
            $this->moveThumbnailToDeleted($filename, $deletedName);

            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('deleted');
            }

            return $this->redirectAfterAction($request, 0, 'deleted');
        }

        if ($this->wantsJsonResponse($request)) {
            return $this->jsonStatus('delete_failed', false, 500);
        }

        return $this->redirect(0, 'delete_failed');
    }

    private function moveFileWithoutOverwrite(string $sourcePath, string $targetPath): bool
    {
        if (! is_file($sourcePath) || $targetPath === '' || is_file($targetPath)) {
            return false;
        }

        /*
         * Use link()+unlink() instead of rename() so an unexpected target file
         * can never be overwritten. link() fails if the target already exists.
         */
        if (! @link($sourcePath, $targetPath)) {
            return false;
        }

        if (! is_file($targetPath)) {
            return false;
        }

        if (! @unlink($sourcePath)) {
            @unlink($targetPath);
            return false;
        }

        return true;
    }

    private function nextAvailableDeletedFilename(string $filename): string
    {
        $pathInfo = pathinfo($filename);
        $baseName = (string) ($pathInfo['filename'] ?? '');
        $extension = strtolower((string) ($pathInfo['extension'] ?? ''));

        if ($baseName === '' || ! preg_match('/^(jpg|jpeg|png|webp)$/i', $extension)) {
            return '';
        }

        $timestamp = date('Ymd-His');
        $deletedName = $baseName . '.deleted-' . $timestamp . '.' . $extension;

        if (! is_file($this->paths->deletedPath($deletedName))) {
            return $deletedName;
        }

        for ($counter = 2; $counter <= 999; $counter++) {
            $candidate = $baseName . '.deleted-' . $timestamp . '-' . $counter . '.' . $extension;

            if (! is_file($this->paths->deletedPath($candidate))) {
                return $candidate;
            }
        }

        return '';
    }

    private function temporaryUploadPath(string $extension): string
    {
        $extension = strtolower(trim($extension));

        if (! in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            return '';
        }

        for ($attempt = 1; $attempt <= 20; $attempt++) {
            try {
                $random = bin2hex(random_bytes(16));
            } catch (\Throwable $e) {
                $random = str_replace('.', '', uniqid('', true));
            }

            $temporaryName = '.device-photo-upload-' . $random . '.' . $extension;
            $temporaryPath = $this->paths->photosDir() . '/' . $temporaryName;

            if (! is_file($temporaryPath)) {
                return $temporaryPath;
            }
        }

        return '';
    }

    private function changePhotoOwner(Request $request, int $deviceId)
    {
        $filename = basename((string) $request->input('filename', ''));
        $targetInput = trim((string) $request->input('target_device_query', $request->input('target_device_id', '')));

        if (! $this->findExistingDevice($deviceId)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('device_not_found', false, 404);
            }

            return $this->redirect($deviceId, 'device_not_found');
        }

        $settings = $this->settings->settings();

        if (! $this->permissions->userCanAction(auth()->user(), $settings, 'upload_roles')) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('permission_denied', false, 403);
            }

            return $this->redirect($deviceId, 'permission_denied');
        }

        $safeShortName = $this->photos->safeDevicePrefix($deviceId);
        $pattern = '/^' . preg_quote($safeShortName, '/') . '-[0-9]{1,3}\.(jpg|jpeg|png|webp)$/i';

        if (! preg_match($pattern, $filename)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('invalid_filename', false, 422);
            }

            return $this->redirect($deviceId, 'invalid_filename');
        }

        if (! is_file($this->paths->photoPath($filename))) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('not_found', false, 404);
            }

            return $this->redirect($deviceId, 'not_found');
        }

        $targetDevice = $this->findDeviceFromInput($targetInput);
        $targetDeviceId = $targetDevice ? (int) $targetDevice->device_id : 0;

        if (! $targetDevice || $targetDeviceId < 1) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('invalid_target_device', false, 422);
            }

            return $this->redirect($deviceId, 'invalid_target_device');
        }

        if ($targetDeviceId === $deviceId) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('same_target_device', false, 409);
            }

            return $this->redirect($deviceId, 'same_target_device');
        }

        $linkedTargetDeviceIds = $this->linkedTargetDeviceIdsForPhoto($deviceId, $filename);

        $pathInfo = pathinfo($filename);
        $ext = strtolower((string) ($pathInfo['extension'] ?? ''));
        $targetName = $this->nextAvailablePhotoFilename($targetDeviceId, $ext);

        if ($targetName === '' || is_file($this->paths->photoPath($targetName))) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('owner_change_failed', false, 500);
            }

            return $this->redirect($deviceId, 'owner_change_failed');
        }

        $sourcePath = $this->paths->photoPath($filename);
        $targetPath = $this->paths->photoPath($targetName);

        if (! $this->moveFileWithoutOverwrite($sourcePath, $targetPath)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('owner_change_failed', false, 500);
            }

            return $this->redirect($deviceId, 'owner_change_failed');
        }

        @chmod($targetPath, 0664);

        /*
         * After the original file has been renamed successfully, update all
         * references that pointed to the old owner filename.
         */
        $oldLinkedKey = 'linked:' . $deviceId . ':' . $filename;
        $newLinkedKey = 'linked:' . $targetDeviceId . ':' . $targetName;
        $targetOrderReplaced = false;
        $linkMetadataUpdated = true;
        $orderMetadataUpdated = true;

        $linkMetadataUpdated = $this->links->updateFilenameReferences($filename, $targetDeviceId, $targetName)
            && $linkMetadataUpdated;

        /*
         * If the target device previously linked to this photo, the link would
         * now point to a photo owned by the same device. Remove that self-link.
         */
        if ($this->links->exists($targetDeviceId, $targetDeviceId, $targetName)) {
            $linkMetadataUpdated = $this->links->remove($targetDeviceId, $targetDeviceId, $targetName)
                && $linkMetadataUpdated;
        }

        $orderMetadataUpdated = $this->order->remove($safeShortName, $filename)
            && $orderMetadataUpdated;

        foreach ($linkedTargetDeviceIds as $linkedTargetDeviceId) {
            $linkedTargetDeviceId = (int) $linkedTargetDeviceId;
            $linkedSafeShortName = $this->photos->safeDevicePrefix($linkedTargetDeviceId);

            if ($linkedTargetDeviceId === $targetDeviceId) {
                $replaceStatus = $this->order->replaceKeyWithStatus($linkedSafeShortName, $oldLinkedKey, $targetName);
                $orderMetadataUpdated = ! empty($replaceStatus['written']) && $orderMetadataUpdated;
                $targetOrderReplaced = ! empty($replaceStatus['replaced']) || $targetOrderReplaced;
            } else {
                $replaceStatus = $this->order->replaceKeyWithStatus($linkedSafeShortName, $oldLinkedKey, $newLinkedKey);
                $orderMetadataUpdated = ! empty($replaceStatus['written']) && $orderMetadataUpdated;
            }

            $this->pruneOrderForDevice($linkedTargetDeviceId);
        }

        if (! $targetOrderReplaced) {
            $orderMetadataUpdated = $this->appendOwnedPhotoToOrder($targetDeviceId, $targetName)
                && $orderMetadataUpdated;
        } else {
            $this->pruneOrderForDevice($targetDeviceId);
        }

        $this->pruneOrderForDevice($deviceId);

        /*
         * Thumbnails are cache only. Run thumbnail work after link/order state has
         * been updated, and never let thumbnail failures break a completed owner
         * change after the original photo has already been moved.
         */
        try {
            $oldThumbPath = $this->paths->thumbPath($filename);
            $newThumbPath = $this->paths->thumbPath($targetName);

            if (is_file($oldThumbPath)) {
                @rename($oldThumbPath, $newThumbPath);
                @chmod($newThumbPath, 0664);
            }

            $this->images->createThumbnail($targetPath, $targetName);
        } catch (\Throwable $e) {
            /*
             * Ignore thumbnail failures here. Missing thumbnails can be regenerated
             * by thumbnail maintenance, and the original photo is the source of truth.
             */
        }

        $status = ($linkMetadataUpdated && $orderMetadataUpdated)
            ? 'photo_owner_changed'
            : 'photo_owner_changed_with_warnings';

        if ($this->wantsJsonResponse($request)) {
            return $this->jsonStatus($status);
        }

        return $this->redirectAfterAction($request, $targetDeviceId, $status);
    }

    private function deletePhoto(Request $request, int $deviceId)
    {
        $filename = basename((string) $request->input('filename', ''));

        if (! $this->findExistingDevice($deviceId)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('device_not_found', false, 404);
            }

            return $this->redirect($deviceId, 'device_not_found');
        }

        $settings = $this->settings->settings();

        if (! $this->permissions->userCanAction(auth()->user(), $settings, 'delete_roles')) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('permission_denied', false, 403);
            }

            return $this->redirect($deviceId, 'permission_denied');
        }

        $safeShortName = $this->photos->safeDevicePrefix($deviceId);
        $pattern = '/^' . preg_quote($safeShortName, '/') . '-[0-9]{1,3}\\.(jpg|jpeg|png|webp)$/i';

        if (! preg_match($pattern, $filename)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('invalid_filename', false, 422);
            }

            return $this->redirect($deviceId, 'invalid_filename');
        }

        $photoPath = $this->paths->photoPath($filename);

        if (! is_file($photoPath)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('not_found', false, 404);
            }

            return $this->redirect($deviceId, 'not_found');
        }

        if (! is_dir($this->paths->deletedDir())) {
            @mkdir($this->paths->deletedDir(), 02775, true);
        }

        $deletedName = $this->nextAvailableDeletedFilename($filename);

        if ($deletedName === '') {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('delete_failed', false, 500);
            }

            return $this->redirect($deviceId, 'delete_failed');
        }

        $deletedPath = $this->paths->deletedPath($deletedName);

        if ($this->moveFileWithoutOverwrite($photoPath, $deletedPath)) {
            @chmod($deletedPath, 0664);

            $this->moveThumbnailToDeleted($filename, $deletedName);

            /*
             * Remove all links from other devices that pointed to this original photo.
             * Otherwise deleting an owned photo would leave broken linked-photo entries.
             */
            $metadataUpdated = true;
            $targetDeviceIds = $this->linkedTargetDeviceIdsForPhoto($deviceId, $filename);

            $metadataUpdated = $this->links->removeAllForFilename($filename)
                && $metadataUpdated;

            $safeShortName = $this->photos->safeDevicePrefix($deviceId);

            $metadataUpdated = $this->order->remove($safeShortName, $filename)
                && $metadataUpdated;

            foreach ($targetDeviceIds as $targetDeviceId) {
                $this->pruneOrderForDevice((int) $targetDeviceId);
            }

            $status = $metadataUpdated
                ? 'deleted'
                : 'deleted_with_warnings';

            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus($status);
            }

            return $this->redirectAfterAction($request, $deviceId, $status);
        }

        if ($this->wantsJsonResponse($request)) {
            return $this->jsonStatus('delete_failed', false, 500);
        }

        return $this->redirect($deviceId, 'delete_failed');
    }

    private function moveThumbnailToDeleted(string $filename, string $deletedName): void
    {
        if (! is_dir($this->paths->deletedThumbsDir())) {
            @mkdir($this->paths->deletedThumbsDir(), 02775, true);
        }

        $deletedThumbPath = $this->paths->deletedThumbPath($deletedName);

        if (is_file($deletedThumbPath)) {
            return;
        }

        $thumbPath = $this->paths->thumbPath($filename);

        if (is_file($thumbPath)) {
            if ($this->moveFileWithoutOverwrite($thumbPath, $deletedThumbPath)) {
                @chmod($deletedThumbPath, 0664);
            }

            return;
        }

        /*
         * Thumbnail files are cache only. If the active thumbnail was already
         * missing before delete, generate a deleted thumbnail from the deleted
         * original so the restore/deleted view does not report a missing thumb.
         */
        $deletedOriginalPath = $this->paths->deletedPath($deletedName);

        if (! is_file($deletedOriginalPath)) {
            return;
        }

        $generatedThumbPath = $this->paths->thumbPath($deletedName);

        if (
            $this->images->createThumbnail($deletedOriginalPath, $deletedName)
            && is_file($generatedThumbPath)
            && $this->moveFileWithoutOverwrite($generatedThumbPath, $deletedThumbPath)
        ) {
            @chmod($deletedThumbPath, 0664);
            return;
        }

        @unlink($generatedThumbPath);
    }

    private function setPhotoTaken(Request $request, int $deviceId)
    {
        $filename = basename((string) $request->input('filename', ''));
        $photoTakenInput = (string) $request->input('photo_taken', '');

        if (! $this->findExistingDevice($deviceId)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('device_not_found', false, 404);
            }

            return $this->redirect($deviceId, 'device_not_found');
        }

        $settings = $this->settings->settings();

        if (! $this->permissions->userCanAction(auth()->user(), $settings, 'upload_roles')) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('permission_denied', false, 403);
            }

            return $this->redirect($deviceId, 'permission_denied');
        }

        $safeShortName = $this->photos->safeDevicePrefix($deviceId);
        $pattern = '/^' . preg_quote($safeShortName, '/') . '-\\d+\\.(jpg|jpeg)$/i';

        if (! preg_match($pattern, $filename)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('invalid_filename', false, 422);
            }

            return $this->redirect($deviceId, 'invalid_filename');
        }

        $photoPath = $this->paths->photoPath($filename);

        if (! is_file($photoPath)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('not_found', false, 404);
            }

            return $this->redirect($deviceId, 'not_found');
        }

        if (! $this->metadata->exiftoolAvailable()) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('exiftool_unavailable', false, 500);
            }

            return $this->redirect($deviceId, 'exiftool_unavailable');
        }

        $timestamp = $this->metadata->parsePhotoTakenInput($photoTakenInput);

        if (! $timestamp) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('invalid_photo_taken', false, 422);
            }

            return $this->redirect($deviceId, 'invalid_photo_taken');
        }

        if (! $this->metadata->writePhotoTakenExif($photoPath, $timestamp)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('photo_taken_failed', false, 500);
            }

            return $this->redirect($deviceId, 'photo_taken_failed');
        }

        /*
         * EXIF orientation/date may affect generated thumbnails and displayed metadata.
         * Refresh the thumbnail after writing metadata.
         */
        $this->images->createThumbnail($photoPath, $filename);

        if ($this->wantsJsonResponse($request)) {
            return $this->jsonStatus('photo_taken_updated', true, 200, [
                'message' => 'Photo taken updated.',
                'filename' => $filename,
                'photo_taken_iso' => date('c', $timestamp),
                'photo_taken_display' => date('Y-m-d H:i', $timestamp),
                'photo_taken_input' => date('Y-m-d\\TH:i', $timestamp),
            ]);
        }

        return $this->redirectAfterAction($request, $deviceId, 'photo_taken_updated');
    }

    private function removeBrokenLink(Request $request)
    {
        /*
         * Remove a broken linked-photo entry from the target device JSON.
         * This only removes the link entry, not any photo file.
         */
        $settings = $this->settings->settings();

        if (! $this->permissions->userCanAction(auth()->user(), $settings, 'delete_roles')) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('permission_denied', false, 403);
            }

            return $this->redirect(0, 'permission_denied');
        }

        $targetDeviceId = (int) $request->input('target_device_id', 0);
        $ownerDeviceId = (int) $request->input('owner_device_id', 0);
        $filename = basename((string) $request->input('filename', ''));

        if ($targetDeviceId < 1 || $ownerDeviceId < 1 || $filename === '') {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('invalid_link', false, 422);
            }

            return $this->redirect(0, 'invalid_link');
        }

        if (! preg_match('/^device-\d+-\d+\.(jpg|jpeg|png|webp)$/i', $filename)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('invalid_filename', false, 422);
            }

            return $this->redirect(0, 'invalid_filename');
        }

        if (! $this->links->exists($targetDeviceId, $ownerDeviceId, $filename)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('not_found', false, 404);
            }

            return $this->redirect(0, 'not_found');
        }

        if (! $this->links->remove($targetDeviceId, $ownerDeviceId, $filename)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('link_remove_failed', false, 500);
            }

            return $this->redirect(0, 'link_remove_failed');
        }

        if ($this->wantsJsonResponse($request)) {
            return $this->jsonStatus('link_removed');
        }

        return $this->redirectAfterAction($request, 0, 'link_removed');
    }

    private function thumbnailMaintenanceStats(): array
    {
        $missing = 0;
        $stale = 0;
        $activeBytes = 0;
        $thumbnailBytes = 0;

        foreach (glob($this->paths->photosDir() . '/device-*.*') ?: [] as $sourcePath) {
            if (! is_file($sourcePath)) {
                continue;
            }

            $filename = basename($sourcePath);

            if (! preg_match('/^device-\d+-\d+\.(jpg|jpeg|png|webp)$/i', $filename)) {
                continue;
            }

            $activeBytes += filesize($sourcePath) ?: 0;

            if (! is_file($this->paths->thumbPath($filename))) {
                $missing++;
            }
        }

        foreach (glob($this->paths->deletedDir() . '/device-*.*') ?: [] as $sourcePath) {
            if (! is_file($sourcePath)) {
                continue;
            }

            $filename = basename($sourcePath);

            if (! preg_match('/^device-\d+-\d+\.deleted-\d{8}-\d{6}\.(jpg|jpeg|png|webp)$/i', $filename)) {
                continue;
            }

            $activeBytes += filesize($sourcePath) ?: 0;

            if (! is_file($this->paths->deletedThumbPath($filename))) {
                $missing++;
            }
        }

        foreach (glob($this->paths->thumbsDir() . '/device-*.*') ?: [] as $thumbPath) {
            if (! is_file($thumbPath)) {
                continue;
            }

            $filename = basename($thumbPath);

            $thumbnailBytes += filesize($thumbPath) ?: 0;

            if (! is_file($this->paths->photoPath($filename))) {
                $stale++;
            }
        }

        foreach (glob($this->paths->deletedThumbsDir() . '/device-*.*') ?: [] as $thumbPath) {
            if (! is_file($thumbPath)) {
                continue;
            }

            $filename = basename($thumbPath);

            $thumbnailBytes += filesize($thumbPath) ?: 0;

            if (! is_file($this->paths->deletedPath($filename))) {
                $stale++;
            }
        }

        return [
            'missing_thumbnail_count' => $missing,
            'stale_thumbnail_count' => $stale,
            'active_photo_mb' => round($activeBytes / 1024 / 1024, 2),
            'thumbnail_mb' => round($thumbnailBytes / 1024 / 1024, 2),
            'active_total_mb' => round(($activeBytes + $thumbnailBytes) / 1024 / 1024, 2),
        ];
    }

    private function cleanStaleThumbnails(Request $request)
    {
        $settings = $this->settings->settings();

        if (! $this->permissions->userCanAction(auth()->user(), $settings, 'upload_roles')) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('permission_denied', false, 403);
            }

            return $this->redirect(0, 'permission_denied');
        }

        $this->images->cleanupStaleThumbnails($this->paths->photosDir());
        $this->images->cleanupStaleThumbnails($this->paths->deletedDir(), $this->paths->deletedThumbsDir());

        if ($this->wantsJsonResponse($request)) {
            return $this->jsonStatus('thumbnails_cleaned', true, 200, [
                'message' => 'Stale thumbnails cleaned.',
                'maintenance_stats' => $this->thumbnailMaintenanceStats(),
            ]);
        }

        return $this->redirect(0, 'thumbnails_cleaned');
    }

    private function generateMissingThumbnails(Request $request)
    {
        $settings = $this->settings->settings();

        if (! $this->permissions->userCanAction(auth()->user(), $settings, 'upload_roles')) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('permission_denied', false, 403);
            }

            return $this->redirect(0, 'permission_denied');
        }

        /*
         * Generate thumbnails for active photos that are missing thumbnails.
         * Fail-safe: if GD is missing, redirect without breaking anything.
         */
        if (! extension_loaded('gd')) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('thumbnail_gd_missing', true, 200, [
                    'message' => 'GD is missing. Thumbnails were not generated.',
                    'maintenance_stats' => $this->thumbnailMaintenanceStats(),
                ]);
            }

            return $this->redirect(0, 'thumbnail_gd_missing');
        }

        if (! is_dir($this->paths->thumbsDir())) {
            @mkdir($this->paths->thumbsDir(), 02775, true);
        }

        $generated = 0;
        $failed = 0;

        foreach (glob($this->paths->photosDir() . '/device-*.*') ?: [] as $sourcePath) {
            if (! is_file($sourcePath)) {
                continue;
            }

            $filename = basename($sourcePath);

            if (! preg_match('/^device-\d+-\d+\.(jpg|jpeg|png|webp)$/i', $filename)) {
                continue;
            }

            $thumbPath = $this->paths->thumbPath($filename);

            if (is_file($thumbPath)) {
                continue;
            }

            if ($this->images->createThumbnail($sourcePath, $filename)) {
                $generated++;
            } else {
                $failed++;
            }
        }

        if (! is_dir($this->paths->deletedThumbsDir())) {
            @mkdir($this->paths->deletedThumbsDir(), 02775, true);
        }

        foreach (glob($this->paths->deletedDir() . '/device-*.*') ?: [] as $sourcePath) {
            if (! is_file($sourcePath)) {
                continue;
            }

            $filename = basename($sourcePath);

            if (! preg_match('/^device-\d+-\d+\.deleted-\d{8}-\d{6}\.(jpg|jpeg|png|webp)$/i', $filename)) {
                continue;
            }

            $thumbPath = $this->paths->deletedThumbPath($filename);

            if (is_file($thumbPath)) {
                continue;
            }

            $created = $this->images->createThumbnail($sourcePath, $filename);
            $generatedThumbPath = $this->paths->thumbPath($filename);

            if ($created && is_file($generatedThumbPath) && @rename($generatedThumbPath, $thumbPath) && is_file($thumbPath)) {
                $generated++;
            } else {
                if (is_file($generatedThumbPath)) {
                    @unlink($generatedThumbPath);
                }

                $failed++;
            }
        }

        if ($generated > 0 && $failed > 0) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('thumbnails_partial', true, 200, [
                    'generated' => $generated,
                    'failed' => $failed,
                    'message' => 'Generated ' . $generated . ' thumbnail(s). Failed: ' . $failed . '.',
                    'maintenance_stats' => $this->thumbnailMaintenanceStats(),
                ]);
            }

            return $this->redirect(0, 'thumbnails_partial');
        }

        if ($generated > 0) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('thumbnails_generated', true, 200, [
                    'generated' => $generated,
                    'failed' => $failed,
                    'message' => 'Generated ' . $generated . ' missing thumbnail(s).',
                    'maintenance_stats' => $this->thumbnailMaintenanceStats(),
                ]);
            }

            return $this->redirect(0, 'thumbnails_generated');
        }

        if ($failed > 0) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('thumbnails_failed', true, 200, [
                    'generated' => $generated,
                    'failed' => $failed,
                    'message' => 'Thumbnail generation failed for ' . $failed . ' file(s).',
                    'maintenance_stats' => $this->thumbnailMaintenanceStats(),
                ]);
            }

            return $this->redirect(0, 'thumbnails_failed');
        }

        if ($this->wantsJsonResponse($request)) {
            return $this->jsonStatus('thumbnails_none_missing', true, 200, [
                'generated' => $generated,
                'failed' => $failed,
                'message' => 'No missing thumbnails found.',
                'maintenance_stats' => $this->thumbnailMaintenanceStats(),
            ]);
        }

        return $this->redirect(0, 'thumbnails_none_missing');
    }

    private function addLink(Request $request, int $deviceId)
    {
        if (! $this->findExistingDevice($deviceId)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('device_not_found', false, 404);
            }

            return $this->redirect($deviceId, 'device_not_found');
        }

        $settings = $this->settings->settings();

        if (! $this->permissions->userCanAction(auth()->user(), $settings, 'upload_roles')) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('permission_denied', false, 403);
            }

            return $this->redirect($deviceId, 'permission_denied');
        }

        $filename = basename((string) $request->input('filename', ''));
        $targetInput = trim((string) $request->input('target_device_query', $request->input('target_device_id', '')));

        $targetDevice = $this->findDeviceFromInput($targetInput);
        $targetDeviceId = $targetDevice ? (int) $targetDevice->device_id : 0;

        $safeShortName = $this->photos->safeDevicePrefix($deviceId);
        $pattern = '/^' . preg_quote($safeShortName, '/') . '-[0-9]{1,3}\\.(jpg|jpeg|png|webp)$/i';

        if (! preg_match($pattern, $filename)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('invalid_filename', false, 422);
            }

            return $this->redirect($deviceId, 'invalid_filename');
        }

        if (! is_file($this->paths->photoPath($filename))) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('not_found', false, 404);
            }

            return $this->redirect($deviceId, 'not_found');
        }

        if ($targetDeviceId < 1 || $targetDeviceId === $deviceId) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('invalid_target_device', false, 422);
            }

            return $this->redirect($deviceId, 'invalid_target_device');
        }

        if ($this->links->exists($targetDeviceId, $deviceId, $filename)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('already_linked', true, 200, [
                    'message' => 'Photo is already linked to that device.',
                    'already_linked' => true,
                ]);
            }

            return $this->redirect($deviceId, 'already_linked');
        }

        if (! $this->links->add($targetDeviceId, $deviceId, $filename)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('link_add_failed', false, 500);
            }

            return $this->redirect($deviceId, 'link_add_failed');
        }

        if ($this->wantsJsonResponse($request)) {
            return $this->jsonStatus('link_added', true, 200, [
                'filename' => $filename,
                'target_device_id' => $targetDeviceId,
                'target_device_name' => $this->deviceShortLabel($targetDevice, $targetDeviceId),
                'can_delete' => $this->permissions->userCanAction(auth()->user(), $settings, 'delete_roles'),
            ]);
        }

        return $this->redirectAfterAction($request, $deviceId, 'link_added');
    }

    private function addIncomingLink(Request $request, int $deviceId)
    {
        if (! $this->findExistingDevice($deviceId)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('device_not_found', false, 404);
            }

            return $this->redirect($deviceId, 'device_not_found');
        }

        $settings = $this->settings->settings();

        if (! $this->permissions->userCanAction(auth()->user(), $settings, 'upload_roles')) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('permission_denied', false, 403);
            }

            return $this->redirect($deviceId, 'permission_denied');
        }

        $ownerDeviceId = (int) $request->input('owner_device_id', 0);
        $filename = basename((string) $request->input('filename', ''));

        $ownerDevice = $ownerDeviceId > 0 ? Device::find($ownerDeviceId) : null;

        if ($ownerDeviceId < 1 || $ownerDeviceId === $deviceId || ! $ownerDevice) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('invalid_target_device', false, 422);
            }

            return $this->redirect($deviceId, 'invalid_target_device');
        }

        $ownerKey = $this->photos->safeDevicePrefix($ownerDeviceId);
        $pattern = '/^' . preg_quote($ownerKey, '/') . '-[0-9]{1,3}\\.(jpg|jpeg|png|webp)$/i';

        if (! preg_match($pattern, $filename)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('invalid_filename', false, 422);
            }

            return $this->redirect($deviceId, 'invalid_filename');
        }

        if (! is_file($this->paths->photoPath($filename))) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('not_found', false, 404);
            }

            return $this->redirect($deviceId, 'not_found');
        }

        if ($this->links->exists($deviceId, $ownerDeviceId, $filename)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('already_linked', true, 200, [
                    'message' => 'Photo is already linked.',
                    'already_linked' => true,
                ]);
            }

            return $this->redirectAfterIncomingLink($request, $deviceId, $ownerDeviceId, 'already_linked');
        }

        if (! $this->links->add($deviceId, $ownerDeviceId, $filename)) {
            if ($this->wantsJsonResponse($request)) {
                return $this->jsonStatus('link_add_failed', false, 500);
            }

            return $this->redirectAfterIncomingLink($request, $deviceId, $ownerDeviceId, 'link_add_failed');
        }

        if ($this->wantsJsonResponse($request)) {
            $photoPath = $this->paths->photoPath($filename);
            $thumbPath = $this->paths->thumbPath($filename);
            $imageUrl = url('plugin/device-photo-package/image') . '?action=photo&filename=' . rawurlencode($filename);
            $thumbUrl = is_file($thumbPath)
                ? url('plugin/device-photo-package/image') . '?action=thumb&filename=' . rawurlencode($filename)
                : $imageUrl;

            $fileTime = is_file($photoPath) ? (int) filemtime($photoPath) : 0;
            $dateData = $this->dates->data($photoPath);

            $photo = [
                'filename' => $filename,
                'owner_device_id' => $ownerDeviceId,
                'owner_name' => $this->deviceShortLabel($ownerDevice, $ownerDeviceId),
                'url' => $imageUrl,
                'thumb_url' => $thumbUrl,
                'order_key' => 'linked:' . $ownerDeviceId . ':' . $filename,
                'display_order_index' => 9999,
                'photo_taken_display' => $dateData['photo_taken_display'] ?? null,
                'photo_taken_iso' => $dateData['photo_taken_iso'] ?? null,
                'file_date_display' => $dateData['file_date_display'] ?? ($fileTime > 0 ? date('Y-m-d H:i', $fileTime) : null),
                'file_date_iso' => $dateData['file_date_iso'] ?? ($fileTime > 0 ? date('c', $fileTime) : null),
            ];

            return $this->jsonStatus('link_added', true, 200, [
                'owner_device_id' => $ownerDeviceId,
                'filename' => $filename,
                'order_key' => $photo['order_key'],
                'html' => view('device-photo::partials.manager-linked-photo-card', [
                    'photo' => $photo,
                    'device' => Device::find($deviceId),
                    'can_reorder' => $this->permissions->userCanAction(auth()->user(), $settings, 'reorder_roles'),
                    'can_delete' => $this->permissions->userCanAction(auth()->user(), $settings, 'delete_roles'),
                ])->render(),
            ]);
        }

        return $this->redirectAfterIncomingLink($request, $deviceId, $ownerDeviceId, 'link_added');
    }

    private function pruneOrderForDevice(int $deviceId): void
    {
        if ($deviceId < 1) {
            return;
        }

        $safeShortName = $this->photos->safeDevicePrefix($deviceId);
        $validOrderKeys = [];

        foreach ($this->photos->listFilenamesForDevice($deviceId) as $filename) {
            if (is_string($filename) && $filename !== '' && is_file($this->paths->photoPath($filename))) {
                $validOrderKeys[] = $filename;
            }
        }

        foreach ($this->links->load($deviceId) as $link) {
            if (! is_array($link)) {
                continue;
            }

            $ownerDeviceId = (int) ($link['owner_device_id'] ?? 0);
            $filename = basename((string) ($link['filename'] ?? ''));

            if ($ownerDeviceId < 1 || $filename === '') {
                continue;
            }

            if (! preg_match('/^device-' . preg_quote((string) $ownerDeviceId, '/') . '-[0-9]{1,3}\.(jpg|jpeg|png|webp)$/i', $filename)) {
                continue;
            }

            if (! is_file($this->paths->photoPath($filename))) {
                continue;
            }

            $validOrderKeys[] = 'linked:' . $ownerDeviceId . ':' . $filename;
        }

        $this->order->prune($safeShortName, $validOrderKeys);
    }

    private function linkedTargetDeviceIdsForPhoto(int $ownerDeviceId, string $filename): array
    {
        $targetDeviceIds = [];

        foreach (glob($this->paths->linksDir() . '/device-*.json') ?: [] as $linkFile) {
            $targetDeviceId = (int) preg_replace('/[^0-9]/', '', basename($linkFile, '.json'));

            if ($targetDeviceId < 1) {
                continue;
            }

            foreach ($this->links->load($targetDeviceId) as $link) {
                if (! is_array($link)) {
                    continue;
                }

                $linkOwnerDeviceId = (int) ($link['owner_device_id'] ?? 0);
                $linkFilename = basename((string) ($link['filename'] ?? ''));

                if ($linkOwnerDeviceId === $ownerDeviceId && $linkFilename === $filename) {
                    $targetDeviceIds[$targetDeviceId] = true;
                }
            }
        }

        return array_keys($targetDeviceIds);
    }

    private function findExistingDevice(int $deviceId): ?Device
    {
        if ($deviceId < 1) {
            return null;
        }

        return Device::find($deviceId);
    }

    private function deviceShortLabel(?Device $device, int $fallbackDeviceId): string
    {
        if (! $device) {
            return 'device-' . $fallbackDeviceId;
        }

        foreach (['sysName', 'display', 'hostname'] as $field) {
            $value = trim((string) ($device->{$field} ?? ''));

            if ($value === '') {
                continue;
            }

            /*
             * Keep IP addresses intact, but shorten FQDN-style device names.
             */
            if (filter_var($value, FILTER_VALIDATE_IP)) {
                return $value;
            }

            return preg_replace('/\..*$/', '', $value) ?: $value;
        }

        return 'device-' . $fallbackDeviceId;
    }

    private function findDeviceFromInput(string $targetInput): ?Device
    {
        if ($targetInput !== '' && preg_match('/^\\s*(\\d+)\\b/', $targetInput, $matches)) {
            return Device::find((int) $matches[1]);
        }

        if ($targetInput !== '') {
            $exactMatches = Device::query()
                ->where('hostname', $targetInput)
                ->orWhere('sysName', $targetInput)
                ->orWhere('display', $targetInput)
                ->limit(2)
                ->get();

            if ($exactMatches->count() === 1) {
                return $exactMatches->first();
            }
        }

        if ($targetInput !== '') {
            $likeMatches = Device::query()
                ->where('hostname', 'like', '%' . $targetInput . '%')
                ->orWhere('sysName', 'like', '%' . $targetInput . '%')
                ->orWhere('display', 'like', '%' . $targetInput . '%')
                ->limit(2)
                ->get();

            if ($likeMatches->count() === 1) {
                return $likeMatches->first();
            }
        }

        return null;
    }

    private function redirectAfterIncomingLink(Request $request, int $deviceId, int $ownerDeviceId, ?string $status = null)
    {
        $this->pruneOrderForDevice($deviceId);

        $ownerDeviceQuery = trim((string) $request->input('owner_device_query', ''));

        $query = [
            'device_id' => $deviceId,
        ];

        if ($status !== null) {
            $query['status'] = $status;
        }

        if ($ownerDeviceQuery !== '') {
            $query['owner_device_query'] = $ownerDeviceQuery;
        } else {
            $query['owner_device_query'] = (string) $ownerDeviceId;
        }

        return redirect(url('plugin/device-photo') . '?' . http_build_query($query) . '#device-photo-incoming-link');
    }

    private function wantsJsonResponse(Request $request): bool
    {
        return $request->ajax() || $request->expectsJson() || $request->boolean('ajax');
    }

    private function jsonStatus(string $status, bool $ok = true, int $httpStatus = 200, array $extra = [])
    {
        return response()->json(array_merge([
            'ok' => $ok,
            'status' => $status,
        ], $extra), $httpStatus);
    }

    private function redirectAfterAction(Request $request, int $deviceId, ?string $status = null)
    {
        $this->pruneOrderForDevice($deviceId);

        $returnTo = (string) $request->input('return_to', '');

        if ($returnTo === 'overview') {
            $query = [];

            if ($status !== null) {
                $query['status'] = $status;
            }

            $anchor = trim((string) $request->input('return_anchor', ''));

            if ($anchor !== '' && preg_match('/^[A-Za-z0-9_-]{1,120}$/', $anchor)) {
                return redirect(url('plugin/device-photo') . ($query ? '?' . http_build_query($query) : '') . '#' . $anchor);
            }

            return redirect(url('plugin/device-photo') . ($query ? '?' . http_build_query($query) : ''));
        }

        $query = [];

        if ($deviceId > 0) {
            $query['device_id'] = $deviceId;
        }

        if ($status !== null) {
            $query['status'] = $status;
        }

        $anchor = trim((string) $request->input('return_anchor', ''));

        if ($anchor !== '' && preg_match('/^[A-Za-z0-9_-]{1,120}$/', $anchor)) {
            return redirect(url('plugin/device-photo') . ($query ? '?' . http_build_query($query) : '') . '#' . $anchor);
        }

        return $this->redirect($deviceId, $status);
    }

    private function redirect(int $deviceId, ?string $status = null)
    {
        $this->pruneOrderForDevice($deviceId);

        $query = [];

        if ($deviceId > 0) {
            $query['device_id'] = $deviceId;
        }

        if ($status !== null) {
            $query['status'] = $status;
        }

        return redirect(url('plugin/device-photo') . ($query ? '?' . http_build_query($query) : ''));
    }
}