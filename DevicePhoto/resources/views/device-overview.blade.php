@php
    $photoDir = base_path('html/device-photos');
    $photoUrlBase = url('device-photos');
    $thumbDir = base_path('html/device-photos/thumbs');
    $thumbUrlBase = url('device-photos/thumbs');

    $devicePhotoThumbUrl = function (string $filename) use ($photoUrlBase, $thumbDir, $thumbUrlBase): string {
        if (is_file($thumbDir . '/' . $filename)) {
            return $thumbUrlBase . '/' . rawurlencode($filename);
        }

        return $photoUrlBase . '/' . rawurlencode($filename);
    };

    $extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

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
            $filename = $safeShortName . '.' . $ext;
            $path = $photoDir . '/' . $filename;

            if (is_file($path)) {
                $photos[$filename] = [
                    'filename' => $filename,
                    'url' => $photoUrlBase . '/' . rawurlencode($filename),
                    'thumb_url' => $devicePhotoThumbUrl($filename),
                ];
            }
        }

        foreach ($extensions as $ext) {
            foreach (glob($photoDir . '/' . $safeShortName . '-*.' . $ext) ?: [] as $path) {
                $filename = basename($path);

                if (is_file($path)) {
                    $photos[$filename] = [
                        'filename' => $filename,
                        'url' => $photoUrlBase . '/' . rawurlencode($filename),
                        'thumb_url' => $devicePhotoThumbUrl($filename),
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
            'url' => $photoUrlBase . '/' . rawurlencode($filename),
            'thumb_url' => $devicePhotoThumbUrl($filename),
            'linked' => true,
            'owner_device_id' => $ownerDeviceId,
            'owner_name' => $ownerDeviceNames[$ownerDeviceId] ?? ('device-' . $ownerDeviceId),
        ];
    }

    $photoCount = count($photos);
    $modalId = 'device-photo-modal-' . $device->device_id;
@endphp

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
                   title="Manage Device Photos">
                    &hellip;
                </a>
            @endif
        </div>

        <div class="device-photo-grid">
            @foreach ($photos as $photo)
                <div class="device-photo-card" onclick="openDevicePhotoModal{{ $device->device_id }}('{{ $photo['url'] }}')">
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

    <div id="{{ $modalId }}" class="device-photo-modal" onclick="closeDevicePhotoModal{{ $device->device_id }}()">
        <button class="device-photo-modal-close" onclick="closeDevicePhotoModal{{ $device->device_id }}()" type="button">&times;</button>

        <div class="device-photo-toolbar" onclick="event.stopPropagation();">
            <button type="button" onclick="zoomDevicePhoto{{ $device->device_id }}(-0.25)">−</button>
            <span id="{{ $modalId }}-zoom">100%</span>
            <button type="button" onclick="zoomDevicePhoto{{ $device->device_id }}(0.25)">+</button>
            <button type="button" onclick="resetDevicePhotoZoom{{ $device->device_id }}()">Reset</button>
        </div>

        <div class="device-photo-modal-inner" onclick="event.stopPropagation();">
            <img id="{{ $modalId }}-img" src="" alt="Device photo" draggable="false">
        </div>
    </div>

    <script>
        var devicePhotoState{{ $device->device_id }} = {
            scale: 1,
            x: 0,
            y: 0,
            dragging: false,
            startX: 0,
            startY: 0
        };

        function updateDevicePhotoTransform{{ $device->device_id }}() {
            var modalId = '{{ $modalId }}';
            var img = document.getElementById(modalId + '-img');
            var zoomLabel = document.getElementById(modalId + '-zoom');
            var s = devicePhotoState{{ $device->device_id }};

            img.style.transform = 'translate(' + s.x + 'px, ' + s.y + 'px) scale(' + s.scale + ')';
            zoomLabel.textContent = Math.round(s.scale * 100) + '%';
        }

        function openDevicePhotoModal{{ $device->device_id }}(src) {
            var modal = document.getElementById('{{ $modalId }}');
            var img = document.getElementById('{{ $modalId }}-img');
            var s = devicePhotoState{{ $device->device_id }};

            s.scale = 1;
            s.x = 0;
            s.y = 0;
            s.dragging = false;

            img.src = src;
            modal.classList.add('is-open');
            updateDevicePhotoTransform{{ $device->device_id }}();
        }

        function closeDevicePhotoModal{{ $device->device_id }}() {
            var modal = document.getElementById('{{ $modalId }}');
            var img = document.getElementById('{{ $modalId }}-img');

            modal.classList.remove('is-open');
            img.src = '';
        }

        function zoomDevicePhoto{{ $device->device_id }}(delta) {
            var s = devicePhotoState{{ $device->device_id }};
            s.scale = Math.max(0.5, Math.min(5, s.scale + delta));

            if (s.scale <= 1) {
                s.x = 0;
                s.y = 0;
            }

            updateDevicePhotoTransform{{ $device->device_id }}();
        }

        function resetDevicePhotoZoom{{ $device->device_id }}() {
            var s = devicePhotoState{{ $device->device_id }};
            s.scale = 1;
            s.x = 0;
            s.y = 0;
            updateDevicePhotoTransform{{ $device->device_id }}();
        }

        (function () {
            var modalId = '{{ $modalId }}';
            var modal = document.getElementById(modalId);
            var img = document.getElementById(modalId + '-img');
            var s = devicePhotoState{{ $device->device_id }};

            modal.addEventListener('wheel', function (e) {
                if (!modal.classList.contains('is-open')) {
                    return;
                }

                e.preventDefault();

                var delta = e.deltaY < 0 ? 0.15 : -0.15;
                s.scale = Math.max(0.5, Math.min(5, s.scale + delta));

                if (s.scale <= 1) {
                    s.x = 0;
                    s.y = 0;
                }

                updateDevicePhotoTransform{{ $device->device_id }}();
            }, { passive: false });

            img.addEventListener('mousedown', function (e) {
                if (s.scale <= 1) {
                    return;
                }

                s.dragging = true;
                s.startX = e.clientX - s.x;
                s.startY = e.clientY - s.y;
                img.classList.add('is-dragging');
                e.preventDefault();
            });

            window.addEventListener('mousemove', function (e) {
                if (!s.dragging) {
                    return;
                }

                s.x = e.clientX - s.startX;
                s.y = e.clientY - s.startY;
                updateDevicePhotoTransform{{ $device->device_id }}();
            });

            window.addEventListener('mouseup', function () {
                s.dragging = false;
                img.classList.remove('is-dragging');
            });

            img.addEventListener('dblclick', function () {
                resetDevicePhotoZoom{{ $device->device_id }}();
            });

            /*
             * Close modal with ESC.
             */
            document.addEventListener('keydown', function (e) {
                var modal = document.getElementById('{{ $modalId }}');

                if (e.key === 'Escape' && modal.classList.contains('is-open')) {
                    closeDevicePhotoModal{{ $device->device_id }}();
                }
            });

            /*
             * Close modal when clicking outside the image.
             * The dark area is usually covered by .device-photo-modal-inner,
             * so we check both the modal and the inner container.
             */
            var inner = modal.querySelector('.device-photo-modal-inner');

            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    closeDevicePhotoModal{{ $device->device_id }}();
                }
            });

            if (inner) {
                inner.addEventListener('click', function (e) {
                    if (e.target === inner) {
                        closeDevicePhotoModal{{ $device->device_id }}();
                    }
                });
            }
        })();
    </script>
@else
    <div class="device-photo-wrapper">
        <div class="device-photo-header">
            <div class="device-photo-title">
                <i class="fa fa-camera"></i> Device Photos
            </div>

            @if ($can_manage_photos ?? false)
                <a href="{{ url('plugin/DevicePhoto') }}?device_id={{ $device->device_id }}"
                   class="device-photo-options"
                   title="Manage Device Photos">
                    &hellip;
                </a>
            @endif
        </div>

        <div class="device-photo-empty">
            No device photo found
        </div>
    </div>
@endif
