<?php

namespace WizballEsy\LibreNmsDevicePhoto\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use WizballEsy\LibreNmsDevicePhoto\Services\PhotoImageService;
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
        private readonly PhotoListService $photos,
        private readonly PhotoLinkService $links,
        private readonly PhotoMetadataService $metadata,
        private readonly PhotoOrderService $order,
        private readonly PhotoPathService $paths,
        private readonly PhotoPermissionService $permissions,
        private readonly PhotoSettingsService $settings,
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
            'clean_stale_thumbnails' => $this->cleanStaleThumbnails(),
            'generate_missing_thumbnails' => $this->generateMissingThumbnails(),
            'remove_broken_link' => $this->removeBrokenLink($request),
            'set_photo_taken' => $this->setPhotoTaken($request, $deviceId),
            'delete' => $this->deletePhoto($request, $deviceId),
            'assign_orphan_photo' => $this->assignOrphanPhoto($request),
            'delete_orphan_photo' => $this->deleteOrphanPhoto($request),
            default => $this->redirect($deviceId, 'unknown_action'),
        };
    }

    private function saveOrder(Request $request, int $deviceId)
    {
        if ($deviceId < 1) {
            return $this->redirect($deviceId, 'device_not_found');
        }

        $settings = $this->settings->settings();

        if (! $this->permissions->userCanAction(auth()->user(), $settings, 'reorder_roles')) {
            return $this->redirect($deviceId, 'permission_denied');
        }

        $orderJson = (string) $request->input('order_json', '[]');
        $decoded = json_decode($orderJson, true);

        if (! is_array($decoded)) {
            return $this->redirect($deviceId, 'invalid_order');
        }

        $safeShortName = $this->photos->safeDevicePrefix($deviceId);
        $existing = $this->photos->listFilenamesForDevice($deviceId);
        $existingLookup = array_flip($existing);

        $newOrder = [];

        foreach ($decoded as $filename) {
            $filename = basename((string) $filename);

            if (isset($existingLookup[$filename])) {
                $newOrder[] = $filename;
            }
        }

        foreach ($existing as $filename) {
            if (! in_array($filename, $newOrder, true)) {
                $newOrder[] = $filename;
            }
        }

        $this->order->save($safeShortName, $newOrder);

        return $this->redirect($deviceId, 'order_updated');
    }

    private function removeLink(Request $request, int $deviceId)
    {
        if ($deviceId < 1) {
            return $this->redirect($deviceId, 'device_not_found');
        }

        $settings = $this->settings->settings();

        if (! $this->permissions->userCanAction(auth()->user(), $settings, 'delete_roles')) {
            return $this->redirect($deviceId, 'permission_denied');
        }

        $filename = basename((string) $request->input('filename', ''));
        $ownerDeviceId = (int) $request->input('owner_device_id', 0);

        $removeLinkPattern = '/^device-' . preg_quote((string) $ownerDeviceId, '/') . '-[0-9]{1,3}\.(jpg|jpeg|png|webp)$/i';

        if ($ownerDeviceId < 1 || ! preg_match($removeLinkPattern, $filename)) {
            return $this->redirect($deviceId, 'invalid_filename');
        }

        $this->links->remove($deviceId, $ownerDeviceId, $filename);

        return $this->redirectAfterAction($request, $deviceId, 'link_removed');
    }

    private function removeOutgoingLink(Request $request, int $deviceId)
    {
        if ($deviceId < 1) {
            return $this->redirect($deviceId, 'device_not_found');
        }

        $settings = $this->settings->settings();

        if (! $this->permissions->userCanAction(auth()->user(), $settings, 'delete_roles')) {
            return $this->redirect($deviceId, 'permission_denied');
        }

        $filename = basename((string) $request->input('filename', ''));
        $targetDeviceId = (int) $request->input('target_device_id', 0);
        $safeShortName = $this->photos->safeDevicePrefix($deviceId);

        $pattern = '/^' . preg_quote($safeShortName, '/') . '-[0-9]{1,3}\.(jpg|jpeg|png|webp)$/i';

        if (! preg_match($pattern, $filename)) {
            return $this->redirect($deviceId, 'invalid_filename');
        }

        if ($targetDeviceId < 1 || ! Device::find($targetDeviceId)) {
            return $this->redirect($deviceId, 'invalid_target_device');
        }

        $this->links->remove($targetDeviceId, $deviceId, $filename);

        return $this->redirectAfterAction($request, $deviceId, 'link_removed');
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
            return $this->redirect(0, 'permission_denied');
        }

        $filename = basename((string) $request->input('filename', ''));
        $targetInput = trim((string) $request->input('target_device_query', $request->input('target_device_id', '')));

        $targetDevice = $this->findDeviceFromInput($targetInput);
        $targetDeviceId = $targetDevice ? (int) $targetDevice->device_id : 0;

        if (! preg_match('/^device-\d+-\d+\.(jpg|jpeg|png|webp)$/i', $filename)) {
            return $this->redirect(0, 'invalid_filename');
        }

        if (! is_file($this->paths->photoPath($filename))) {
            return $this->redirect(0, 'not_found');
        }

        if (! preg_match('/^device-(\d+)-\d+\.(jpg|jpeg|png|webp)$/i', $filename, $matches)) {
            return $this->redirect(0, 'invalid_filename');
        }

        $oldDeviceId = (int) $matches[1];

        /*
         * Only allow assigning photos whose original owner device no longer exists.
         */
        if ($oldDeviceId > 0 && Device::find($oldDeviceId)) {
            return $this->redirect(0, 'not_orphaned');
        }

        if (! $targetDevice) {
            return $this->redirect(0, 'invalid_target_device');
        }

        $pathInfo = pathinfo($filename);
        $ext = strtolower((string) ($pathInfo['extension'] ?? ''));

        if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            return $this->redirect(0, 'invalid_type');
        }

        $targetPrefix = 'device-' . $targetDeviceId;
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

        if (! @rename($this->paths->photoPath($filename), $this->paths->photoPath($targetName))) {
            return $this->redirect(0, 'assign_failed');
        }

        @chmod($this->paths->photoPath($targetName), 0664);

        /*
         * Keep the orphaned thumbnail with the photo when assigning it to a device.
         * If no orphaned thumbnail exists, thumbnail generation below will create one.
         */
        $oldThumbPath = $this->paths->thumbPath($filename);
        $newThumbPath = $this->paths->thumbPath($targetName);

        if (is_file($oldThumbPath)) {
            @rename($oldThumbPath, $newThumbPath);
            @chmod($newThumbPath, 0664);
        }

        /*
         * Create or refresh thumbnail for assigned orphaned photo if possible.
         */
        $this->images->createThumbnail($this->paths->photoPath($targetName), $targetName);

        /*
         * Update existing linked-photo JSON entries that pointed to the old orphaned filename.
         */
        foreach (glob($this->paths->linksDir() . '/device-*.json') ?: [] as $linkFile) {
            $jsonTargetDeviceId = (int) preg_replace('/[^0-9]/', '', basename($linkFile, '.json'));

            if ($jsonTargetDeviceId < 1) {
                continue;
            }

            $links = $this->links->load($jsonTargetDeviceId);
            $changed = false;

            foreach ($links as $index => $link) {
                if (! is_array($link)) {
                    continue;
                }

                if (basename((string) ($link['filename'] ?? '')) === $filename) {
                    $links[$index]['owner_device_id'] = $targetDeviceId;
                    $links[$index]['filename'] = $targetName;
                    $changed = true;
                }
            }

            if ($changed) {
                $this->links->save($jsonTargetDeviceId, $links);
            }
        }

        $targetSafeShortName = $this->photos->safeDevicePrefix($targetDeviceId);
        $order = $this->photos->listFilenamesForDevice($targetDeviceId);
        $this->order->save($targetSafeShortName, $order);

        return $this->redirect(0, 'assigned');
    }

    private function deleteOrphanPhoto(Request $request)
    {
        /*
         * Move an orphaned photo to deleted folder.
         * This does not permanently delete the file.
         */
        $settings = $this->settings->settings();

        if (! $this->permissions->userCanAction(auth()->user(), $settings, 'delete_roles')) {
            return $this->redirect(0, 'permission_denied');
        }

        $filename = basename((string) $request->input('filename', ''));

        if (! preg_match('/^device-\d+-\d+\.(jpg|jpeg|png|webp)$/i', $filename)) {
            return $this->redirect(0, 'invalid_filename');
        }

        if (! is_file($this->paths->photoPath($filename))) {
            return $this->redirect(0, 'not_found');
        }

        if (! preg_match('/^device-(\d+)-\d+\.(jpg|jpeg|png|webp)$/i', $filename, $matches)) {
            return $this->redirect(0, 'invalid_filename');
        }

        $oldDeviceId = (int) $matches[1];

        /*
         * Only allow delete_orphan_photo if the owner device no longer exists.
         */
        if ($oldDeviceId > 0 && Device::find($oldDeviceId)) {
            return $this->redirect(0, 'not_orphaned');
        }

        if (! is_dir($this->paths->deletedDir())) {
            @mkdir($this->paths->deletedDir(), 02775, true);
        }

        $pathInfo = pathinfo($filename);
        $deletedName = $pathInfo['filename'] . '.deleted-' . date('Ymd-His') . '.' . strtolower((string) $pathInfo['extension']);

        if (@rename($this->paths->photoPath($filename), $this->paths->deletedPath($deletedName))) {
            @chmod($this->paths->deletedPath($deletedName), 0664);
            $this->moveThumbnailToDeleted($filename, $deletedName);

            return $this->redirect(0, 'deleted');
        }

        return $this->redirect(0, 'delete_failed');
    }

    private function deletePhoto(Request $request, int $deviceId)
    {
        $filename = basename((string) $request->input('filename', ''));

        if ($deviceId < 1) {
            return $this->redirect($deviceId, 'device_not_found');
        }

        $settings = $this->settings->settings();

        if (! $this->permissions->userCanAction(auth()->user(), $settings, 'delete_roles')) {
            return $this->redirect($deviceId, 'permission_denied');
        }

        $safeShortName = $this->photos->safeDevicePrefix($deviceId);
        $pattern = '/^' . preg_quote($safeShortName, '/') . '-[0-9]{1,3}\\.(jpg|jpeg|png|webp)$/i';

        if (! preg_match($pattern, $filename)) {
            return $this->redirect($deviceId, 'invalid_filename');
        }

        $photoPath = $this->paths->photoPath($filename);

        if (! is_file($photoPath)) {
            return $this->redirect($deviceId, 'not_found');
        }

        if (! is_dir($this->paths->deletedDir())) {
            @mkdir($this->paths->deletedDir(), 02775, true);
        }

        $timestamp = date('Ymd-His');
        $deletedName = preg_replace('/\\.(jpg|jpeg|png|webp)$/i', '.deleted-' . $timestamp . '.$1', $filename);

        if (! is_string($deletedName) || $deletedName === '') {
            return $this->redirect($deviceId, 'delete_failed');
        }

        $deletedPath = $this->paths->deletedPath($deletedName);

        if (@rename($photoPath, $deletedPath)) {
            @chmod($deletedPath, 0664);

            $this->moveThumbnailToDeleted($filename, $deletedName);

            /*
             * Remove all links from other devices that pointed to this original photo.
             * Otherwise deleting an owned photo would leave broken linked-photo entries.
             */
            $this->links->removeAllForFilename($filename);

            $safeShortName = $this->photos->safeDevicePrefix($deviceId);
            $order = $this->photos->listFilenamesForDevice($deviceId);
            $order = array_values(array_filter($order, fn ($item) => $item !== $filename));
            $this->order->save($safeShortName, $order);

            return $this->redirectAfterAction($request, $deviceId, 'deleted');
        }

        return $this->redirect($deviceId, 'delete_failed');
    }

    private function moveThumbnailToDeleted(string $filename, string $deletedName): void
    {
        $thumbPath = $this->paths->thumbPath($filename);

        if (! is_file($thumbPath)) {
            return;
        }

        if (! is_dir($this->paths->deletedThumbsDir())) {
            @mkdir($this->paths->deletedThumbsDir(), 02775, true);
        }

        $deletedThumbPath = $this->paths->deletedThumbPath($deletedName);

        if (@rename($thumbPath, $deletedThumbPath)) {
            @chmod($deletedThumbPath, 0664);
        }
    }

    private function setPhotoTaken(Request $request, int $deviceId)
    {
        $filename = basename((string) $request->input('filename', ''));
        $photoTakenInput = (string) $request->input('photo_taken', '');

        if ($deviceId < 1) {
            return $this->redirect($deviceId, 'device_not_found');
        }

        $safeShortName = $this->photos->safeDevicePrefix($deviceId);
        $pattern = '/^' . preg_quote($safeShortName, '/') . '-\\d+\\.(jpg|jpeg)$/i';

        if (! preg_match($pattern, $filename)) {
            return $this->redirect($deviceId, 'invalid_filename');
        }

        $photoPath = $this->paths->photoPath($filename);

        if (! is_file($photoPath)) {
            return $this->redirect($deviceId, 'not_found');
        }

        if (! $this->metadata->exiftoolAvailable()) {
            return $this->redirect($deviceId, 'exiftool_unavailable');
        }

        $timestamp = $this->metadata->parsePhotoTakenInput($photoTakenInput);

        if (! $timestamp) {
            return $this->redirect($deviceId, 'invalid_photo_taken');
        }

        if (! $this->metadata->writePhotoTakenExif($photoPath, $timestamp)) {
            return $this->redirect($deviceId, 'photo_taken_failed');
        }

        /*
         * EXIF orientation/date may affect generated thumbnails and displayed metadata.
         * Refresh the thumbnail after writing metadata.
         */
        $this->images->createThumbnail($photoPath, $filename);

        return $this->redirect($deviceId, 'photo_taken_updated');
    }

    private function removeBrokenLink(Request $request)
    {
        /*
         * Remove a broken linked-photo entry from the target device JSON.
         * This only removes the link entry, not any photo file.
         */
        $settings = $this->settings->settings();

        if (! $this->permissions->userCanAction(auth()->user(), $settings, 'delete_roles')) {
            return $this->redirect(0, 'permission_denied');
        }

        $targetDeviceId = (int) $request->input('target_device_id', 0);
        $ownerDeviceId = (int) $request->input('owner_device_id', 0);
        $filename = basename((string) $request->input('filename', ''));

        if ($targetDeviceId < 1 || $ownerDeviceId < 1 || $filename === '') {
            return $this->redirect(0, 'invalid_link');
        }

        if (! preg_match('/^device-\d+-\d+\.(jpg|jpeg|png|webp)$/i', $filename)) {
            return $this->redirect(0, 'invalid_filename');
        }

        $links = $this->links->load($targetDeviceId);
        $newLinks = [];
        $removed = false;

        foreach ($links as $link) {
            if (! is_array($link)) {
                continue;
            }

            $linkOwnerDeviceId = (int) ($link['owner_device_id'] ?? 0);
            $linkFilename = basename((string) ($link['filename'] ?? ''));

            if ($linkOwnerDeviceId === $ownerDeviceId && $linkFilename === $filename) {
                $removed = true;
                continue;
            }

            $newLinks[] = $link;
        }

        if (! $removed) {
            return $this->redirect(0, 'not_found');
        }

        $this->links->save($targetDeviceId, $newLinks);

        return $this->redirectAfterAction($request, 0, 'link_removed');
    }

    private function cleanStaleThumbnails()
    {
        $settings = $this->settings->settings();

        if (! $this->permissions->userCanAction(auth()->user(), $settings, 'upload_roles')) {
            return $this->redirect(0, 'permission_denied');
        }

        $this->images->cleanupStaleThumbnails($this->paths->photosDir());

        return $this->redirect(0, 'thumbnails_cleaned');
    }

    private function generateMissingThumbnails()
    {
        $settings = $this->settings->settings();

        if (! $this->permissions->userCanAction(auth()->user(), $settings, 'upload_roles')) {
            return $this->redirect(0, 'permission_denied');
        }

        /*
         * Generate thumbnails for active photos that are missing thumbnails.
         * Fail-safe: if GD is missing, redirect without breaking anything.
         */
        if (! extension_loaded('gd')) {
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

        if ($generated > 0 && $failed > 0) {
            return $this->redirect(0, 'thumbnails_partial');
        }

        if ($generated > 0) {
            $this->images->cleanupStaleThumbnails($this->paths->photosDir());

            return $this->redirect(0, 'thumbnails_generated');
        }

        if ($failed > 0) {
            return $this->redirect(0, 'thumbnails_failed');
        }

        return $this->redirect(0, 'thumbnails_none_missing');
    }

    private function addLink(Request $request, int $deviceId)
    {
        if ($deviceId < 1) {
            return $this->redirect($deviceId, 'device_not_found');
        }

        $settings = $this->settings->settings();

        if (! $this->permissions->userCanAction(auth()->user(), $settings, 'delete_roles')) {
            return $this->redirect($deviceId, 'permission_denied');
        }

        $filename = basename((string) $request->input('filename', ''));
        $targetInput = trim((string) $request->input('target_device_query', $request->input('target_device_id', '')));

        $targetDevice = $this->findDeviceFromInput($targetInput);
        $targetDeviceId = $targetDevice ? (int) $targetDevice->device_id : 0;

        $safeShortName = $this->photos->safeDevicePrefix($deviceId);
        $pattern = '/^' . preg_quote($safeShortName, '/') . '-[0-9]{1,3}\\.(jpg|jpeg|png|webp)$/i';

        if (! preg_match($pattern, $filename)) {
            return $this->redirect($deviceId, 'invalid_filename');
        }

        if (! is_file($this->paths->photoPath($filename))) {
            return $this->redirect($deviceId, 'not_found');
        }

        if ($targetDeviceId < 1 || $targetDeviceId === $deviceId) {
            return $this->redirect($deviceId, 'invalid_target_device');
        }

        $this->links->add($targetDeviceId, $deviceId, $filename);

        return $this->redirectAfterAction($request, $deviceId, 'link_added');
    }

    private function addIncomingLink(Request $request, int $deviceId)
    {
        if ($deviceId < 1) {
            return $this->redirect($deviceId, 'device_not_found');
        }

        $settings = $this->settings->settings();

        if (! $this->permissions->userCanAction(auth()->user(), $settings, 'delete_roles')) {
            return $this->redirect($deviceId, 'permission_denied');
        }

        $ownerDeviceId = (int) $request->input('owner_device_id', 0);
        $filename = basename((string) $request->input('filename', ''));

        if ($ownerDeviceId < 1 || $ownerDeviceId === $deviceId || ! Device::find($ownerDeviceId)) {
            return $this->redirect($deviceId, 'invalid_target_device');
        }

        $ownerKey = $this->photos->safeDevicePrefix($ownerDeviceId);
        $pattern = '/^' . preg_quote($ownerKey, '/') . '-[0-9]{1,3}\\.(jpg|jpeg|png|webp)$/i';

        if (! preg_match($pattern, $filename)) {
            return $this->redirect($deviceId, 'invalid_filename');
        }

        if (! is_file($this->paths->photoPath($filename))) {
            return $this->redirect($deviceId, 'not_found');
        }

        $this->links->add($deviceId, $ownerDeviceId, $filename);

        return $this->redirectAfterIncomingLink($request, $deviceId, $ownerDeviceId, 'link_added');
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

    private function redirectAfterAction(Request $request, int $deviceId, ?string $status = null)
    {
        $returnTo = (string) $request->input('return_to', '');

        if ($returnTo === 'overview') {
            $query = [];

            if ($status !== null) {
                $query['status'] = $status;
            }

            return redirect(url('plugin/device-photo') . ($query ? '?' . http_build_query($query) : ''));
        }

        return $this->redirect($deviceId, $status);
    }

    private function redirect(int $deviceId, ?string $status = null)
    {
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