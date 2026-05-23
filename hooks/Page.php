<?php

namespace WizballEsy\LibreNmsDevicePhoto\Hooks;

use App\Models\Device;
use App\Plugins\Hooks\PageHook;
use WizballEsy\LibreNmsDevicePhoto\Services\PhotoDateService;
use WizballEsy\LibreNmsDevicePhoto\Services\PhotoPermissionService;

class Page extends PageHook
{
    public function authorize(\Illuminate\Contracts\Auth\Authenticatable $user): bool
    {
        return $user->can('global-read');
    }

    private function userCanAction(?\Illuminate\Contracts\Auth\Authenticatable $user, array $settings, string $key): bool
    {
        return app(PhotoPermissionService::class)->userCanAction($user, $settings, $key);
    }

    private function deviceLabel(?Device $device, int $deviceId): string
    {
        if (! $device) {
            return 'device-' . $deviceId;
        }

        foreach (['sysName', 'display', 'hostname'] as $field) {
            if (! empty($device->$field)) {
                $value = trim((string) $device->$field);

                if ($value !== '') {
                    return str_contains($value, '.') ? explode('.', $value)[0] : $value;
                }
            }
        }

        return 'device-' . $deviceId;
    }

    private function resolveDeviceFromQuery(string $query): ?Device
    {
        $query = trim($query);

        if ($query === '') {
            return null;
        }

        if (preg_match('/^\s*(\d+)\b/', $query, $matches)) {
            return Device::find((int) $matches[1]);
        }

        $exactMatches = Device::query()
            ->where('hostname', $query)
            ->orWhere('sysName', $query)
            ->orWhere('display', $query)
            ->limit(2)
            ->get();

        if ($exactMatches->count() === 1) {
            return $exactMatches->first();
        }

        $likeMatches = Device::query()
            ->where('hostname', 'like', '%' . $query . '%')
            ->orWhere('sysName', 'like', '%' . $query . '%')
            ->orWhere('display', 'like', '%' . $query . '%')
            ->limit(2)
            ->get();

        if ($likeMatches->count() === 1) {
            return $likeMatches->first();
        }

        return null;
    }

    private function photosDir(): string
    {
        return storage_path(config('device-photo.photos_path', 'app/device-photos'));
    }

    private function thumbsDir(): string
    {
        return $this->photosDir() . '/thumbs';
    }

    private function orderDir(): string
    {
        return storage_path(config('device-photo.order_path', 'app/device-photos-order'));
    }

    private function linksDir(): string
    {
        return storage_path(config('device-photo.links_path', 'app/device-photos-links'));
    }

    private function ensureStorageDirectories(): void
    {
        foreach ([
            $this->photosDir(),
            $this->thumbsDir(),
            $this->photosDir() . '/deleted',
            $this->photosDir() . '/deleted/thumbs',
            $this->orderDir(),
            $this->linksDir(),
        ] as $dir) {
            if (! is_dir($dir)) {
                @mkdir($dir, 02775, true);
            }
        }
    }

    private function countStaleThumbnails(string $photoDir): int
    {
        $thumbDir = $photoDir . '/thumbs';
        $count = 0;

        if (! is_dir($thumbDir) || ! is_dir($photoDir)) {
            return 0;
        }

        foreach (glob($thumbDir . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE) ?: [] as $thumbPath) {
            $filename = basename($thumbPath);

            if (! is_file($photoDir . '/' . $filename)) {
                $count++;
            }
        }

        return $count;
    }

