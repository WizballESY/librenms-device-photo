@include('device-photo::partials.styles')





@php
    $photoDir = storage_path(config('device-photo.photos_path', 'app/device-photos'));
    $thumbDir = $photoDir . '/thumbs';

    $devicePhotoPhotoUrl = function (string $filename) use ($photoDir): string {
        $path = $photoDir . '/' . $filename;
        $version = is_file($path) ? (string) @filemtime($path) : '0';

        return url('plugin/device-photo-package/image') . '?action=photo&filename=' . rawurlencode($filename) . '&v=' . rawurlencode($version);
    };

    $devicePhotoThumbUrl = function (string $filename) use ($thumbDir, $devicePhotoPhotoUrl): string {
        $thumbPath = $thumbDir . '/' . $filename;

        if (is_file($thumbPath)) {
            $version = (string) @filemtime($thumbPath);

            return url('plugin/device-photo-package/image') . '?action=thumb&filename=' . rawurlencode($filename) . '&v=' . rawurlencode($version);
        }

        return $devicePhotoPhotoUrl($filename);
    };

    $devicePhotoParseExifDate = function (?string $value): ?int {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        foreach (['Y:m:d H:i:s', 'Y-m-d H:i:s', 'Y:m:d H:i:sP', 'Y-m-d H:i:sP'] as $format) {
            $date = \DateTime::createFromFormat($format, $value);

            if ($date instanceof \DateTime) {
                return $date->getTimestamp();
            }
        }

        $timestamp = strtotime($value);

        return $timestamp === false ? null : $timestamp;
    };

    $devicePhotoTakenTimestamp = function (string $path) use ($devicePhotoParseExifDate): ?int {
        if (! is_file($path)) {
            return null;
        }

        $ext = strtolower((string) pathinfo($path, PATHINFO_EXTENSION));

        if (! in_array($ext, ['jpg', 'jpeg'], true)) {
            return null;
        }

        if (! function_exists('exif_read_data')) {
            return null;
        }

        $exif = @exif_read_data($path, 'EXIF', true);

        if (! is_array($exif)) {
            return null;
        }

        $value = $exif['EXIF']['DateTimeOriginal']
            ?? $exif['EXIF']['DateTimeDigitized']
            ?? $exif['IFD0']['DateTime']
            ?? null;

        return $devicePhotoParseExifDate(is_string($value) ? $value : null);
    };

    $devicePhotoDateIso = function (?int $timestamp): ?string {
        if (! $timestamp) {
            return null;
        }

        return date(DATE_ATOM, $timestamp);
    };

    $devicePhotoDateData = function (string $path) use ($devicePhotoTakenTimestamp, $devicePhotoDateIso): array {
        $fileDate = is_file($path) ? @filemtime($path) : null;
        $takenDate = $devicePhotoTakenTimestamp($path);

        return [
            'photo_taken_iso' => $devicePhotoDateIso($takenDate),
            'file_date_iso' => $devicePhotoDateIso($fileDate ?: null),
        ];
    };

    $extensions = ['jpg', 'jpeg', 'png', 'webp'];

    $nameSource = null;

    foreach (['sysName', 'hostname', 'display'] as $field) {
        if (!empty($device->$field)) {
            $nameSource = trim((string) $device->$field);
            break;
        }
    }

    if (empty($nameSource) && !empty($device->device_id)) {
        $nameSource = 'device-' . $device->device_id;
    }

    $shortName = $nameSource;

    if (str_contains($shortName, '.')) {
        $shortName = explode('.', $shortName)[0];
    }

    /*
     * Use LibreNMS device_id as stable photo key.
     * This survives hostname/display/sysName changes.
     */
    $safeShortName = 'device-' . $device->device_id;

    $photos = [];

    if (!empty($safeShortName) && is_dir($photoDir)) {
        foreach ($extensions as $ext) {
            foreach (glob($photoDir . '/' . $safeShortName . '-*.' . $ext) ?: [] as $path) {
                $filename = basename($path);

                if (is_file($path)) {
                    $photos[$filename] = [
                        'filename' => $filename,
                        'url' => $devicePhotoPhotoUrl($filename),
                        'thumb_url' => $devicePhotoThumbUrl($filename),
                        'photo_taken_iso' => $devicePhotoDateData($path)['photo_taken_iso'],
                        'file_date_iso' => $devicePhotoDateData($path)['file_date_iso'],
                    ];
                }
            }
        }
    }

    ksort($photos, SORT_NATURAL | SORT_FLAG_CASE);

    /*
     * Apply custom photo order from management page.
     * If no JSON order file exists, fall back to natural filename sorting.
     */
    $orderFile = storage_path(config('device-photo.order_path', 'app/device-photos-order')) . '/' . $safeShortName . '.json';
    $order = [];

    if (is_file($orderFile)) {
        $decoded = json_decode((string) file_get_contents($orderFile), true);
        $order = is_array($decoded) ? $decoded : [];
    }

    $orderedPhotos = [];

    foreach ($order as $filename) {
        if (isset($photos[$filename])) {
            $orderedPhotos[$filename] = $photos[$filename];
        }
    }

    foreach ($photos as $filename => $photo) {
        if (! isset($orderedPhotos[$filename])) {
            $orderedPhotos[$filename] = $photo;
        }
    }

    $photos = $orderedPhotos;

    /*
     * Append linked photos from other devices.
     * Own photos are shown first, linked photos after.
     */
    $linksFile = storage_path(config('device-photo.links_path', 'app/device-photos-links')) . '/device-' . $device->device_id . '.json';
    $links = [];

    if (is_file($linksFile)) {
        $decodedLinks = json_decode((string) file_get_contents($linksFile), true);
        $links = is_array($decodedLinks) ? $decodedLinks : [];
    }

    $ownerDeviceIds = [];

    foreach ($links as $link) {
        if (is_array($link)) {
            $ownerDeviceId = (int) ($link['owner_device_id'] ?? 0);

            if ($ownerDeviceId > 0) {
                $ownerDeviceIds[] = $ownerDeviceId;
            }
        }
    }

    $ownerDeviceIds = array_values(array_unique($ownerDeviceIds));
    $ownerDeviceNames = [];

    if (! empty($ownerDeviceIds)) {
        $ownerDevices = \App\Models\Device::query()
            ->whereIn('device_id', $ownerDeviceIds)
            ->get();

        foreach ($ownerDevices as $ownerDevice) {
            $ownerName = 'device-' . $ownerDevice->device_id;

            foreach (['sysName', 'display', 'hostname'] as $field) {
                if (! empty($ownerDevice->$field)) {
                    $ownerName = trim((string) $ownerDevice->$field);
                    $ownerName = str_contains($ownerName, '.') ? explode('.', $ownerName)[0] : $ownerName;
                    break;
                }
            }

            $ownerDeviceNames[(int) $ownerDevice->device_id] = $ownerName;
        }
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

        $photos['linked:' . $ownerDeviceId . ':' . $filename] = [
            'filename' => $filename,
            'url' => $devicePhotoPhotoUrl($filename),
            'thumb_url' => $devicePhotoThumbUrl($filename),
            'photo_taken_iso' => $devicePhotoDateData($photoDir . '/' . $filename)['photo_taken_iso'],
            'file_date_iso' => $devicePhotoDateData($photoDir . '/' . $filename)['file_date_iso'],
            'linked' => true,
            'owner_device_id' => $ownerDeviceId,
            'owner_name' => $ownerDeviceNames[$ownerDeviceId] ?? ('device-' . $ownerDeviceId),
        ];
    }

    /*
     * Re-apply custom order after linked photos have been added.
     * This supports mixed order keys from the management page:
     *
     * Owned:
     *   device-108-1.jpg
     *
     * Linked:
     *   linked:109:device-109-2.jpg
     */
    $mixedOrderedPhotos = [];

    foreach ($order as $item) {
        if (is_string($item) && isset($photos[$item])) {
            $mixedOrderedPhotos[$item] = $photos[$item];
        }
    }

    foreach ($photos as $key => $photo) {
        if (! isset($mixedOrderedPhotos[$key])) {
            $mixedOrderedPhotos[$key] = $photo;
        }
    }

    $photos = $mixedOrderedPhotos;

    $photoCount = count($photos);
