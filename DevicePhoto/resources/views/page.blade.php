<div class="container-fluid">
    <h2 style="margin-bottom: 6px;">
        {{ ($global_overview ?? false) ? 'Device Photos Overview' : 'Device Photo Manager' }}
    </h2>

    @if ($device)
        <div style="margin-bottom: 18px;">
            <a href="{{ url('device/' . $device->device_id) }}" class="btn btn-default btn-sm">
                <i class="fa fa-arrow-left"></i> Back to device
            </a>

            <a href="{{ url('plugin/DevicePhoto') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-camera"></i> Device Photos Overview
            </a>
        </div>
    @endif

    <div class="alert alert-warning" style="font-size: 12px;">
        <strong>Notice:</strong>
        This plugin was created with assistance from AI. Use at your own risk.
        Make sure you have tested it before using it in production.
    </div>

    @if ($message)
        <div class="alert alert-success">{{ $message }}</div>
    @endif

    @if ($error)
        <div class="alert alert-danger">{{ $error }}</div>
    @endif

    <style>
        .device-photo-preview-modal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 25000;
            background: rgba(0,0,0,0.88);
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .device-photo-preview-modal.is-open {
            display: flex;
        }

        .device-photo-preview-modal-inner {
            max-width: 96vw;
            max-height: 92vh;
            overflow: hidden;
            cursor: grab;
        }

        .device-photo-preview-modal-inner.dragging {
            cursor: grabbing;
        }

        .device-photo-preview-modal img {
            max-width: 92vw;
            max-height: 86vh;
            transform-origin: center center;
            user-select: none;
            -webkit-user-drag: none;
            transition: transform 0.04s linear;
        }

        .device-photo-preview-close {
            position: absolute;
            top: 14px;
            right: 18px;
            z-index: 25002;
            border: 0;
            background: rgba(255,255,255,0.95);
            color: #333;
            border-radius: 50%;
            width: 34px;
            height: 34px;
            font-size: 22px;
            line-height: 30px;
            cursor: pointer;
        }

        .device-photo-preview-toolbar {
            position: absolute;
            top: 14px;
            left: 14px;
            z-index: 25002;
            display: flex;
            gap: 6px;
            align-items: center;
            background: rgba(255,255,255,0.95);
            border-radius: 8px;
            padding: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.35);
        }

        .device-photo-preview-toolbar button {
            border: 1px solid #ccc;
            background: #fff;
            border-radius: 5px;
            padding: 4px 10px;
            cursor: pointer;
            font-weight: bold;
        }

        .device-photo-preview-toolbar span {
            min-width: 48px;
            text-align: center;
            color: #333;
            font-size: 12px;
        }

        [data-device-photo-preview-src] {
            cursor: zoom-in;
        }
    </style>

    <div id="device-photo-preview-modal" class="device-photo-preview-modal">
        <button type="button" class="device-photo-preview-close" id="device-photo-preview-close">&times;</button>

        <div class="device-photo-preview-toolbar" onclick="event.stopPropagation();">
            <button type="button" id="device-photo-preview-zoom-out">−</button>
            <span id="device-photo-preview-zoom-label">100%</span>
            <button type="button" id="device-photo-preview-zoom-in">+</button>
            <button type="button" id="device-photo-preview-reset">Reset</button>
        </div>

        <div id="device-photo-preview-inner" class="device-photo-preview-modal-inner">
            <img id="device-photo-preview-img" src="" alt="Device photo" draggable="false">
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modal = document.getElementById('device-photo-preview-modal');
            var inner = document.getElementById('device-photo-preview-inner');
            var img = document.getElementById('device-photo-preview-img');
            var closeBtn = document.getElementById('device-photo-preview-close');
            var zoomIn = document.getElementById('device-photo-preview-zoom-in');
            var zoomOut = document.getElementById('device-photo-preview-zoom-out');
            var resetBtn = document.getElementById('device-photo-preview-reset');
            var zoomLabel = document.getElementById('device-photo-preview-zoom-label');

            if (!modal || !inner || !img) {
                return;
            }

            var state = {
                scale: 1,
                x: 0,
                y: 0,
                dragging: false,
                startX: 0,
                startY: 0
            };

            function updateTransform() {
                img.style.transform = 'translate(' + state.x + 'px, ' + state.y + 'px) scale(' + state.scale + ')';
                zoomLabel.textContent = Math.round(state.scale * 100) + '%';
            }

            function resetImage() {
                state.scale = 1;
                state.x = 0;
                state.y = 0;
                state.dragging = false;
                updateTransform();
            }

            function openPreview(src) {
                img.src = src;
                resetImage();
                modal.classList.add('is-open');
            }

            function closePreview() {
                modal.classList.remove('is-open');
                img.src = '';
                resetImage();
            }

            document.addEventListener('click', function (e) {
                var el = e.target.closest('[data-device-photo-preview-src]');

                if (!el) {
                    return;
                }

                e.preventDefault();
                e.stopPropagation();

                var src = el.getAttribute('data-device-photo-preview-src');

                if (src) {
                    openPreview(src);
                }
            }, true);

            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    closePreview();
                }
            });

            closeBtn.addEventListener('click', closePreview);

            zoomIn.addEventListener('click', function () {
                state.scale = Math.min(8, state.scale + 0.25);
                updateTransform();
            });

            zoomOut.addEventListener('click', function () {
                state.scale = Math.max(0.25, state.scale - 0.25);
                updateTransform();
            });

            resetBtn.addEventListener('click', resetImage);

            inner.addEventListener('wheel', function (e) {
                e.preventDefault();

                if (e.deltaY < 0) {
                    state.scale = Math.min(8, state.scale + 0.15);
                } else {
                    state.scale = Math.max(0.25, state.scale - 0.15);
                }

                updateTransform();
            }, { passive: false });

            inner.addEventListener('mousedown', function (e) {
                e.preventDefault();
                state.dragging = true;
                state.startX = e.clientX - state.x;
                state.startY = e.clientY - state.y;
                inner.classList.add('dragging');
            });

            document.addEventListener('mousemove', function (e) {
                if (!state.dragging) {
                    return;
                }

                state.x = e.clientX - state.startX;
                state.y = e.clientY - state.startY;
                updateTransform();
            });

            document.addEventListener('mouseup', function () {
                state.dragging = false;
                inner.classList.remove('dragging');
            });

            inner.addEventListener('dblclick', function (e) {
                e.preventDefault();
                resetImage();
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && modal.classList.contains('is-open')) {
                    closePreview();
                }
            });
        });
    </script>


    @if ($global_overview ?? false)
        @php
            $overview = $global_photo_overview ?? ['rows' => [], 'orphaned_photos' => [], 'broken_links' => []];
        @endphp

        <div class="panel panel-default">
            <div class="panel-heading">
                <strong><i class="fa fa-camera"></i> Device Photos Overview</strong>
            </div>

            <div class="panel-body">
                <p class="text-muted">
                    This page shows devices with owned photos, linked photos, and orphaned photo files.
                    Upload and photo deletion are handled from each device's photo manager.
                </p>

                <style>
                    .device-photo-links-row td {
                        border-top: 0;
                    }

                    .device-photo-links-row td > div,
                    .device-photo-links-row td {
                        background: #fafafa;
                    }
                </style>

                <style>
                    .device-photo-summary-bar {
                        display: flex;
                        flex-wrap: wrap;
                        gap: 7px;
                        align-items: center;
                        margin: 14px 0 16px 0;
                    }

                    .device-photo-summary-item {
                        display: inline-flex;
                        align-items: baseline;
                        gap: 4px;
                        padding: 7px 10px;
                        background: #f8f8f8;
                        border: 1px solid #ddd;
                        border-radius: 7px;
                        white-space: nowrap;
                        line-height: 1.15;
                        cursor: help;
                    }

                    .device-photo-summary-item .number {
                        display: inline-block;
                        font-weight: bold;
                        font-size: 15px;
                        color: #555;
                    }

                    .device-photo-summary-item .label {
                        display: inline-block;
                        font-size: 12px;
                        color: #666;
                    }

                    .device-photo-summary-item.is-problem {
                        background: #f2dede;
                        border-color: #ebccd1;
                    }

                    .device-photo-summary-item.is-problem .number,
                    .device-photo-summary-item.is-problem .label {
                        color: #a94442;
                    }
                </style>

                <div class="device-photo-summary-bar">
                    <span class="device-photo-summary-item" title="Number of devices that currently have owned photos or linked photos.">
                        <span class="number">{{ count($overview['rows'] ?? []) }}</span><span class="label">devices</span>
                    </span>

                    <span class="device-photo-summary-item" title="Total number of active image files in the main photo folder.">
                        <span class="number">{{ $overview['active_photo_count'] ?? 0 }}</span><span class="label">active files</span>
                    </span>

                    <span class="device-photo-summary-item" title="Total size of active image files in the main photo folder.">
                        <span class="number">{{ $overview['active_photo_mb'] ?? 0 }} MB</span><span class="label">active size</span>
                    </span>

                    <span class="device-photo-summary-item {{ count($overview['orphaned_photos'] ?? []) > 0 ? 'is-problem' : '' }}" title="Photos whose original device ID no longer exists in LibreNMS.">
                        <span class="number">{{ count($overview['orphaned_photos'] ?? []) }}</span><span class="label">orphans</span>
                    </span>

                    <span class="device-photo-summary-item" title="Number of image files currently stored in the deleted folder.">
                        <span class="number">{{ $overview['deleted_photo_count'] ?? 0 }}</span><span class="label">deleted files</span>
                    </span>

                    <span class="device-photo-summary-item" title="Total size of image files currently stored in the deleted folder.">
                        <span class="number">{{ $overview['deleted_photo_mb'] ?? 0 }} MB</span><span class="label">deleted size</span>
                    </span>

                    <span class="device-photo-summary-item {{ count($overview['broken_links'] ?? []) > 0 ? 'is-problem' : '' }}" title="Number of link entries pointing to missing photo files.">
                        <span class="number">{{ count($overview['broken_links'] ?? []) }}</span><span class="label">broken links</span>
                    </span>

                    <span class="device-photo-summary-item" title="Generated thumbnails compared to total active photos. Thumbnails are used in overview lists, while the original image opens in the photo viewer.">
                        <span class="number">{{ $overview['thumbnail_count'] ?? 0 }} / {{ $overview['active_photo_count'] ?? 0 }}</span><span class="label">thumbnails</span>
                    </span>

                    <span class="device-photo-summary-item {{ ($overview['missing_thumbnail_count'] ?? 0) > 0 ? 'is-problem' : '' }}" title="Active photos that do not currently have a generated thumbnail.">
                        <span class="number">{{ $overview['missing_thumbnail_count'] ?? 0 }}</span><span class="label">missing thumbnails</span>
                    </span>
                </div>

                <style>
                    .device-photo-target-suggestions {
                        display: none;
                        position: absolute;
                        z-index: 9999;
                        left: 0;
                        top: 100%;
                        margin-top: 4px;
                        background: #fff;
                        border: 1px solid #ccc;
                        border-radius: 4px;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                        max-height: 320px;
                        overflow-y: auto;
                        min-width: 260px;
                        width: 100%;
                        font-size: 12px;
                    }

                    .device-photo-target-suggestion {
                        padding: 6px 8px;
                        cursor: pointer;
                        border-bottom: 1px solid #eee;
                    }

                    .device-photo-target-suggestion:hover {
                        background: #f3f7fb;
                    }

                    .device-photo-target-suggestion .device-id {
                        font-family: monospace;
                        color: #b00040;
                    }

                    .device-photo-target-suggestion .device-name {
                        margin-left: 6px;
                    }
                </style>

                <script>
                    window.DevicePhotoTargetDevices = @json(
                        collect($link_target_devices ?? [])->values()
                    );

                    document.addEventListener('DOMContentLoaded', function () {
                        var maxResults = 20;
                        var devices = window.DevicePhotoTargetDevices || [];

                        function normalize(value) {
                            return String(value || '').toLowerCase();
                        }

                        function findMatches(query) {
                            query = String(query || '').trim();

                            if (query === '') {
                                return [];
                            }

                            var q = normalize(query);
                            var exactId = [];
                            var startsWithId = [];
                            var startsWithName = [];
                            var containsName = [];

                            devices.forEach(function (device) {
                                var id = String(device.device_id);
                                var label = String(device.label || '');
                                var labelLower = normalize(label);

                                if (id === query) {
                                    exactId.push(device);
                                } else if (id.indexOf(query) === 0) {
                                    startsWithId.push(device);
                                } else if (labelLower.indexOf(q) === 0) {
                                    startsWithName.push(device);
                                } else if (labelLower.indexOf(q) !== -1) {
                                    containsName.push(device);
                                }
                            });

                            return exactId
                                .concat(startsWithId)
                                .concat(startsWithName)
                                .concat(containsName)
                                .slice(0, maxResults);
                        }

                        function closeAllSuggestions() {
                            document.querySelectorAll('.device-photo-target-suggestions').forEach(function (box) {
                                box.style.display = 'none';
                            });
                        }

                        function ensureSuggestionBox(input) {
                            var wrapper = input.closest('.input-group');
                            var box = wrapper.parentNode.querySelector('.device-photo-target-suggestions');

                            if (!box) {
                                box = document.createElement('div');
                                box.className = 'device-photo-target-suggestions';
                                wrapper.parentNode.appendChild(box);
                            }

                            return box;
                        }

                        function renderSuggestions(input) {
                            var box = ensureSuggestionBox(input);
                            var matches = findMatches(input.value);

                            box.innerHTML = '';

                            if (matches.length === 0) {
                                box.style.display = 'none';
                                return;
                            }

                            matches.forEach(function (device) {
                                var item = document.createElement('div');
                                item.className = 'device-photo-target-suggestion';

                                var id = document.createElement('span');
                                id.className = 'device-id';
                                id.textContent = device.device_id;

                                var name = document.createElement('span');
                                name.className = 'device-name';
                                name.textContent = device.label || '';

                                item.appendChild(id);
                                item.appendChild(document.createTextNode(' - '));
                                item.appendChild(name);

                                item.addEventListener('mousedown', function (e) {
                                    e.preventDefault();
                                    input.value = device.device_id + ' - ' + (device.label || '');
                                    box.style.display = 'none';
                                });

                                box.appendChild(item);
                            });

                            box.style.display = 'block';
                        }

                        document.querySelectorAll('.device-photo-target-input').forEach(function (input) {
                            input.addEventListener('input', function () {
                                renderSuggestions(input);
                            });

                            input.addEventListener('focus', function () {
                                renderSuggestions(input);
                            });

                            input.addEventListener('keydown', function (e) {
                                if (e.key === 'Escape') {
                                    closeAllSuggestions();
                                }
                            });
                        });

                        document.addEventListener('click', function (e) {
                            if (!e.target.closest('.device-photo-target-suggestions') && !e.target.closest('.device-photo-target-input')) {
                                closeAllSuggestions();
                            }
                        });
                    });
                </script>

                @if (empty($overview['gd_available']))
                    <div class="alert alert-warning" style="font-size: 12px; padding: 8px 10px;">
                        <strong>Thumbnail generation unavailable:</strong>
                        PHP GD extension is not installed. Original images will be used as thumbnails.
                    </div>
                @elseif (empty($overview['thumb_dir_writable']))
                    <div class="alert alert-danger" style="font-size: 12px; padding: 8px 10px;">
                        <strong>Thumbnail folder is not writable:</strong>
                        Check permissions on <code>html/device-photos/thumbs</code>.
                    </div>
                @elseif (($overview['missing_thumbnail_count'] ?? 0) > 0)
                    <div class="alert alert-warning" style="font-size: 12px; padding: 8px 10px;">
                        <form method="post" action="{{ url('plugin/v1/DevicePhoto') }}" data-device-photo-confirm="Generate missing thumbnails for active photos? Existing thumbnails will not be overwritten." style="display: inline;">
                            @csrf
                            <input type="hidden" name="action" value="generate_missing_thumbnails">
                            <input type="hidden" name="device_id" value="0">
                            <input type="hidden" name="return_to" value="overview">

                            <strong>{{ $overview['missing_thumbnail_count'] ?? 0 }}</strong>
                            active photo{{ ($overview['missing_thumbnail_count'] ?? 0) === 1 ? '' : 's' }} missing thumbnails.

                            <button type="submit" class="btn btn-warning btn-xs" style="margin-left: 8px;">
                                <i class="fa fa-magic"></i> Generate missing thumbnails
                            </button>
                        </form>
                    </div>
                @endif

                <input
                    type="text"
                    id="device-photo-overview-filter"
                    class="form-control"
                    placeholder="Filter by Device ID, device name or filename"
                    style="max-width: 520px; margin-bottom: 14px;"
                >

                <h4>Devices with photos or links</h4>

                @if (empty($overview['rows']))
                    <div class="alert alert-info">No device photos or linked photos found.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover table-condensed" id="device-photo-overview-table">
                            <thead>
                                <tr>
                                    <th title="LibreNMS device that owns photos or has linked photos.">Device</th>
                                    <th title="Photos physically owned by this device.">Owned photos</th>
                                    <th title="Photos owned by other devices, but shown on this device.">Linked in</th>
                                    <th title="Photos owned by this device, but shown on other devices.">Linked out</th>
                                    <th title="Thumbnail preview of photos owned by this device.">Preview</th>
                                    <th title="Open the device or manage photos for this device.">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($overview['rows'] as $row)
                                    <tr class="device-photo-overview-row"
                                        data-device-photo-row="{{ $row['device_id'] }}"
                                        data-filter="{{ strtolower($row['device_id'] . ' ' . $row['name'] . ' ' . collect($row['owned_photos'])->pluck('filename')->implode(' ') . ' ' . collect($row['linked_in'])->pluck('filename')->implode(' ') . ' ' . collect($row['linked_out'])->pluck('filename')->implode(' ')) }}">
                                        <td>
                                            <a href="{{ url('device/' . $row['device_id']) }}">
                                                <code>Device ID: {{ $row['device_id'] }}</code>
                                            </a>
                                            <br>
                                            <a href="{{ url('device/' . $row['device_id']) }}">
                                                {{ $row['name'] }}
                                            </a>
                                        </td>
                                        <td>{{ $row['owned_count'] }}</td>
                                        <td>{{ $row['linked_in_count'] }}</td>
                                        <td>{{ $row['linked_out_count'] }}</td>
                                        <td>
                                            @if (!empty($row['owned_photos']))
                                                <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                                    @foreach ($row['owned_photos'] as $photo)
                                                        <a href="{{ $photo['url'] }}" data-device-photo-preview-src="{{ $photo['url'] }}" title="{{ $photo['filename'] }}">
                                                            <img data-device-photo-preview-src="{{ $photo['url'] }}" src="{{ $photo['thumb_url'] ?? $photo['url'] }}" style="width: 54px; height: 42px; object-fit: contain; border: 1px solid #ddd; border-radius: 4px; background: #fff;">
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted">No owned photos</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a class="btn btn-default btn-xs" href="{{ url('device/' . $row['device_id']) }}">
                                                Open device
                                            </a>
                                            <a class="btn btn-primary btn-xs" href="{{ url('plugin/DevicePhoto') }}?device_id={{ $row['device_id'] }}">
                                                Manage photos
                                            </a>

                                            @if (!empty($row['linked_in']) || !empty($row['linked_out']))
                                                <button type="button"
                                                        class="btn btn-info btn-xs device-photo-toggle-links"
                                                        data-device-photo-target="{{ $row['device_id'] }}">
                                                    Show links
                                                </button>
                                            @endif
                                        </td>
                                    </tr>

                                    @if (!empty($row['linked_in']) || !empty($row['linked_out']))
                                        <tr class="device-photo-overview-row device-photo-links-row"
                                            data-device-photo-links="{{ $row['device_id'] }}"
                                            style="display: none;"
                                            data-filter="{{ strtolower($row['device_id'] . ' ' . $row['name'] . ' ' . collect($row['linked_in'])->pluck('filename')->implode(' ') . ' ' . collect($row['linked_out'])->pluck('filename')->implode(' ')) }}">
                                            <td colspan="6" style="background: #fafafa;">
                                                @if (!empty($row['linked_in']))
                                                    <div style="margin-bottom: 8px;">
                                                        <strong>Linked photos shown on this device:</strong>
                                                        <ul style="margin: 4px 0 0 18px;">
                                                            @foreach ($row['linked_in'] as $link)
                                                                <li>
                                                                    {{ $link['filename'] }}
                                                                    from
                                                                    <a href="{{ url('device/' . $link['owner_device_id']) }}">
                                                                        Device ID: {{ $link['owner_device_id'] }}
                                                                    </a>
                                                                    @if (!empty($link['owner_name']))
                                                                        - <a href="{{ url('device/' . $link['owner_device_id']) }}">{{ $link['owner_name'] }}</a>
                                                                    @endif

                                                                    @if ($can_delete)
                                                                        <form method="post" action="{{ url('plugin/v1/DevicePhoto') }}" style="display: inline;" data-device-photo-confirm="Remove this link? The original photo will not be deleted.">
                                                                            @csrf
                                                                            <input type="hidden" name="action" value="remove_link">
                                                                            <input type="hidden" name="return_to" value="overview">
                                                                            <input type="hidden" name="device_id" value="{{ $row['device_id'] }}">
                                                                            <input type="hidden" name="owner_device_id" value="{{ $link['owner_device_id'] }}">
                                                                            <input type="hidden" name="filename" value="{{ $link['filename'] }}">
                                                                            <button type="submit" class="btn btn-warning btn-xs">
                                                                                Remove link
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif

                                                @if (!empty($row['linked_out']))
                                                    <div>
                                                        <strong>Owned photos linked to other devices:</strong>
                                                        <ul style="margin: 4px 0 0 18px;">
                                                            @foreach ($row['linked_out'] as $link)
                                                                <li>
                                                                    {{ $link['filename'] }}
                                                                    linked to
                                                                    <a href="{{ url('device/' . $link['target_device_id']) }}">
                                                                        Device ID: {{ $link['target_device_id'] }}
                                                                    </a>
                                                                    @if (!empty($link['target_name']))
                                                                        - <a href="{{ url('device/' . $link['target_device_id']) }}">{{ $link['target_name'] }}</a>
                                                                    @endif

                                                                    @if ($can_delete)
                                                                        <form method="post" action="{{ url('plugin/v1/DevicePhoto') }}" style="display: inline;" data-device-photo-confirm="Remove this outgoing link? The original photo will not be deleted.">
                                                                            @csrf
                                                                            <input type="hidden" name="action" value="remove_outgoing_link">
                                                                            <input type="hidden" name="return_to" value="overview">
                                                                            <input type="hidden" name="device_id" value="{{ $row['device_id'] }}">
                                                                            <input type="hidden" name="target_device_id" value="{{ $link['target_device_id'] }}">
                                                                            <input type="hidden" name="filename" value="{{ $link['filename'] }}">
                                                                            <button type="submit" class="btn btn-warning btn-xs">
                                                                                Remove link
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <hr>

                <style>
                    .device-photo-orphan-suggestions {
                        display: none;
                        position: absolute;
                        z-index: 99999;
                        left: 0;
                        bottom: 100%;
                        margin-bottom: 4px;
                        background: #fff;
                        border: 1px solid #ccc;
                        border-radius: 4px;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.20);
                        max-height: 320px;
                        overflow-y: auto;
                        min-width: 260px;
                        width: 100%;
                        font-size: 12px;
                    }

                    .device-photo-orphan-suggestion {
                        padding: 6px 8px;
                        cursor: pointer;
                        border-bottom: 1px solid #eee;
                    }

                    .device-photo-orphan-suggestion:hover {
                        background: #f3f7fb;
                    }

                    .device-photo-orphan-suggestion .device-id {
                        font-family: monospace;
                        color: #b00040;
                    }

                    .device-photo-orphan-suggestion .device-name {
                        margin-left: 6px;
                    }
                </style>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var orphanDevices = @json(collect($link_target_devices ?? [])->values());
                        var maxResults = 20;

                        function normalize(value) {
                            return String(value || '').toLowerCase();
                        }

                        function findOrphanMatches(query) {
                            query = String(query || '').trim();

                            if (query === '') {
                                return [];
                            }

                            var q = normalize(query);
                            var exactId = [];
                            var startsWithId = [];
                            var startsWithName = [];
                            var containsName = [];

                            orphanDevices.forEach(function (device) {
                                var id = String(device.device_id);
                                var label = String(device.label || '');
                                var labelLower = normalize(label);

                                if (id === query) {
                                    exactId.push(device);
                                } else if (id.indexOf(query) === 0) {
                                    startsWithId.push(device);
                                } else if (labelLower.indexOf(q) === 0) {
                                    startsWithName.push(device);
                                } else if (labelLower.indexOf(q) !== -1) {
                                    containsName.push(device);
                                }
                            });

                            return exactId
                                .concat(startsWithId)
                                .concat(startsWithName)
                                .concat(containsName)
                                .slice(0, maxResults);
                        }

                        function closeOrphanSuggestions() {
                            document.querySelectorAll('.device-photo-orphan-suggestions').forEach(function (box) {
                                box.style.display = 'none';
                            });
                        }

                        function ensureOrphanSuggestionBox(input) {
                            var form = input.closest('form');
                            var box = form.querySelector('.device-photo-orphan-suggestions');

                            if (!box) {
                                box = document.createElement('div');
                                box.className = 'device-photo-orphan-suggestions';
                                form.appendChild(box);
                            }

                            return box;
                        }

                        function renderOrphanSuggestions(input) {
                            var box = ensureOrphanSuggestionBox(input);
                            var matches = findOrphanMatches(input.value);

                            box.innerHTML = '';

                            if (matches.length === 0) {
                                box.style.display = 'none';
                                return;
                            }

                            matches.forEach(function (device) {
                                var item = document.createElement('div');
                                item.className = 'device-photo-orphan-suggestion';

                                var id = document.createElement('span');
                                id.className = 'device-id';
                                id.textContent = device.device_id;

                                var name = document.createElement('span');
                                name.className = 'device-name';
                                name.textContent = device.label || '';

                                item.appendChild(id);
                                item.appendChild(document.createTextNode(' - '));
                                item.appendChild(name);

                                item.addEventListener('mousedown', function (e) {
                                    e.preventDefault();
                                    input.value = device.device_id + ' - ' + (device.label || '');
                                    box.style.display = 'none';
                                });

                                box.appendChild(item);
                            });

                            box.style.display = 'block';
                        }

                        document.querySelectorAll('.device-photo-orphan-target-input').forEach(function (input) {
                            input.addEventListener('input', function () {
                                renderOrphanSuggestions(input);
                            });

                            input.addEventListener('focus', function () {
                                renderOrphanSuggestions(input);
                            });

                            input.addEventListener('keydown', function (e) {
                                if (e.key === 'Escape') {
                                    closeOrphanSuggestions();
                                }
                            });
                        });

                        document.addEventListener('click', function (e) {
                            if (!e.target.closest('.device-photo-orphan-suggestions') && !e.target.closest('.device-photo-orphan-target-input')) {
                                closeOrphanSuggestions();
                            }
                        });
                    });
                </script>

                <h4>Orphaned photos</h4>

                <p class="text-muted">
                    Orphaned photos are image files where the original LibreNMS Device ID no longer exists.
                    This can happen if a device was deleted from LibreNMS, restored with a different ID,
                    or if files were copied manually.
                    You can assign the photo to an existing device, download it, or delete it. Deleted photos are moved to the deleted folder and are not permanently removed.
                </p>

                @if (empty($overview['orphaned_photos']))
                    <div class="alert alert-info">No orphaned photos found.</div>
                @else
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 240px)); gap: 14px;">
                        @foreach ($overview['orphaned_photos'] as $photo)
                            <div style="background: #f8f8f8; border: 1px solid #ddd; border-radius: 8px; padding: 10px;">
                                <img data-device-photo-preview-src="{{ $photo['url'] }}" src="{{ $photo['thumb_url'] ?? $photo['url'] }}" style="width: 100%; max-height: 160px; object-fit: contain; background: #fff; border-radius: 5px; margin-bottom: 8px;">
                                <div style="font-size: 12px;">
                                    <strong>{{ $photo['filename'] }}</strong><br>
                                    <span class="text-muted">Missing Device ID: {{ $photo['device_id'] }}</span>
                                </div>
                                <a href="{{ $photo['url'] }}" download="{{ $photo['filename'] }}" class="btn btn-default btn-xs btn-block" style="margin-top: 8px;">
                                    <i class="fa fa-download"></i> Download
                                </a>

                                @if ($can_upload)
                                    <form method="post" action="{{ url('plugin/v1/DevicePhoto') }}" style="margin-top: 8px; position: relative;" data-device-photo-confirm="Assign this orphaned photo to the selected device? The file will be renamed.">
                                        @csrf
                                        <input type="hidden" name="action" value="assign_orphan_photo">
                                        <input type="hidden" name="device_id" value="0">
                                        <input type="hidden" name="filename" value="{{ $photo['filename'] }}">

                                        <div class="input-group input-group-sm" style="max-width: 220px;">
                                            <input
                                                type="text"
                                                name="target_device_query"
                                                class="form-control device-photo-orphan-target-input"
                                                placeholder="Search Device ID or name"
                                                autocomplete="off"
                                                required
                                            >
                                            <span class="input-group-btn">
                                                <button type="submit" class="btn btn-primary">
                                                    Assign
                                                </button>
                                            </span>
                                        </div>
                                    </form>
                                @endif

                                @if ($can_delete)
                                    <form method="post" action="{{ url('plugin/v1/DevicePhoto') }}" style="margin-top: 8px;" data-device-photo-confirm="Delete this orphaned photo? It will be moved to the deleted folder and can be restored manually.">
                                        @csrf
                                        <input type="hidden" name="action" value="delete_orphan_photo">
                                        <input type="hidden" name="device_id" value="0">
                                        <input type="hidden" name="filename" value="{{ $photo['filename'] }}">

                                        <button type="submit" class="btn btn-danger btn-xs btn-block">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                <style>
                    .device-photo-confirm-backdrop {
                        display: none;
                        position: fixed;
                        inset: 0;
                        background: rgba(0, 0, 0, 0.45);
                        z-index: 20000;
                        align-items: center;
                        justify-content: center;
                    }

                    .device-photo-confirm-box {
                        background: #fff;
                        border-radius: 10px;
                        box-shadow: 0 8px 30px rgba(0,0,0,0.35);
                        width: 420px;
                        max-width: calc(100vw - 40px);
                        padding: 18px 20px;
                        border-top: 5px solid #337ab7;
                    }

                    .device-photo-confirm-title {
                        font-size: 18px;
                        font-weight: bold;
                        margin-bottom: 10px;
                    }

                    .device-photo-confirm-message {
                        font-size: 14px;
                        line-height: 1.45;
                        margin-bottom: 18px;
                        color: #333;
                    }

                    .device-photo-confirm-actions {
                        text-align: right;
                    }

                    .device-photo-confirm-actions .btn {
                        margin-left: 6px;
                    }
                </style>

                <div id="device-photo-confirm-backdrop" class="device-photo-confirm-backdrop">
                    <div class="device-photo-confirm-box">
                        <div class="device-photo-confirm-title">
                            <i class="fa fa-exclamation-circle"></i> Confirm action
                        </div>

                        <div id="device-photo-confirm-message" class="device-photo-confirm-message"></div>

                        <div class="device-photo-confirm-actions">
                            <button type="button" class="btn btn-default" id="device-photo-confirm-cancel">
                                Cancel
                            </button>
                            <button type="button" class="btn btn-primary" id="device-photo-confirm-ok">
                                Continue
                            </button>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var pendingForm = null;
                        var backdrop = document.getElementById('device-photo-confirm-backdrop');
                        var message = document.getElementById('device-photo-confirm-message');
                        var ok = document.getElementById('device-photo-confirm-ok');
                        var cancel = document.getElementById('device-photo-confirm-cancel');

                        if (!backdrop || !message || !ok || !cancel) {
                            return;
                        }

                        document.querySelectorAll('form[data-device-photo-confirm]').forEach(function (form) {
                            form.addEventListener('submit', function (e) {
                                if (form.getAttribute('data-device-photo-confirmed') === '1') {
                                    return;
                                }

                                e.preventDefault();

                                pendingForm = form;
                                message.textContent = form.getAttribute('data-device-photo-confirm') || 'Continue?';
                                backdrop.style.display = 'flex';
                            });
                        });

                        ok.addEventListener('click', function () {
                            if (!pendingForm) {
                                backdrop.style.display = 'none';
                                return;
                            }

                            pendingForm.setAttribute('data-device-photo-confirmed', '1');
                            backdrop.style.display = 'none';
                            pendingForm.submit();
                        });

                        cancel.addEventListener('click', function () {
                            pendingForm = null;
                            backdrop.style.display = 'none';
                        });

                        backdrop.addEventListener('click', function (e) {
                            if (e.target === backdrop) {
                                pendingForm = null;
                                backdrop.style.display = 'none';
                            }
                        });

                        document.addEventListener('keydown', function (e) {
                            if (e.key === 'Escape') {
                                pendingForm = null;
                                backdrop.style.display = 'none';
                            }
                        });
                    });
                </script>

                <hr>
                <h4>Broken links</h4>

                <p class="text-muted">
                    Broken links are photo link entries that point to an image file that no longer exists.
                    The target device still has a saved link, but the original photo file is missing.
                    Removing a broken link only removes the link entry. It does not delete any photo file.
                </p>

                @if (empty($overview['broken_links']))
                    <div class="alert alert-info">No broken links found.</div>
                @else

                    <div class="table-responsive">
                        <table class="table table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th>Target device</th>
                                    <th>Owner device</th>
                                    <th>File</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($overview['broken_links'] as $link)
                                    <tr>
                                        <td>
                                            <a href="{{ url('device/' . $link['target_device_id']) }}">
                                                <code>Device ID: {{ $link['target_device_id'] }}</code>
                                            </a>
                                            @if (!empty($link['target_name']))
                                                <br>
                                                <a href="{{ url('device/' . $link['target_device_id']) }}">
                                                    {{ $link['target_name'] }}
                                                </a>
                                            @endif
                                        </td>

                                        <td>
                                            @if (!empty($link['owner_name']))
                                                <a href="{{ url('device/' . $link['owner_device_id']) }}">
                                                    <code>Device ID: {{ $link['owner_device_id'] }}</code>
                                                </a>
                                                <br>
                                                <a href="{{ url('device/' . $link['owner_device_id']) }}">
                                                    {{ $link['owner_name'] }}
                                                </a>
                                            @else
                                                <code>Device ID: {{ $link['owner_device_id'] }}</code>
                                                <br>
                                                <span class="label label-warning">Missing device</span>
                                            @endif
                                        </td>

                                        <td>
                                            <code>{{ $link['filename'] }}</code>
                                            <br>
                                            <span class="label label-danger">Missing file</span>
                                        </td>

                                        <td>
                                            @if ($can_delete)
                                                <form method="post" action="{{ url('plugin/v1/DevicePhoto') }}" data-device-photo-confirm="Remove this broken photo link? The original photo file is already missing.">
                                                    @csrf
                                                    <input type="hidden" name="action" value="remove_broken_link">
                                                    <input type="hidden" name="return_to" value="overview">
                                                    <input type="hidden" name="target_device_id" value="{{ $link['target_device_id'] }}">
                                                    <input type="hidden" name="owner_device_id" value="{{ $link['owner_device_id'] }}">
                                                    <input type="hidden" name="filename" value="{{ $link['filename'] }}">

                                                    <button type="submit" class="btn btn-warning btn-xs">
                                                        <i class="fa fa-unlink"></i> Remove broken link
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted">No permission</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <script>
            (function () {
                var input = document.getElementById('device-photo-overview-filter');

                document.querySelectorAll('.device-photo-toggle-links').forEach(function (button) {
                    button.addEventListener('click', function () {
                        var id = button.getAttribute('data-device-photo-target');
                        var row = document.querySelector('[data-device-photo-links="' + id + '"]');

                        if (!row) {
                            return;
                        }

                        var isOpen = row.style.display === 'table-row';
                        row.style.display = isOpen ? 'none' : 'table-row';
                        button.textContent = isOpen ? 'Show links' : 'Hide links';
                    });
                });

                if (!input) {
                    return;
                }

                input.addEventListener('input', function () {
                    var q = input.value.toLowerCase();

                    document.querySelectorAll('tr[data-device-photo-row]').forEach(function (row) {
                        var haystack = row.getAttribute('data-filter') || '';
                        var visible = haystack.indexOf(q) !== -1;
                        var id = row.getAttribute('data-device-photo-row');

                        row.style.display = visible ? '' : 'none';

                        var linkRow = document.querySelector('[data-device-photo-links="' + id + '"]');
                        var button = document.querySelector('[data-device-photo-target="' + id + '"]');

                        if (linkRow && !visible) {
                            linkRow.style.display = 'none';
                        }

                        if (button && !visible) {
                            button.textContent = 'Show links';
                        }
                    });
                });
            })();
        </script>
    @else

    <style>
        .device-photo-confirm-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            z-index: 20000;
            align-items: center;
            justify-content: center;
        }

        .device-photo-confirm-box {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.35);
            width: 420px;
            max-width: calc(100vw - 40px);
            padding: 18px 20px;
            border-top: 5px solid #337ab7;
        }

        .device-photo-confirm-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .device-photo-confirm-message {
            font-size: 14px;
            line-height: 1.45;
            margin-bottom: 18px;
            color: #333;
        }

        .device-photo-confirm-actions {
            text-align: right;
        }

        .device-photo-confirm-actions .btn {
            margin-left: 6px;
        }
    </style>

    <div id="device-photo-confirm-backdrop" class="device-photo-confirm-backdrop">
        <div class="device-photo-confirm-box">
            <div class="device-photo-confirm-title">
                <i class="fa fa-exclamation-circle"></i> Confirm action
            </div>

            <div id="device-photo-confirm-message" class="device-photo-confirm-message"></div>

            <div class="device-photo-confirm-actions">
                <button type="button" class="btn btn-default" id="device-photo-confirm-cancel">
                    Cancel
                </button>
                <button type="button" class="btn btn-primary" id="device-photo-confirm-ok">
                    Continue
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var pendingForm = null;
            var backdrop = document.getElementById('device-photo-confirm-backdrop');
            var message = document.getElementById('device-photo-confirm-message');
            var ok = document.getElementById('device-photo-confirm-ok');
            var cancel = document.getElementById('device-photo-confirm-cancel');

            if (!backdrop || !message || !ok || !cancel) {
                return;
            }

            document.querySelectorAll('form[data-device-photo-confirm]').forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    if (form.getAttribute('data-device-photo-confirmed') === '1') {
                        return;
                    }

                    e.preventDefault();

                    pendingForm = form;
                    message.textContent = form.getAttribute('data-device-photo-confirm') || 'Continue?';
                    backdrop.style.display = 'flex';
                });
            });

            ok.addEventListener('click', function () {
                if (!pendingForm) {
                    backdrop.style.display = 'none';
                    return;
                }

                pendingForm.setAttribute('data-device-photo-confirmed', '1');
                backdrop.style.display = 'none';
                pendingForm.submit();
            });

            cancel.addEventListener('click', function () {
                pendingForm = null;
                backdrop.style.display = 'none';
            });

            backdrop.addEventListener('click', function (e) {
                if (e.target === backdrop) {
                    pendingForm = null;
                    backdrop.style.display = 'none';
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    pendingForm = null;
                    backdrop.style.display = 'none';
                }
            });
        });
    </script>

    @if (!$device)
        <div class="alert alert-warning">
            No device selected.
        </div>
    @else
        @if (!($can_manage ?? false))
            <div class="alert alert-danger">
                You do not have permission to manage photos for this device.
            </div>
        @else
        <style>
            .device-photo-manager-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(170px, 230px));
                gap: 14px;
            }

            .device-photo-manager-card {
                background: #f3f3f3;
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 10px;
                cursor: grab;
            }

            .device-photo-manager-card.dragging {
                opacity: 0.45;
                cursor: grabbing;
            }

            .device-photo-manager-card.drop-before {
                box-shadow: inset 10px 0 0 #337ab7;
            }

            .device-photo-manager-card.drop-after {
                box-shadow: inset -10px 0 0 #337ab7;
            }

            .device-photo-manager-card img {
                width: 100%;
                max-height: 180px;
                object-fit: contain;
                background: #fff;
                border-radius: 5px;
                margin-bottom: 10px;
                pointer-events: auto;
                cursor: zoom-in;
            }

            .device-photo-drag-hint {
                color: #777;
                margin-bottom: 12px;
                font-size: 13px;
            }
        </style>

        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>{{ $device->display ?? $device->hostname }}</strong>

                <div class="text-muted" style="margin-top: 4px; font-size: 12px;">
                    Device ID:
                    <code>{{ $device->device_id }}</code>

                    @if (!empty($device->sysName))
                        &nbsp;|&nbsp; SysName:
                        <code>{{ $device->sysName }}</code>
                    @endif

                    @if (!empty($device->hostname))
                        &nbsp;|&nbsp; Hostname:
                        <code>{{ $device->hostname }}</code>
                    @endif
                </div>
            </div>

            <div class="panel-body">