    private function buildGlobalOverview(string $photoDir): array
    {
        $photoFilesByDevice = [];
        $orphanedPhotos = [];
        $deletedPhotos = [];
        $linkedInByDevice = [];
        $linkedOutByDevice = [];
        $brokenLinks = [];

        $activePhotoCount = 0;
        $activePhotoBytes = 0;
        $deletedPhotoCount = 0;
        $deletedPhotoBytes = 0;

        $thumbnailCount = 0;
        $missingThumbnailCount = 0;
        $thumbnailBytes = 0;
        $gdAvailable = extension_loaded('gd');
        $thumbDir = $this->thumbsDir();
        $thumbDirWritable = is_dir($thumbDir) ? is_writable($thumbDir) : is_writable($this->photosDir());

        /*
         * Find all photo files on disk.
         */
        foreach (glob($photoDir . '/device-*.*') ?: [] as $path) {
            $filename = basename($path);

            if (! preg_match('/^device-(\d+)-\d+\.(jpg|jpeg|png|webp)$/i', $filename, $matches)) {
                continue;
            }

            $ownerDeviceId = (int) $matches[1];
            $size = is_file($path) ? filesize($path) : 0;

            $activePhotoCount++;
            $activePhotoBytes += $size;

            $thumbPath = $thumbDir . '/' . $filename;
            $hasThumbnail = is_file($thumbPath);

            if ($hasThumbnail) {
                $thumbnailCount++;
                $thumbnailBytes += filesize($thumbPath);
            }

            $photoFilesByDevice[$ownerDeviceId][] = [
                'filename' => $filename,
                'url' => $this->photoUrl($filename),
                'thumb_url' => $this->thumbUrl($filename),
                'photo_taken_display' => $this->photoDateData($photoDir . '/' . $filename)['photo_taken_display'],
                'photo_taken_iso' => $this->photoDateData($photoDir . '/' . $filename)['photo_taken_iso'],
                'file_date_display' => $this->photoDateData($photoDir . '/' . $filename)['file_date_display'],
                'file_date_iso' => $this->photoDateData($photoDir . '/' . $filename)['file_date_iso'],
                'size' => $size,
                'has_thumbnail' => $hasThumbnail,
            ];
        }

        /*
         * Count deleted photos and deleted thumbnails.
         */
        $deletedThumbnailCount = 0;
        $deletedThumbnailBytes = 0;

        foreach (glob($photoDir . '/deleted/*') ?: [] as $deletedPath) {
            if (! is_file($deletedPath)) {
                continue;
            }

            $filename = basename($deletedPath);

            if (! preg_match('/^device-\d+-\d+\.deleted-\d{8}-\d{6}\.(jpg|jpeg|png|webp)$/i', $filename)) {
                continue;
            }

            $size = filesize($deletedPath);

            $deletedPhotoCount++;
            $deletedPhotoBytes += $size;

            $originalFilename = preg_replace('/\.deleted-\d{8}-\d{6}\./i', '.', $filename);
            $thumbPath = $photoDir . '/deleted/thumbs/' . $filename;
            $hasThumbnail = is_file($thumbPath);

            $deletedPhotos[] = [
                'filename' => $filename,
                'original_filename' => $originalFilename,
                'url' => url('plugin/device-photo-package/image') . '?action=deleted_photo&filename=' . rawurlencode($filename),
                'thumb_url' => $hasThumbnail
                    ? url('plugin/device-photo-package/image') . '?action=deleted_thumb&filename=' . rawurlencode($filename)
                    : url('plugin/device-photo-package/image') . '?action=deleted_photo&filename=' . rawurlencode($filename),
                'photo_taken_display' => $this->photoDateData($deletedPath)['photo_taken_display'],
                'photo_taken_iso' => $this->photoDateData($deletedPath)['photo_taken_iso'],
                'file_date_display' => $this->photoDateData($deletedPath)['file_date_display'],
                'file_date_iso' => $this->photoDateData($deletedPath)['file_date_iso'],
                'size' => $size,
                'has_thumbnail' => $hasThumbnail,
            ];
        }

        usort($deletedPhotos, function ($a, $b) {
            return strcmp((string) ($a['filename'] ?? ''), (string) ($b['filename'] ?? ''));
        });

        foreach (glob($photoDir . '/deleted/thumbs/*') ?: [] as $deletedThumbPath) {
            if (! is_file($deletedThumbPath)) {
                continue;
            }

            $deletedThumbnailCount++;
            $deletedThumbnailBytes += filesize($deletedThumbPath);
        }

        /*
         * Read linked-photo JSON files.
         */
        $linkDir = $this->linksDir();

        foreach (glob($linkDir . '/device-*.json') ?: [] as $linkFile) {
            $targetDeviceId = (int) preg_replace('/[^0-9]/', '', basename($linkFile, '.json'));

            if ($targetDeviceId < 1) {
                continue;
            }

            $decoded = json_decode((string) file_get_contents($linkFile), true);
            $links = is_array($decoded) ? $decoded : [];

            foreach ($links as $link) {
                if (! is_array($link)) {
                    continue;
                }

                $ownerDeviceId = (int) ($link['owner_device_id'] ?? 0);
                $filename = basename((string) ($link['filename'] ?? ''));

                if ($ownerDeviceId < 1 || $filename === '') {
                    continue;
                }

                $entry = [
                    'target_device_id' => $targetDeviceId,
                    'target_name' => null,
                    'owner_device_id' => $ownerDeviceId,
                    'owner_name' => null,
                    'filename' => $filename,
                    'file_exists' => is_file($photoDir . '/' . $filename),
                ];

                $linkedInByDevice[$targetDeviceId][] = $entry;
                $linkedOutByDevice[$ownerDeviceId][] = $entry;

                if (! $entry['file_exists']) {
                    $brokenLinks[] = $entry;
                }
            }
        }

        $allDeviceIds = array_values(array_unique(array_merge(
            array_keys($photoFilesByDevice),
            array_keys($linkedInByDevice),
            array_keys($linkedOutByDevice)
        )));

        $devices = [];

        if (! empty($allDeviceIds)) {
            Device::query()
                ->whereIn('device_id', $allDeviceIds)
                ->get()
                ->each(function ($device) use (&$devices) {
                    $devices[(int) $device->device_id] = $device;
                });
        }

        /*
         * Add readable device labels to link entries.
         */
        foreach ($linkedInByDevice as $targetDeviceId => $links) {
            foreach ($links as $index => $link) {
                $ownerId = (int) ($link['owner_device_id'] ?? 0);
                $targetId = (int) ($link['target_device_id'] ?? 0);

                $linkedInByDevice[$targetDeviceId][$index]['owner_name'] = isset($devices[$ownerId])
                    ? $this->deviceLabel($devices[$ownerId], $ownerId)
                    : ('device-' . $ownerId);

                $linkedInByDevice[$targetDeviceId][$index]['target_name'] = isset($devices[$targetId])
                    ? $this->deviceLabel($devices[$targetId], $targetId)
                    : ('device-' . $targetId);
            }
        }

        foreach ($linkedOutByDevice as $ownerDeviceId => $links) {
            foreach ($links as $index => $link) {
                $ownerId = (int) ($link['owner_device_id'] ?? 0);
                $targetId = (int) ($link['target_device_id'] ?? 0);

                $linkedOutByDevice[$ownerDeviceId][$index]['owner_name'] = isset($devices[$ownerId])
                    ? $this->deviceLabel($devices[$ownerId], $ownerId)
                    : ('device-' . $ownerId);

                $linkedOutByDevice[$ownerDeviceId][$index]['target_name'] = isset($devices[$targetId])
                    ? $this->deviceLabel($devices[$targetId], $targetId)
                    : ('device-' . $targetId);
            }
        }

        $rows = [];

        foreach ($allDeviceIds as $deviceId) {
            $device = $devices[$deviceId] ?? null;

            if ($device && ! empty($photoFilesByDevice[$deviceId])) {
                foreach ($photoFilesByDevice[$deviceId] as $photo) {
                    if (empty($photo['has_thumbnail'])) {
                        $missingThumbnailCount++;
                    }
                }
            }

            if (! $device) {
                if (! empty($photoFilesByDevice[$deviceId])) {
                    foreach ($photoFilesByDevice[$deviceId] as $photo) {
                        $orphanedPhotos[] = [
                            'device_id' => $deviceId,
                            'filename' => $photo['filename'],
                            'url' => $photo['url'],
                            'thumb_url' => $photo['thumb_url'] ?? $photo['url'],
                            'photo_taken_display' => $photo['photo_taken_display'] ?? null,
                            'photo_taken_iso' => $photo['photo_taken_iso'] ?? null,
                            'file_date_display' => $photo['file_date_display'] ?? null,
                            'file_date_iso' => $photo['file_date_iso'] ?? null,
                            'size' => $photo['size'],
                        ];
                    }
                }

                continue;
            }

            $rows[] = [
                'device_id' => $deviceId,
                'name' => $this->deviceLabel($device, $deviceId),
                'owned_count' => count($photoFilesByDevice[$deviceId] ?? []),
                'linked_in_count' => count($linkedInByDevice[$deviceId] ?? []),
                'linked_out_count' => count($linkedOutByDevice[$deviceId] ?? []),
                'owned_photos' => array_slice($photoFilesByDevice[$deviceId] ?? [], 0, 6),
                'linked_in' => $linkedInByDevice[$deviceId] ?? [],
                'linked_out' => $linkedOutByDevice[$deviceId] ?? [],
            ];
        }

        usort($rows, function ($a, $b) {
            $nameCompare = strnatcasecmp((string) $a['name'], (string) $b['name']);

            if ($nameCompare !== 0) {
                return $nameCompare;
            }

            return ((int) $a['device_id']) <=> ((int) $b['device_id']);
        });

        usort($orphanedPhotos, function ($a, $b) {
            return strnatcasecmp($a['filename'], $b['filename']);
        });

        return [
            'rows' => $rows,
            'orphaned_photos' => $orphanedPhotos,
            'deleted_photos' => $deletedPhotos,
            'broken_links' => $brokenLinks,
            'active_photo_count' => $activePhotoCount,
            'active_photo_bytes' => $activePhotoBytes,
            'active_photo_mb' => round($activePhotoBytes / 1024 / 1024, 2),
            'active_total_bytes' => $activePhotoBytes + $thumbnailBytes,
            'active_total_mb' => round(($activePhotoBytes + $thumbnailBytes) / 1024 / 1024, 2),
            'deleted_photo_count' => $deletedPhotoCount,
            'deleted_photo_bytes' => $deletedPhotoBytes,
            'deleted_photo_mb' => round($deletedPhotoBytes / 1024 / 1024, 2),
            'deleted_thumbnail_count' => $deletedThumbnailCount,
            'deleted_thumbnail_bytes' => $deletedThumbnailBytes,
            'deleted_thumbnail_mb' => round($deletedThumbnailBytes / 1024 / 1024, 2),
            'deleted_total_bytes' => $deletedPhotoBytes + $deletedThumbnailBytes,
            'deleted_total_mb' => round(($deletedPhotoBytes + $deletedThumbnailBytes) / 1024 / 1024, 2),
            'gd_available' => $gdAvailable,
            'thumbnail_count' => $thumbnailCount,
            'missing_thumbnail_count' => $missingThumbnailCount,
            'stale_thumbnail_count' => $this->countStaleThumbnails($photoDir),
            'thumbnail_bytes' => $thumbnailBytes,
            'thumbnail_mb' => round($thumbnailBytes / 1024 / 1024, 2),
            'thumb_dir_writable' => $thumbDirWritable,
        ];
    }

