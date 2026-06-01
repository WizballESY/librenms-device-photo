@php
    $devicePhotoCardAnchor = 'device-photo-card-' . preg_replace('/[^A-Za-z0-9_-]/', '-', (string) ($photo['order_key'] ?? $photo['filename']));
@endphp

<div id="{{ $devicePhotoCardAnchor }}"
     class="device-photo-manager-card device-photo-owned-photo-card"
     draggable="{{ $can_reorder ? 'true' : 'false' }}"
     data-filename="{{ $photo['filename'] }}"
     data-order-key="{{ $photo['order_key'] ?? $photo['filename'] }}"
     style="order: {{ $photo['display_order_index'] ?? 0 }};">
    <img
        class="device-photo-card-image"
        data-device-photo-gallery="device-{{ $device->device_id }}"
        data-device-photo-preview-src="{{ $photo['url'] }}"
        data-device-photo-taken="{{ $photo['photo_taken_iso'] ?? '' }}"
        data-device-photo-file-date="{{ $photo['file_date_iso'] ?? '' }}"
        src="{{ $photo['thumb_url'] ?? $photo['url'] }}"
        draggable="false"
    >

    <div class="device-photo-card-type-row">
        <span class="label label-primary"
              title="This photo is owned by this device. It can also be shared to other devices.">
            <i class="fa fa-home"></i> Owned
        </span>
    </div>

    <div class="text-muted device-photo-card-meta" data-device-photo-meta>
        @if (!empty($photo['photo_taken_display']))
            <div data-device-photo-taken-row>
                <strong>Photo taken:</strong>
                <span class="device-photo-local-date"
                      data-device-photo-taken-display
                      data-device-photo-format="date"
                      data-device-photo-date="{{ $photo['photo_taken_iso'] ?? '' }}">{{ $photo['photo_taken_display'] }}</span>
            </div>
        @endif

        @if (!empty($photo['file_date_display']))
            <div title="File timestamp on the LibreNMS server. This may change if files are copied, restored or modified.">
                <strong>File date:</strong>
                <span class="device-photo-local-date"
                      data-device-photo-format="date"
                      data-device-photo-date="{{ $photo['file_date_iso'] ?? '' }}">{{ $photo['file_date_display'] }}</span>
            </div>
        @endif

        <div title="Stored filename on the LibreNMS server.">
            <strong>Filename:</strong>
            <span>{{ $photo['filename'] }}</span>
        </div>
    </div>

    @if (!empty($photo['linked_to']))
        @php
            $linkedToCollapseId = $devicePhotoCardAnchor . '-linked-to';
        @endphp

        <div class="alert alert-warning device-photo-card-link-box"
             data-device-photo-linked-to-box>
            <div class="device-photo-linked-to-summary">
                <strong>
                    <i class="fa fa-link"></i>
                    Linked to <span data-device-photo-linked-to-count>{{ count($photo['linked_to']) }}</span>
                    <span data-device-photo-linked-to-label>device{{ count($photo['linked_to']) === 1 ? '' : 's' }}</span>
                </strong>

                <button type="button"
                        class="btn btn-default btn-xs device-photo-linked-to-toggle"
                        data-toggle="collapse"
                        data-target="#{{ $linkedToCollapseId }}"
                        aria-expanded="false"
                        aria-controls="{{ $linkedToCollapseId }}">
                    <i class="fa fa-list"></i> Show
                </button>
            </div>

            <div id="{{ $linkedToCollapseId }}" class="collapse device-photo-linked-to-list" data-device-photo-linked-to-list>
                @foreach ($photo['linked_to'] as $linkedDevice)
                    <div data-device-photo-ajax-row="outgoing-link"
                         class="device-photo-linked-to-row">
                        <div class="device-photo-linked-to-device-name">
                            <a href="{{ url('plugin/device-photo') }}?device_id={{ $linkedDevice['device_id'] }}">
                                @if (!empty($linkedDevice['name']))
                                    {{ $linkedDevice['name'] }}
                                @else
                                    device-{{ $linkedDevice['device_id'] }}
                                @endif
                                <span class="text-muted">(Device ID {{ $linkedDevice['device_id'] }})</span>
                            </a>
                        </div>

                        @if ($can_delete)
                            <form method="post"
                                  action="{{ url('plugin/device-photo-package/action') }}"
                                  class="device-photo-linked-to-remove-form"
                                  data-device-photo-ajax="1"
                                  data-device-photo-ajax-success="Shared photo link removed."
                                  data-device-photo-confirm-title="Remove shared link?"
                                  data-device-photo-confirm-ok-text="Remove link"
                                  data-device-photo-confirm-ok-class="btn-warning"
                                  data-device-photo-confirm-ok-icon="fa-unlink"
                                  data-device-photo-confirm="Remove this shared photo link? The original photo will not be deleted.">
                                @csrf
                                <input type="hidden" name="action" value="remove_outgoing_link">
                                <input type="hidden" name="device_id" value="{{ $device->device_id }}">
                                <input type="hidden" name="target_device_id" value="{{ $linkedDevice['device_id'] }}">
                                <input type="hidden" name="filename" value="{{ $photo['filename'] }}">
                                <input type="hidden" name="return_anchor" value="{{ $devicePhotoCardAnchor }}">

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

    <a href="{{ $photo['url'] }}" download="{{ $photo['filename'] }}" class="btn btn-default btn-sm btn-block device-photo-card-action">
        <i class="fa fa-download"></i> Download
    </a>

    @php
        $devicePhotoCanWriteExif = !empty($exiftool_available) && preg_match('/\\.(jpe?g)$/i', $photo['filename']);
    @endphp

    @if ($can_upload && $devicePhotoCanWriteExif)
        <button
            type="button"
            class="btn btn-default btn-sm btn-block device-photo-set-taken-button device-photo-card-action"
            data-filename="{{ $photo['filename'] }}"
            data-photo-taken="{{ !empty($photo['photo_taken_iso']) ? substr($photo['photo_taken_iso'], 0, 16) : '' }}"
            data-device-id="{{ $device->device_id }}"
            data-return-anchor="{{ $devicePhotoCardAnchor }}"
            title="Write Photo taken to EXIF metadata"
        >
            <i class="fa fa-clock-o"></i> Set photo taken
        </button>
    @elseif ($can_upload && empty($exiftool_available) && preg_match('/\\.(jpe?g)$/i', $photo['filename']))
        <div class="text-muted" style="font-size: 12px; margin-bottom: 8px;">
            <i class="fa fa-clock-o"></i> Photo taken editing requires ExifTool.
        </div>
    @endif

    @if ($can_upload)
        <button
            type="button"
            class="btn btn-default btn-sm btn-block device-photo-owner-change-button device-photo-card-action"
            data-filename="{{ $photo['filename'] }}"
            data-device-id="{{ $device->device_id }}"
            data-return-anchor="{{ $devicePhotoCardAnchor }}"
            title="Move this owned photo to another device"
        >
            <i class="fa fa-exchange"></i> Move to another device
        </button>

        <div class="text-muted" style="font-size: 12px; margin-bottom: 4px;">
            <i class="fa fa-link"></i> Link this photo to another device
        </div>

        <form method="post"
              action="{{ url('plugin/device-photo-package/action') }}"
              style="margin-bottom: 8px; position: relative;"
              data-device-photo-ajax-add-link="1"
              data-device-photo-ajax-success="Photo linked.">
            @csrf
            <input type="hidden" name="action" value="add_link">
            <input type="hidden" name="device_id" value="{{ $device->device_id }}">
            <input type="hidden" name="filename" value="{{ $photo['filename'] }}">
            <input type="hidden" name="return_anchor" value="{{ $devicePhotoCardAnchor }}">

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

        <form method="post" action="{{ url('plugin/device-photo-package/action') }}" class="device-photo-delete-form"
              data-device-photo-ajax="1"
              data-device-photo-ajax-success="Photo moved to deleted folder."
              data-device-photo-ajax-remove-card="1"
              data-device-photo-confirm-title="Delete photo?"
              data-device-photo-confirm-ok-text="Delete"
              data-device-photo-confirm-ok-class="btn-danger"
              data-device-photo-confirm-ok-icon="fa-trash"
              data-device-photo-confirm="{{ $deleteConfirm }}">
            @csrf
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="device_id" value="{{ $device->device_id }}">
            <input type="hidden" name="filename" value="{{ $photo['filename'] }}">
            <input type="hidden" name="return_anchor" value="device-photo-manager-grid">

            <button type="submit" class="btn btn-danger btn-sm btn-block">
                <i class="fa fa-trash"></i> Delete
            </button>
        </form>
    @endif
</div>
