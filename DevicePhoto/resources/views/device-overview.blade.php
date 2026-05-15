@php
    $photoDir = storage_path('app/device-photos');
    $thumbDir = storage_path('app/device-photos/thumbs');

    $devicePhotoPhotoUrl = function (string $filename) use ($photoDir): string {
        $path = $photoDir . '/' . $filename;
        $version = is_file($path) ? (string) @filemtime($path) : '0';

        return url('plugin/v1/DevicePhoto') . '?action=photo&filename=' . rawurlencode($filename) . '&v=' . rawurlencode($version);
    };

    $devicePhotoThumbUrl = function (string $filename) use ($thumbDir, $devicePhotoPhotoUrl): string {
        $thumbPath = $thumbDir . '/' . $filename;

        if (is_file($thumbPath)) {
            $version = (string) @filemtime($thumbPath);

            return url('plugin/v1/DevicePhoto') . '?action=thumb&filename=' . rawurlencode($filename) . '&v=' . rawurlencode($version);
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
    $orderFile = storage_path('app/device-photos-order/' . $safeShortName . '.json');
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
    $linksFile = storage_path('app/device-photos-links/device-' . $device->device_id . '.json');
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

    $photoCount = count($photos);
    $modalId = 'device-photo-modal-' . $device->device_id;
@endphp

@include('DevicePhoto::resources.views.partials.photo-modal')

<style>
    .device-photo-wrapper {
        background: #f3f3f3;
        border: 1px solid #dedede;
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 22px;
    }

    .device-photo-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid #e1e1e1;
    }

    .device-photo-title {
        font-weight: bold;
        font-size: 15px;
        color: #333;
    }

    .device-photo-title i {
        margin-right: 6px;
    }

    .device-photo-options {
        border: 1px solid #ccc;
        background: #fff;
        border-radius: 6px;
        padding: 2px 9px;
        cursor: pointer;
        font-weight: bold;
        line-height: 20px;
    }

    .device-photo-options:hover {
        background: #f7f7f7;
    }

    .device-photo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 190px));
        gap: 14px;
        align-items: start;
    }

    .device-photo-linked-tooltip {
        display: none;
        position: absolute;
        top: 34px;
        right: 6px;
        max-width: 220px;
        background: rgba(0, 0, 0, 0.82);
        color: #fff;
        font-size: 11px;
        line-height: 1.35;
        padding: 6px 8px;
        border-radius: 6px;
        z-index: 3;
        pointer-events: none;
        text-align: left;
        box-shadow: 0 2px 8px rgba(0,0,0,0.25);
    }

    .device-photo-card:hover .device-photo-linked-tooltip {
        display: block;
    }

    .device-photo-linked-tooltip code {
        color: #fff;
        background: transparent;
        padding: 0;
    }

    .device-photo-linked-icon {
        position: absolute;
        top: 6px;
        right: 6px;
        width: 22px;
        height: 22px;
        line-height: 22px;
        text-align: center;
        border-radius: 50%;
        background: rgba(0, 0, 0, 0.72);
        color: #fff;
        font-size: 11px;
        z-index: 2;
        pointer-events: none;
    }

    .device-photo-card {
        position: relative;
        background: #fff;
        border: 1px solid #d7d7d7;
        border-radius: 8px;
        padding: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12);
        cursor: pointer;
        transition: transform 0.12s ease, box-shadow 0.12s ease;
    }

    .device-photo-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(0,0,0,0.18);
    }

    .device-photo-card img {
        display: block;
        width: 100%;
        max-height: 220px;
        object-fit: contain;
        border-radius: 5px;
    }

    .device-photo-empty {
        padding: 12px 14px;
        background: #eef7fb;
        border: 1px solid #c7e5f2;
        border-radius: 6px;
        color: #31708f;
    }

    .device-photo-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        inset: 0;
        background: rgba(0,0,0,0.84);
        align-items: center;
        justify-content: center;
        padding: 32px;
    }

    .device-photo-modal.is-open {
        display: flex;
    }

    .device-photo-modal-inner {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        width: 96vw;
        height: 92vh;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .device-photo-modal img {
        display: block;
        max-width: 96vw;
        max-height: 88vh;
        object-fit: contain;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 5px 30px rgba(0,0,0,0.45);
        cursor: grab;
        user-select: none;
        transform-origin: center center;
    }

    .device-photo-modal img.is-dragging {
        cursor: grabbing;
    }

    .device-photo-modal-close {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 0;
        background: #fff;
        color: #222;
        font-size: 24px;
        line-height: 36px;
        text-align: center;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0,0,0,0.35);
        z-index: 10001;
    }

    .device-photo-toolbar {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 10001;
        display: flex;
        gap: 6px;
        align-items: center;
        background: rgba(255,255,255,0.95);
        border-radius: 8px;
        padding: 6px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.35);
    }

    .device-photo-toolbar button {
        border: 1px solid #ccc;
        background: #fff;
        border-radius: 5px;
        padding: 4px 10px;
        cursor: pointer;
        font-weight: bold;
    }

    .device-photo-toolbar span {
        min-width: 48px;
        text-align: center;
        color: #333;
        font-size: 12px;
    }

    .device-photo-count {
        margin-bottom: 10px;
        color: #777;
        font-size: 12px;
    }


</style>

@if ($photoCount > 0)
    <div class="device-photo-wrapper">
        <div class="device-photo-header">
            <div class="device-photo-title">
                <i class="fa fa-camera"></i> Device Photos
            </div>

            {{-- Options button reserved for future admin upload/delete menu --}}
            @if ($can_manage_photos ?? false)
                <a href="{{ url('plugin/DevicePhoto') }}?device_id={{ $device->device_id }}"
                   class="device-photo-options"
                   title="Manage device photos">
                    &hellip;
                </a>
            @endif
        </div>

        <div class="device-photo-grid">
            @foreach ($photos as $photo)
                <div class="device-photo-card"
                     data-device-photo-preview-src="{{ $photo['url'] }}"
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
                <a href="{{ url('plugin/DevicePhoto') }}?device_id={{ $device->device_id }}"
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
