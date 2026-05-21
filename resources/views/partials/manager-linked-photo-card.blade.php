@php
    $devicePhotoLinkedCardAnchor = 'device-photo-card-' . preg_replace('/[^A-Za-z0-9_-]/', '-', (string) ($photo['order_key'] ?? ('linked:' . ($photo['owner_device_id'] ?? 0) . ':' . $photo['filename'])));
@endphp

<div id="{{ $devicePhotoLinkedCardAnchor }}" class="device-photo-manager-card device-photo-linked-photo-card" draggable="{{ $can_reorder ? 'true' : 'false' }}" data-filename="{{ $photo['filename'] }}" data-order-key="{{ $photo['order_key'] ?? ('linked:' . ($photo['owner_device_id'] ?? 0) . ':' . $photo['filename']) }}" style="order: {{ $photo['display_order_index'] ?? 9999 }}; background: #f3f3f3; border: 1px solid #ddd; border-radius: 8px; padding: 10px;">
    <img
        data-device-photo-gallery="device-{{ $device->device_id }}" data-device-photo-preview-src="{{ $photo['url'] }}"
        data-device-photo-taken="{{ $photo['photo_taken_iso'] ?? '' }}"
        data-device-photo-file-date="{{ $photo['file_date_iso'] ?? '' }}"
        src="{{ $photo['thumb_url'] ?? $photo['url'] }}"
        style="width: 100%; max-height: 180px; object-fit: contain; background: #fff; border-radius: 5px; margin-bottom: 10px;"
    >

    <div style="margin: 6px 0 4px 0;">
        <span class="label label-success"
              title="This photo is linked from another device. The owner device listed below has the original photo.">
            <i class="fa fa-link"></i> Linked
        </span>
    </div>

    <div class="text-muted" style="font-size: 12px; margin: 6px 0 8px 0; line-height: 1.35; word-break: break-all;">
        @if (!empty($photo['photo_taken_display']))
            <div>
                <strong>Photo taken:</strong>
                <span class="device-photo-local-date" data-device-photo-format="date" data-device-photo-date="{{ $photo['photo_taken_iso'] ?? '' }}">{{ $photo['photo_taken_display'] }}</span>
            </div>
        @endif

        @if (!empty($photo['file_date_display']))
            <div title="File timestamp on the LibreNMS server. This may change if files are copied, restored or modified.">
                <strong>File date:</strong>
                <span class="device-photo-local-date" data-device-photo-format="date" data-device-photo-date="{{ $photo['file_date_iso'] ?? '' }}">{{ $photo['file_date_display'] }}</span>
            </div>
        @endif

        <div title="Stored filename on the owner device.">
            <strong>Filename:</strong>
            <span>{{ $photo['filename'] }}</span>
        </div>
    </div>

    <div class="alert alert-info device-photo-linked-owner-box" style="font-size: 12px; padding: 6px 8px; margin-bottom: 8px;">
        <strong>
            <i class="fa fa-link"></i> Linked from
        </strong>

        <div style="margin-top: 8px;">
            <a href="{{ url('plugin/device-photo') }}?device_id={{ $photo['owner_device_id'] }}">
                @if (!empty($photo['owner_name']))
                    {{ $photo['owner_name'] }}
                @else
                    device-{{ $photo['owner_device_id'] }}
                @endif
                <span class="text-muted">(Device ID {{ $photo['owner_device_id'] }})</span>
            </a>
        </div>
    </div>

    <a href="{{ $photo['url'] }}" download="{{ $photo['filename'] }}" class="btn btn-default btn-sm btn-block" style="margin-bottom: 8px;">
        <i class="fa fa-download"></i> Download
    </a>

    <a href="{{ url('plugin/device-photo') }}?device_id={{ $photo['owner_device_id'] }}" class="btn btn-default btn-sm btn-block" style="margin-bottom: 8px;">
        <i class="fa fa-camera"></i> Manage owner photos
    </a>

    @if ($can_delete)
    <form method="post" action="{{ url('plugin/device-photo-package/action') }}"
          data-device-photo-ajax="1"
          data-device-photo-ajax-success="Linked photo removed."
          data-device-photo-ajax-remove-card="1"
          data-device-photo-confirm-title="Remove linked photo?"
          data-device-photo-confirm-ok-text="Remove link"
          data-device-photo-confirm-ok-class="btn-warning"
          data-device-photo-confirm-ok-icon="fa-unlink"
          data-device-photo-confirm="Remove this linked photo from this device? The original photo will not be deleted.">
        @csrf
        <input type="hidden" name="action" value="remove_link">
        <input type="hidden" name="device_id" value="{{ $device->device_id }}">
        <input type="hidden" name="owner_device_id" value="{{ $photo['owner_device_id'] }}">
        <input type="hidden" name="filename" value="{{ $photo['filename'] }}">
        <input type="hidden" name="return_anchor" value="device-photo-manager-grid">

        <button type="submit" class="btn btn-warning btn-sm btn-block">
            <i class="fa fa-unlink"></i> Remove link
        </button>
    </form>
    @endif
</div>