    private function photoUrl(string $filename): string
    {
        $path = $this->photosDir() . '/' . $filename;
        $version = is_file($path) ? (string) @filemtime($path) : '0';

        return url('plugin/device-photo-package/image') . '?action=photo&filename=' . rawurlencode($filename) . '&v=' . rawurlencode($version);
    }

    private function thumbUrl(string $filename): string
    {
        $thumbPath = $this->thumbsDir() . '/' . $filename;

        if (is_file($thumbPath)) {
            $version = (string) @filemtime($thumbPath);

            return url('plugin/device-photo-package/image') . '?action=thumb&filename=' . rawurlencode($filename) . '&v=' . rawurlencode($version);
        }

        return $this->photoUrl($filename);
    }

    private function findBinary(string $binary): ?string
    {
        foreach (['/usr/bin/' . $binary, '/usr/local/bin/' . $binary, '/bin/' . $binary] as $path) {
            if (is_executable($path)) {
                return $path;
            }
        }

        $found = trim((string) @shell_exec('command -v ' . escapeshellarg($binary) . ' 2>/dev/null'));

        return $found !== '' && is_executable($found) ? $found : null;
    }

    private function heicConversionAvailable(): bool
    {
        $magick = $this->findBinary('magick');

        if (! $magick) {
            return false;
        }

        $output = (string) @shell_exec(escapeshellarg($magick) . ' -list format 2>/dev/null');

        foreach (preg_split('/\R/', $output) ?: [] as $line) {
            if (preg_match('/^\s*(HEIC|HEIF)\s+HEIC\s+.*r/i', $line)) {
                return true;
            }
        }

        return false;
    }

