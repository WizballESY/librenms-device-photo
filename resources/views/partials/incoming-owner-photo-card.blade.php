<div class="device-photo-incoming-owner-card"
     data-device-photo-incoming-owner-id="{{ $incoming_owner_device->device_id }}"
     data-device-photo-incoming-filename="{{ $ownerPhoto['filename'] }}"
     data-device-photo-owner-query="{{ $incoming_owner_query ?? '' }}"
     style="background: #f3f3f3; border: 1px solid #ddd; border-radius: 8px; padding: 10px;">
    <img
        data-device-photo-gallery="owner-device-{{ $incoming_owner_device->device_id }}"
        data-device-photo-preview-src="{{ $ownerPhoto['url'] }}"
        data-device-photo-taken="{{ $ownerPhoto['photo_taken_iso'] ?? '' }}"
        data-device-photo-file-date="{{ $ownerPhoto['file_date_iso'] ?? '' }}"
        src="{{ $ownerPhoto['thumb_url'] ?? $ownerPhoto['url'] }}"
        style="width: 100%; max-height: 180px; object-fit: contain; background: #fff; border-radius: 5px; margin-bottom: 10px;"
    >

    @if ($devicePhotoIncomingAlreadyLinked)
        <button type="button" class="btn btn-success btn-sm btn-block" disabled>
            <i class="fa fa-check"></i> Already linked
        </button>
    @else
        <form method="post"
              action="{{ url('plugin/device-photo-package/action') }}"
              data-device-photo-ajax-add-incoming-link="1"
              data-device-photo-ajax-success="Photo linked.">
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
    @endif
</div>