@if ($can_upload)
                <form method="post" action="{{ url('plugin/v1/DevicePhoto') }}" enctype="multipart/form-data" style="margin-bottom: 28px;" id="device-photo-upload-form">
                    @csrf
                    <input type="hidden" name="action" value="upload">
                    <input type="hidden" name="device_id" value="{{ $device->device_id }}">

                    <label style="display: block; margin-bottom: 8px;">Upload photos</label>

                    <style>
                        .device-photo-dropzone {
                            border: 2px dashed #b8b8b8;
                            border-radius: 10px;
                            background: #f7f7f7;
                            padding: 34px 20px;
                            text-align: center;
                            color: #555;
                            cursor: pointer;
                            transition: background 0.15s ease, border-color 0.15s ease;
                        }

                        .device-photo-dropzone.drag-active {
                            background: #eaf4ff;
                            border-color: #337ab7;
                        }

                        .device-photo-dropzone .main-text {
                            font-size: 16px;
                            font-weight: bold;
                            margin-bottom: 6px;
                        }

                        .device-photo-dropzone .sub-text {
                            font-size: 13px;
                            color: #777;
                        }

                        .device-photo-selected-files {
                            margin-top: 10px;
                            color: #555;
                            font-size: 13px;
                        }

                        .device-photo-file-list {
                            margin-top: 10px;
                            padding: 10px 12px;
                            background: #fff;
                            border: 1px solid #ddd;
                            border-radius: 6px;
                            display: none;
                        }

                        .device-photo-file-list ul {
                            margin: 6px 0 0 18px;
                            padding: 0;
                        }

                        .device-photo-file-list li {
                            margin-bottom: 3px;
                        }
                    </style>

                    <div id="device-photo-dropzone" class="device-photo-dropzone">
                        <div class="main-text">
                            <i class="fa fa-cloud-upload"></i> Drag and drop photos here
                        </div>
                        <div class="sub-text">
                            or click to browse for photos
                        </div>

                        <input
                            id="device-photo-file"
                            type="file"
                            name="photos[]"
                            multiple
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            style="display: none;"
                        >
                    </div>

                    <div id="device-photo-selected" class="device-photo-selected-files">
                        No photos selected
                    </div>

                    <div id="device-photo-file-list" class="device-photo-file-list">
                        <strong>Ready to upload:</strong>
                        <ul id="device-photo-file-list-items"></ul>
                    </div>

                    <div style="margin-top: 12px;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-upload"></i> Upload photos
                        </button>
                    </div>

                    <div class="text-muted" style="margin-top: 8px; font-size: 12px;">
                        Allowed file types: jpg, jpeg, png, webp. Max file size: 10 MB per file.
                    </div>

                    <div class="alert alert-info" style="margin-top: 12px; margin-bottom: 0; font-size: 12px; padding: 8px 10px;">
                        <strong>PHP upload limits:</strong>
                        <span style="margin-left: 8px;">
                            upload_max_filesize: <code>{{ $php_upload_max_filesize ?? 'unknown' }}</code>
                        </span>
                        <span style="margin-left: 8px;">
                            post_max_size: <code>{{ $php_post_max_size ?? 'unknown' }}</code>
                        </span>
                        <span style="margin-left: 8px;">
                            file_uploads: <code>{{ !empty($php_file_uploads) ? 'Enabled' : 'Disabled' }}</code>
                        </span>
                        <span style="margin-left: 8px;" class="text-muted">
                            Webserver upload limit must also be high enough.
                            Examples: Nginx <code>client_max_body_size</code>, Apache <code>LimitRequestBody</code>.
                        </span>
                    </div>
                </form>
                @else
                    <div class="alert alert-warning">
                        You do not have permission to upload photos.
                    </div>
                @endif

                @if ($can_upload)
                <script>
                    (function () {
                        var dropzone = document.getElementById('device-photo-dropzone');
                        var fileInput = document.getElementById('device-photo-file');
                        var selected = document.getElementById('device-photo-selected');

                        function updateSelectedText() {
                            var fileListBox = document.getElementById('device-photo-file-list');
                            var fileListItems = document.getElementById('device-photo-file-list-items');

                            fileListItems.innerHTML = '';

                            if (!fileInput.files || fileInput.files.length === 0) {
                                selected.textContent = 'No photos selected';
                                fileListBox.style.display = 'none';
                                return;
                            }

                            if (fileInput.files.length === 1) {
                                selected.textContent = '1 photo selected';
                            } else {
                                selected.textContent = fileInput.files.length + ' photos selected';
                            }

                            Array.prototype.forEach.call(fileInput.files, function (file) {
                                var li = document.createElement('li');
                                var sizeMb = file.size / 1024 / 1024;
                                li.textContent = file.name + ' (' + sizeMb.toFixed(2) + ' MB)';
                                fileListItems.appendChild(li);
                            });

                            fileListBox.style.display = 'block';
                        }

                        dropzone.addEventListener('click', function () {
                            fileInput.click();
                        });

                        fileInput.addEventListener('change', updateSelectedText);

                        dropzone.addEventListener('dragover', function (e) {
                            e.preventDefault();
                            dropzone.classList.add('drag-active');
                        });

                        dropzone.addEventListener('dragleave', function () {
                            dropzone.classList.remove('drag-active');
                        });

                        dropzone.addEventListener('drop', function (e) {
                            e.preventDefault();
                            dropzone.classList.remove('drag-active');

                            if (!e.dataTransfer || !e.dataTransfer.files || e.dataTransfer.files.length === 0) {
                                return;
                            }

                            var dt = new DataTransfer();

                            Array.prototype.forEach.call(e.dataTransfer.files, function (file) {
                                dt.items.add(file);
                            });

                            fileInput.files = dt.files;
                            updateSelectedText();
                        });

                        updateSelectedText();
                    })();
                </script>
                @endif

                <hr>

                <style>
                    .device-photo-target-suggestions {
                        display: none;
                        position: absolute;
                        z-index: 9999;
                        left: 0;
                        bottom: 100%;
                        margin-bottom: 4px;
                        background: #fff;
                        border: 1px solid #ccc;
                        border-radius: 4px;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                        max-height: 320px;
                        overflow-y: auto;
                        min-width: 260px;
                        width: 100%;
                        font-size: 12px;
                    }

                    .device-photo-target-suggestion {
                        padding: 6px 8px;
                        cursor: pointer;
                        border-bottom: 1px solid #eee;
                    }

                    .device-photo-target-suggestion:hover {
                        background: #f3f7fb;
                    }

                    .device-photo-target-suggestion .device-id {
                        font-family: monospace;
                        color: #b00040;
                    }

                    .device-photo-target-suggestion .device-name {
                        margin-left: 6px;
                    }
                </style>

                <script>
                    window.DevicePhotoTargetDevices = @json(
                        collect($link_target_devices ?? [])
                            ->filter(fn ($targetDevice) => $device && (int) $targetDevice['device_id'] !== (int) $device->device_id)
                            ->values()
                    );

                    document.addEventListener('DOMContentLoaded', function () {
                        var maxResults = 20;
                        var devices = window.DevicePhotoTargetDevices || [];

                        function normalize(value) {
                            return String(value || '').toLowerCase();
                        }

                        function findMatches(query) {
                            query = String(query || '').trim();

                            if (query === '') {
                                return [];
                            }

                            var q = normalize(query);
                            var exactId = [];
                            var startsWithId = [];
                            var startsWithName = [];
                            var containsName = [];

                            devices.forEach(function (device) {
                                var id = String(device.device_id);
                                var label = String(device.label || '');
                                var labelLower = normalize(label);

                                if (id === query) {
                                    exactId.push(device);
                                } else if (id.indexOf(query) === 0) {
                                    startsWithId.push(device);
                                } else if (labelLower.indexOf(q) === 0) {
                                    startsWithName.push(device);
                                } else if (labelLower.indexOf(q) !== -1) {
                                    containsName.push(device);
                                }
                            });

                            return exactId
                                .concat(startsWithId)
                                .concat(startsWithName)
                                .concat(containsName)
                                .slice(0, maxResults);
                        }

                        function closeAllSuggestions() {
                            document.querySelectorAll('.device-photo-target-suggestions').forEach(function (box) {
                                box.style.display = 'none';
                            });
                        }

                        function ensureSuggestionBox(input) {
                            var wrapper = input.closest('.input-group');
                            var box = wrapper.parentNode.querySelector('.device-photo-target-suggestions');

                            if (!box) {
                                box = document.createElement('div');
                                box.className = 'device-photo-target-suggestions';
                                wrapper.parentNode.appendChild(box);
                            }

                            return box;
                        }

                        function renderSuggestions(input) {
                            var box = ensureSuggestionBox(input);
                            var matches = findMatches(input.value);

                            box.innerHTML = '';

                            if (matches.length === 0) {
                                box.style.display = 'none';
                                return;
                            }

                            matches.forEach(function (device) {
                                var item = document.createElement('div');
                                item.className = 'device-photo-target-suggestion';

                                var id = document.createElement('span');
                                id.className = 'device-id';
                                id.textContent = device.device_id;

                                var name = document.createElement('span');
                                name.className = 'device-name';
                                name.textContent = device.label || '';

                                item.appendChild(id);
                                item.appendChild(document.createTextNode(' - '));
                                item.appendChild(name);

                                item.addEventListener('mousedown', function (e) {
                                    e.preventDefault();
                                    input.value = device.device_id + ' - ' + (device.label || '');
                                    box.style.display = 'none';
                                });

                                box.appendChild(item);
                            });

                            box.style.display = 'block';
                        }

                        document.querySelectorAll('.device-photo-target-input').forEach(function (input) {
                            input.addEventListener('input', function () {
                                renderSuggestions(input);
                            });

                            input.addEventListener('focus', function () {
                                renderSuggestions(input);
                            });

                            input.addEventListener('keydown', function (e) {
                                if (e.key === 'Escape') {
                                    closeAllSuggestions();
                                }
                            });
                        });

                        document.addEventListener('click', function (e) {
                            if (!e.target.closest('.device-photo-target-suggestions') && !e.target.closest('.device-photo-target-input')) {
                                closeAllSuggestions();
                            }
                        });
                    });
                </script>

                <h4 style="margin-bottom: 8px;">Photos owned by this device</h4>

                @if (count($photos) === 0)
                    <div class="alert alert-info">No device photo found</div>
                @else
                    @if ($can_reorder)
                    <div class="device-photo-drag-hint">
                        Drag and drop photos to change the order. The order is saved automatically.
                    </div>

                    <form method="post" action="{{ url('plugin/v1/DevicePhoto') }}" id="device-photo-order-form" style="margin-bottom: 14px;">
                        @csrf
                        <input type="hidden" name="action" value="save_order">
                        <input type="hidden" name="device_id" value="{{ $device->device_id }}">
                        <input type="hidden" name="order_json" id="device-photo-order-json" value="[]">

                        <button type="submit" class="btn btn-success btn-sm" style="display: none;">
                            <i class="fa fa-save"></i> Save order
                        </button>
                    </form>

                    @else
                    <div class="device-photo-drag-hint">
                        You do not have permission to reorder photos.
                    </div>
                    @endif

                    <div class="device-photo-manager-grid" id="device-photo-manager-grid">
                        @foreach ($photos as $photo)
                            <div class="device-photo-manager-card" draggable="{{ $can_reorder ? 'true' : 'false' }}" data-filename="{{ $photo['filename'] }}">
                                <img data-device-photo-preview-src="{{ $photo['url'] }}" src="{{ $photo['thumb_url'] ?? $photo['url'] }}" draggable="false">

                                @if (!empty($photo['linked_to']))
                                    <div class="alert alert-warning" style="font-size: 12px; padding: 6px 8px; margin-bottom: 8px;">
                                        <strong>
                                            <i class="fa fa-link"></i>
                                            Linked to {{ count($photo['linked_to']) }} device{{ count($photo['linked_to']) === 1 ? '' : 's' }}
                                        </strong>

                                        <div style="margin-top: 8px;">
                                            @foreach ($photo['linked_to'] as $linkedDevice)
                                                <div style="margin-bottom: 8px; padding-bottom: 8px; border-bottom: 1px solid #eadfbf;">
                                                    <div>
                                                        <a href="{{ url('device/' . $linkedDevice['device_id']) }}">
                                                            <code>Device ID: {{ $linkedDevice['device_id'] }}</code>
                                                        </a>
                                                    </div>

                                                    @if (!empty($linkedDevice['name']))
                                                        <div style="margin-top: 2px; word-break: break-word;">
                                                            <a href="{{ url('device/' . $linkedDevice['device_id']) }}">
                                                                {{ $linkedDevice['name'] }}
                                                            </a>
                                                        </div>
                                                    @endif

                                                    @if ($can_delete)
                                                        <form method="post" action="{{ url('plugin/v1/DevicePhoto') }}" style="margin-top: 6px;" data-device-photo-confirm="Remove this link? The original photo will not be deleted.">
                                                            @csrf
                                                            <input type="hidden" name="action" value="remove_outgoing_link">
                                                            <input type="hidden" name="device_id" value="{{ $device->device_id }}">
                                                            <input type="hidden" name="target_device_id" value="{{ $linkedDevice['device_id'] }}">
                                                            <input type="hidden" name="filename" value="{{ $photo['filename'] }}">

                                                            <button type="submit" class="btn btn-warning btn-xs">
                                                                <i class="fa fa-unlink"></i> Remove link
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <a href="{{ $photo['url'] }}" download="{{ $photo['filename'] }}" class="btn btn-default btn-sm btn-block" style="margin-bottom: 8px;">
                                    <i class="fa fa-download"></i> Download
                                </a>

                                @if ($can_upload)
                                <div class="text-muted" style="font-size: 12px; margin-bottom: 4px;">
                                    <i class="fa fa-link"></i> Link this photo to another device
                                </div>

                                <form method="post" action="{{ url('plugin/v1/DevicePhoto') }}" style="margin-bottom: 8px; position: relative;">
                                    @csrf
                                    <input type="hidden" name="action" value="add_link">
                                    <input type="hidden" name="device_id" value="{{ $device->device_id }}">
                                    <input type="hidden" name="filename" value="{{ $photo['filename'] }}">

                                    <div class="input-group input-group-sm">
                                        <input
                                            type="text"
                                            name="target_device_query"
                                            class="form-control device-photo-target-input"
                                            placeholder="Search device ID or name"
                                            autocomplete="off"
                                            required
                                        >
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn btn-default" title="Add link">
                                                <i class="fa fa-link"></i>
                                            </button>
                                        </span>
                                    </div>
                                </form>
                                @endif

                                @if ($can_delete)
                                @php
                                    $linkedCount = count($photo['linked_to'] ?? []);

                                    if ($linkedCount > 0) {
                                        $deleteConfirm = 'This photo is linked to ' . $linkedCount . ' other device(s). Deleting it will move the photo to the deleted folder and remove all links to it. Continue?';
                                    } else {
                                        $deleteConfirm = 'Delete this photo? It will be moved to the deleted folder.';
                                    }
                                @endphp

                                <form method="post" action="{{ url('plugin/v1/DevicePhoto') }}" class="device-photo-delete-form" data-device-photo-confirm="{{ $deleteConfirm }}">
                                    @csrf
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="device_id" value="{{ $device->device_id }}">
                                    <input type="hidden" name="filename" value="{{ $photo['filename'] }}">

                                    <button type="submit" class="btn btn-danger btn-sm btn-block">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </form>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    @if ($can_reorder)
                    <script>
                        (function () {
                            var grid = document.getElementById('device-photo-manager-grid');
                            var orderInput = document.getElementById('device-photo-order-json');
                            var dragged = null;

                            function cards() {
                                return Array.prototype.slice.call(grid.querySelectorAll('.device-photo-manager-card'));
                            }

                            function updateOrderJson() {
                                var order = cards().map(function (card) {
                                    return card.getAttribute('data-filename');
                                });

                                orderInput.value = JSON.stringify(order);
                            }

                            function clearDropClasses() {
                                cards().forEach(function (card) {
                                    card.classList.remove('drop-before');
                                    card.classList.remove('drop-after');
                                });
                            }

                            grid.addEventListener('dragstart', function (e) {
                                var card = e.target.closest('.device-photo-manager-card');

                                if (!card) {
                                    return;
                                }

                                dragged = card;
                                card.classList.add('dragging');
                                e.dataTransfer.effectAllowed = 'move';
                                e.dataTransfer.setData('text/plain', card.getAttribute('data-filename'));
                            });

                            grid.addEventListener('dragend', function () {
                                if (dragged) {
                                    dragged.classList.remove('dragging');
                                }

                                clearDropClasses();
                                dragged = null;
                                updateOrderJson();
                            });

                            grid.addEventListener('dragover', function (e) {
                                e.preventDefault();

                                if (!dragged) {
                                    return;
                                }

                                var target = e.target.closest('.device-photo-manager-card');

                                clearDropClasses();

                                if (!target || target === dragged) {
                                    return;
                                }

                                var box = target.getBoundingClientRect();
                                var isAfter = e.clientX > box.left + box.width / 2;

                                if (isAfter) {
                                    target.classList.add('drop-after');
                                } else {
                                    target.classList.add('drop-before');
                                }

                                e.dataTransfer.dropEffect = 'move';
                            });

                            grid.addEventListener('drop', function (e) {
                                e.preventDefault();

                                if (!dragged) {
                                    return;
                                }

                                var target = e.target.closest('.device-photo-manager-card');

                                if (!target || target === dragged) {
                                    clearDropClasses();
                                    updateOrderJson();
                                    return;
                                }

                                var box = target.getBoundingClientRect();
                                var isAfter = e.clientX > box.left + box.width / 2;

                                if (isAfter) {
                                    target.parentNode.insertBefore(dragged, target.nextSibling);
                                } else {
                                    target.parentNode.insertBefore(dragged, target);
                                }

                                clearDropClasses();
                                updateOrderJson();

                                /*
                                 * Auto-save order after drag and drop.
                                 */
                                setTimeout(function () {
                                    document.getElementById('device-photo-order-form').submit();
                                }, 150);
                            });

                            document.getElementById('device-photo-order-form').addEventListener('submit', function () {
                                updateOrderJson();
                            });

                            updateOrderJson();
                        })();
                    </script>
                    @endif
                @endif

                @if ($can_upload)
                    <hr>

                    <h4 id="device-photo-incoming-link" style="margin-bottom: 8px;">Add linked photo from another device</h4>

                    <div class="device-photo-drag-hint">
                        Search for a device that owns the photo, then link one of its photos to this device.
                    </div>

                    <form method="get" action="{{ url('plugin/DevicePhoto') }}" style="margin-bottom: 14px; position: relative;">
                        <input type="hidden" name="device_id" value="{{ $device->device_id }}">

                        <div class="input-group input-group-sm" style="max-width: 520px;">
                            <input
                                type="text"
                                name="owner_device_query"
                                class="form-control device-photo-target-input"
                                placeholder="Owner device ID or name"
                                value="{{ $incoming_owner_query ?? '' }}"
                                autocomplete="off"
                            >
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-default">
                                    <i class="fa fa-search"></i> Search photos
                                </button>
                            </span>
                        </div>
                    </form>

                    @if (!empty($incoming_owner_query) && empty($incoming_owner_device))
                        <div class="alert alert-warning" style="font-size: 12px;">
                            No unique owner device found. Try using the exact Device ID.
                        </div>
                    @endif

                    @if (!empty($incoming_owner_device))
                        <div class="alert alert-info" style="font-size: 12px; max-width: 720px;">
                            Showing photos from:
                            <a href="{{ url('device/' . $incoming_owner_device->device_id) }}">
                                <code>Device ID: {{ $incoming_owner_device->device_id }}</code>
                            </a>
                            -
                            {{ method_exists($incoming_owner_device, 'getAttribute') ? (($incoming_owner_device->sysName ?? $incoming_owner_device->display ?? $incoming_owner_device->hostname) ?? '') : '' }}
                        </div>

                        @if (empty($incoming_owner_photos) || count($incoming_owner_photos) < 1)
                            <div class="alert alert-info" style="font-size: 12px;">
                                This owner device has no photos.
                            </div>
                        @else
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 230px)); gap: 14px; margin-bottom: 18px;">
                                @foreach ($incoming_owner_photos as $ownerPhoto)
                                    <div style="background: #f3f3f3; border: 1px solid #ddd; border-radius: 8px; padding: 10px;">
                                        <img
                                            data-device-photo-preview-src="{{ $ownerPhoto['url'] }}"
                                            src="{{ $ownerPhoto['thumb_url'] ?? $ownerPhoto['url'] }}"
                                            style="width: 100%; max-height: 180px; object-fit: contain; background: #fff; border-radius: 5px; margin-bottom: 10px;"
                                        >

                                        <form method="post" action="{{ url('plugin/v1/DevicePhoto') }}">
                                            @csrf
                                            <input type="hidden" name="action" value="add_incoming_link">
                                            <input type="hidden" name="device_id" value="{{ $device->device_id }}">
                                            <input type="hidden" name="owner_device_id" value="{{ $incoming_owner_device->device_id }}">
                                            <input type="hidden" name="filename" value="{{ $ownerPhoto['filename'] }}">
                                            <input type="hidden" name="owner_device_query" value="{{ $incoming_owner_query ?? '' }}">

                                            <button type="submit" class="btn btn-default btn-sm btn-block">
                                                <i class="fa fa-link"></i> Link this photo
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                @endif

                @if (!empty($linked_photos) && count($linked_photos) > 0)
                    <hr>

                    <h4 style="margin-bottom: 8px;">Linked photos</h4>

                    <div class="device-photo-drag-hint">
                        These photos are owned by other devices, but are linked to this device.
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 230px)); gap: 14px;">
                        @foreach ($linked_photos as $photo)
                            <div style="background: #f3f3f3; border: 1px solid #ddd; border-radius: 8px; padding: 10px;">
                                <img
                                    data-device-photo-preview-src="{{ $photo['url'] }}"
                                    src="{{ $photo['thumb_url'] ?? $photo['url'] }}"
                                    style="width: 100%; max-height: 180px; object-fit: contain; background: #fff; border-radius: 5px; margin-bottom: 10px;"
                                >

                                <div class="alert alert-info" style="font-size: 12px; padding: 6px 8px; margin-bottom: 8px;">
                                    <strong>
                                        <i class="fa fa-link"></i>
                                        Linked from
                                    </strong>

                                    <div style="margin-top: 8px;">
                                        <div>
                                            <a href="{{ url('device/' . $photo['owner_device_id']) }}">
                                                <code>Device ID: {{ $photo['owner_device_id'] }}</code>
                                            </a>
                                        </div>

                                        @if (!empty($photo['owner_name']))
                                            <div style="margin-top: 2px; word-break: break-word;">
                                                <a href="{{ url('device/' . $photo['owner_device_id']) }}">
                                                    {{ $photo['owner_name'] }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <a href="{{ $photo['url'] }}" download="{{ $photo['filename'] }}" class="btn btn-default btn-sm btn-block" style="margin-bottom: 8px;">
                                    <i class="fa fa-download"></i> Download
                                </a>

                                @if ($can_delete)
                                <form method="post" action="{{ url('plugin/v1/DevicePhoto') }}" data-device-photo-confirm="Remove this linked photo from this device? The original photo will not be deleted.">
                                    @csrf
                                    <input type="hidden" name="action" value="remove_link">
                                    <input type="hidden" name="device_id" value="{{ $device->device_id }}">
                                    <input type="hidden" name="owner_device_id" value="{{ $photo['owner_device_id'] }}">
                                    <input type="hidden" name="filename" value="{{ $photo['filename'] }}">

                                    <button type="submit" class="btn btn-warning btn-sm btn-block">
                                        <i class="fa fa-unlink"></i> Remove link
                                    </button>
                                </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        @endif
    @endif
    @endif
    <hr>

    <div class="text-muted" style="font-size: 12px; margin: 18px 0 8px 0;">
        <i class="fa fa-camera"></i>
        Device Photo plugin for LibreNMS · Created by Tom Stian Bjerk · GitHub: coming soon
    </div>
</div>