    private function exifToolAvailable(): bool
    {
        return (bool) $this->findBinary('exiftool');
    }

    private function webServerUploadInfo(): array
    {
        $serverSoftware = (string) ($_SERVER['SERVER_SOFTWARE'] ?? '');
        $serverSoftwareLower = strtolower($serverSoftware);

        $name = 'unknown';
        $hint = 'check your web server body size limit.';

        if (str_contains($serverSoftwareLower, 'nginx')) {
            $name = 'NGINX';
            $hint = 'check client_max_body_size.';
        } elseif (str_contains($serverSoftwareLower, 'apache')) {
            $name = 'Apache';
            $hint = 'check LimitRequestBody.';
        }

        return [
            'name' => $name,
            'software' => $serverSoftware,
            'hint' => $hint,
        ];
    }

    private function photoDateData(string $path): array
    {
        return app(PhotoDateService::class)->data($path);
    }

    public function data(array $settings = []): array
    {
        $request = request();

        $this->ensureStorageDirectories();

        $photoDir = $this->photosDir();
        $orderDir = $this->orderDir();

        if (! is_dir($orderDir)) {
            mkdir($orderDir, 02775, true);
        }

        $status = (string) $request->query('status', '');

        $messages = [
            'uploaded' => 'Photo uploaded.',
            'deleted' => 'Photo moved to deleted folder.',
            'order_updated' => 'Photo order updated.',
            'link_added' => 'Photo link added.',
            'link_removed' => 'Photo link removed.',
            'already_linked' => 'Photo is already linked.',
            'assigned' => 'Orphaned photo assigned.',
            'thumbnails_generated' => 'Missing thumbnails were generated.',
            'thumbnails_cleaned' => 'Stale thumbnails were removed.',
            'thumbnails_none_missing' => 'No missing thumbnails found.',
            'thumbnail_gd_missing' => 'GD image support is not available. Missing thumbnails could not be generated.',
            'thumbnails_partial' => 'Some thumbnails were generated, but one or more failed.',
            'thumbnails_failed' => 'Thumbnail generation failed.',
            'photo_taken_updated' => 'Photo taken date was written to EXIF metadata.',
            'deleted_photos_emptied' => 'Deleted photos were permanently removed.',
            'deleted_photos_empty' => 'No deleted photos were found.',
            'deleted_photo_permanently_deleted' => 'Deleted photo was permanently removed.',
            'restored' => 'Photo restored.',
            'restore_failed' => 'Could not restore photo.',
        ];

        $errors = [
            'device_not_found' => 'Device not found.',
            'no_file' => 'No file selected.',
            'upload_failed' => 'Upload failed.',
            'invalid_type' => 'Only jpg, jpeg, png, webp, heic and heif are allowed.',
            'heic_unavailable' => 'HEIC/HEIF conversion is not available on this server. Install ImageMagick with HEIC support.',
            'heic_convert_failed' => 'HEIC/HEIF conversion failed.',
            'too_large' => 'Maximum file size is 10 MB.',
            'invalid_image' => 'The uploaded file does not look like a valid image.',
            'image_too_large_pixels' => 'The uploaded image dimensions are too large. Maximum is 40 megapixels.',
            'invalid_filename' => 'Invalid filename.',
            'invalid_order' => 'Invalid photo order.',
            'invalid_target_device' => 'Invalid target device.',
            'invalid_link' => 'Invalid photo link.',
            'not_orphaned' => 'Photo is no longer orphaned.',
            'not_found' => 'Photo not found.',
            'assign_failed' => 'Could not assign orphaned photo.',
            'delete_failed' => 'Could not delete photo.',
            'permission_denied' => 'You do not have permission to perform that action.',
            'exiftool_unavailable' => 'ExifTool is not available on this server.',
            'photo_taken_unsupported_type' => 'Photo taken can only be written to JPG/JPEG files.',
            'invalid_photo_taken' => 'Invalid Photo taken date/time.',
            'photo_taken_failed' => 'Could not write Photo taken metadata.',
            'invalid_confirm_code' => 'The confirmation code did not match.',
            'unknown_action' => 'Unknown action.',
        ];

        $message = $messages[$status] ?? null;
        $error = $errors[$status] ?? null;

        $user = auth()->user();

        $canUpload = $this->userCanAction($user, $settings, 'upload_roles');
        $canDelete = $this->userCanAction($user, $settings, 'delete_roles');
        $canReorder = $this->userCanAction($user, $settings, 'reorder_roles');
        $canManage = $canUpload || $canDelete || $canReorder;

        $deviceId = (int) $request->query('device_id', 0);
        $device = $deviceId > 0 ? Device::find($deviceId) : null;

        /*
         * Device list used by link-to-device and orphan assignment search fields.
         * This intentionally includes all devices.
         */
        $linkTargetDevices = Device::query()
            ->select(['device_id', 'hostname', 'sysName', 'display'])
            ->orderBy('hostname')
            ->get()
            ->map(function ($targetDevice) {
                return [
                    'device_id' => (int) $targetDevice->device_id,
                    'label' => $this->deviceLabel($targetDevice, (int) $targetDevice->device_id),
                ];
            })
            ->values()
            ->all();

        /*
         * Device list used by "Add linked photo from another device".
         * This should only contain devices that actually own active photos.
         */
        $photoOwnerDeviceIds = [];

        foreach (glob($this->photosDir() . '/device-*.*') ?: [] as $photoPath) {
            $photoFilename = basename($photoPath);

            if (preg_match('/^device-(\d+)-\d+\.(jpg|jpeg|png|webp)$/i', $photoFilename, $matches)) {
                $photoOwnerDeviceIds[(int) $matches[1]] = true;
            }
        }

        unset($photoOwnerDeviceIds[$deviceId]);

        $linkOwnerDevices = [];

        if (! empty($photoOwnerDeviceIds)) {
            $linkOwnerDevices = Device::query()
                ->select(['device_id', 'hostname', 'sysName', 'display'])
                ->whereIn('device_id', array_keys($photoOwnerDeviceIds))
                ->orderBy('hostname')
                ->get()
                ->map(function ($ownerDevice) {
                    return [
                        'device_id' => (int) $ownerDevice->device_id,
                        'label' => $this->deviceLabel($ownerDevice, (int) $ownerDevice->device_id),
                    ];
                })
                ->values()
                ->all();
        }

        $shortName = null;
        $safeShortName = null;
        $photos = [];
        $linkedPhotos = [];

        $incomingOwnerQuery = trim((string) $request->query('owner_device_query', ''));
        $incomingOwnerDevice = null;
        $incomingOwnerPhotos = [];

        if ($device) {
            $nameSource = null;

            foreach (['sysName', 'hostname', 'display'] as $field) {
                if (! empty($device->$field)) {
                    $nameSource = trim((string) $device->$field);
                    break;
                }
            }

            if (empty($nameSource)) {
                $nameSource = 'device-' . $device->device_id;
            }

            $shortName = str_contains($nameSource, '.') ? explode('.', $nameSource)[0] : $nameSource;

            /*
             * Stable storage key.
             */
            $safeShortName = 'device-' . $device->device_id;

            foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
                foreach (glob($photoDir . '/' . $safeShortName . '-*.' . $ext) ?: [] as $path) {
                    $filename = basename($path);

                    if (is_file($path)) {
                        $photos[$filename] = [
                            'filename' => $filename,
                            'photo_type' => 'owned',
                            'order_key' => $filename,
                            'url' => $this->photoUrl($filename),
                            'thumb_url' => $this->thumbUrl($filename),
                            'photo_taken_display' => $this->photoDateData($photoDir . '/' . $filename)['photo_taken_display'],
                            'photo_taken_iso' => $this->photoDateData($photoDir . '/' . $filename)['photo_taken_iso'],
                            'file_date_display' => $this->photoDateData($photoDir . '/' . $filename)['file_date_display'],
                            'file_date_iso' => $this->photoDateData($photoDir . '/' . $filename)['file_date_iso'],
                        ];
                    }
                }
            }

            ksort($photos, SORT_NATURAL | SORT_FLAG_CASE);

            $orderFile = $orderDir . '/' . $safeShortName . '.json';
            $order = [];

            if (is_file($orderFile)) {
                $decoded = json_decode((string) file_get_contents($orderFile), true);
                $order = is_array($decoded) ? $decoded : [];
            }

            $ordered = [];

            foreach ($order as $filename) {
                if (isset($photos[$filename])) {
                    $ordered[$filename] = $photos[$filename];
                }
            }

            foreach ($photos as $filename => $photo) {
                if (! isset($ordered[$filename])) {
                    $ordered[$filename] = $photo;
                }
            }

            $photos = $ordered;

            /*
             * Find where this device's own photos are linked.
             * This is used to warn before deleting an owner photo that is used elsewhere.
             */
            foreach (glob($this->linksDir() . '/device-*.json') ?: [] as $linkFile) {
                $targetDeviceId = (int) preg_replace('/[^0-9]/', '', basename($linkFile, '.json'));

                if ($targetDeviceId < 1) {
                    continue;
                }

                $decodedTargetLinks = json_decode((string) file_get_contents($linkFile), true);
                $targetLinks = is_array($decodedTargetLinks) ? $decodedTargetLinks : [];

                foreach ($targetLinks as $targetLink) {
                    if (! is_array($targetLink)) {
                        continue;
                    }

                    $ownerDeviceId = (int) ($targetLink['owner_device_id'] ?? 0);
                    $filename = basename((string) ($targetLink['filename'] ?? ''));

                    if ($ownerDeviceId !== (int) $device->device_id || $filename === '' || ! isset($photos[$filename])) {
                        continue;
                    }

                    $targetDevice = Device::find($targetDeviceId);

                    $photos[$filename]['linked_to'][] = [
                        'device_id' => $targetDeviceId,
                        'name' => $this->deviceLabel($targetDevice, $targetDeviceId),
                    ];
                }
            }

            /*
             * Linked photos shown on this device.
             * The file is owned by another device, but displayed here.
             */
            $linksFile = $this->linksDir() . '/device-' . $device->device_id . '.json';
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

                if (! is_file($photoDir . '/' . $filename)) {
                    continue;
                }

                $ownerDevice = Device::find($ownerDeviceId);

                $linkedPhotos[$filename] = [
                    'filename' => $filename,
                    'photo_type' => 'linked',
                    'order_key' => 'linked:' . $ownerDeviceId . ':' . $filename,
                    'url' => $this->photoUrl($filename),
                    'thumb_url' => $this->thumbUrl($filename),
                    'photo_taken_display' => $this->photoDateData($photoDir . '/' . $filename)['photo_taken_display'],
                    'photo_taken_iso' => $this->photoDateData($photoDir . '/' . $filename)['photo_taken_iso'],
                    'file_date_display' => $this->photoDateData($photoDir . '/' . $filename)['file_date_display'],
                    'file_date_iso' => $this->photoDateData($photoDir . '/' . $filename)['file_date_iso'],
                    'owner_device_id' => $ownerDeviceId,
                    'owner_name' => $this->deviceLabel($ownerDevice, $ownerDeviceId),
                ];
            }