@endphp

@include('device-photo::partials.photo-modal')

@if ($photoCount > 0)
    <div class="device-photo-wrapper">
        <div class="device-photo-header">
            <div class="device-photo-title">
                <i class="fa fa-camera"></i> Device Photos
            </div>

            {{-- Options button reserved for future admin upload/delete menu --}}
            @if ($can_manage_photos ?? false)
                <a href="{{ url('plugin/device-photo') }}?device_id={{ $device->device_id }}"
                   class="device-photo-options"
                   title="Manage device photos">
                    &hellip;
                </a>
            @endif
        </div>

        <div class="device-photo-grid">
            @foreach ($photos as $photo)
                <div class="device-photo-card"
                     data-device-photo-gallery="device-{{ $device->device_id }}" data-device-photo-preview-src="{{ $photo['url'] }}"
                     data-device-photo-taken="{{ $photo['photo_taken_iso'] ?? '' }}"
                     data-device-photo-file-date="{{ $photo['file_date_iso'] ?? '' }}">
                    <img src="{{ $photo['thumb_url'] ?? $photo['url'] }}" alt="Device photo">

                    @if (!empty($photo['linked']))
                        <span class="device-photo-linked-icon">
                            <i class="fa fa-link"></i>
                        </span>

                        <div class="device-photo-linked-tooltip">
                            <strong>Linked from:</strong><br>
                            <code>Device ID: {{ $photo['owner_device_id'] ?? '' }}</code>
                            @if (!empty($photo['owner_name']))
                                <br>{{ $photo['owner_name'] }}
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>


@else
    <div class="device-photo-wrapper">
        <div class="device-photo-header">
            <div class="device-photo-title">
                <i class="fa fa-camera"></i> Device Photos
            </div>

            @if ($can_manage_photos ?? false)
                <a href="{{ url('plugin/device-photo') }}?device_id={{ $device->device_id }}"
                   class="device-photo-options"
                   title="Manage device photos">
                    &hellip;
                </a>
            @endif
        </div>

        <div class="device-photo-empty">
            No device photo found
        </div>
    </div>
@endif