            /*
             * Assign mixed display order indexes.
             * Old order files with only owned filenames remain valid.
             */
            $existingOrderKeys = [];
            $orderTargets = [];

            foreach ($photos as $filename => $photo) {
                $key = (string) ($photo['order_key'] ?? $filename);
                $existingOrderKeys[$key] = true;
                $orderTargets[$key] = ['type' => 'owned', 'filename' => $filename];
            }

            foreach ($linkedPhotos as $filename => $photo) {
                $key = (string) ($photo['order_key'] ?? ('linked:' . ($photo['owner_device_id'] ?? 0) . ':' . $filename));
                $existingOrderKeys[$key] = true;
                $orderTargets[$key] = ['type' => 'linked', 'filename' => $filename];
            }

            $orderedKeys = [];

            foreach ($order as $item) {
                if (is_string($item) && isset($existingOrderKeys[$item]) && ! in_array($item, $orderedKeys, true)) {
                    $orderedKeys[] = $item;
                }
            }

            foreach (array_keys($existingOrderKeys) as $item) {
                if (! in_array($item, $orderedKeys, true)) {
                    $orderedKeys[] = $item;
                }
            }

            foreach ($orderedKeys as $index => $key) {
                $target = $orderTargets[$key] ?? null;

                if (! $target) {
                    continue;
                }

                if ($target['type'] === 'owned' && isset($photos[$target['filename']])) {
                    $photos[$target['filename']]['display_order_index'] = $index;
                }

                if ($target['type'] === 'linked' && isset($linkedPhotos[$target['filename']])) {
                    $linkedPhotos[$target['filename']]['display_order_index'] = $index;
                }
            }
        }

        $globalOverview = [
            'rows' => [],
            'orphaned_photos' => [],
            'broken_links' => [],
        ];

        if (! $device) {
            $globalOverview = $this->buildGlobalOverview($photoDir);
        }

        if ($device && $incomingOwnerQuery !== '') {
            $incomingOwnerDevice = $this->resolveDeviceFromQuery($incomingOwnerQuery);

            if ($incomingOwnerDevice && (int) $incomingOwnerDevice->device_id === (int) $device->device_id) {
                $incomingOwnerDevice = null;
            }

            if ($incomingOwnerDevice) {
                $ownerKey = 'device-' . (int) $incomingOwnerDevice->device_id;

                foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
                    foreach (glob($photoDir . '/' . $ownerKey . '-*.' . $ext) ?: [] as $path) {
                        $filename = basename($path);

                        if (is_file($path)) {
                            $incomingOwnerPhotos[$filename] = [
                                'filename' => $filename,
                                'url' => $this->photoUrl($filename),
                                'thumb_url' => $this->thumbUrl($filename),
                                'photo_taken_display' => $this->photoDateData($photoDir . '/' . $filename)['photo_taken_display'],
                                'photo_taken_iso' => $this->photoDateData($photoDir . '/' . $filename)['photo_taken_iso'],
                                'file_date_display' => $this->photoDateData($photoDir . '/' . $filename)['file_date_display'],
                                'file_date_iso' => $this->photoDateData($photoDir . '/' . $filename)['file_date_iso'],
                            ];
                        }
                    }
                }

                ksort($incomingOwnerPhotos, SORT_NATURAL | SORT_FLAG_CASE);
            }
        }

        $webServerUploadInfo = $this->webServerUploadInfo();

        return [
            'message' => $message,
            'error' => $error,
            'device' => $device,
            'device_id' => $deviceId,
            'short_name' => $shortName,
            'safe_short_name' => $safeShortName,
            'photos' => $photos,
            'linked_photos' => $linkedPhotos,
            'link_target_devices' => $linkTargetDevices,
            'link_owner_devices' => $linkOwnerDevices,
            'incoming_owner_query' => $incomingOwnerQuery,
            'incoming_owner_device' => $incomingOwnerDevice,
            'incoming_owner_photos' => $incomingOwnerPhotos,
            'can_upload' => $canUpload,
            'can_delete' => $canDelete,
            'can_reorder' => $canReorder,
            'can_manage' => $canManage,
            'global_overview' => ! $device,
            'global_photo_overview' => $globalOverview,
            'php_file_uploads' => ini_get('file_uploads'),
            'heic_conversion_available' => $this->heicConversionAvailable(),
            'exiftool_available' => $this->exifToolAvailable(),
            'php_upload_max_filesize' => ini_get('upload_max_filesize'),
            'php_post_max_size' => ini_get('post_max_size'),
            'web_server_name' => $webServerUploadInfo['name'],
            'web_server_software' => $webServerUploadInfo['software'],
            'web_server_upload_hint' => $webServerUploadInfo['hint'],
        ];
    }
}
