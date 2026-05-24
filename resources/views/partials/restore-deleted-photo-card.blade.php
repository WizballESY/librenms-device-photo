@php
    $devicePhotoDeletedFormatDate = function ($iso, $display) {
        $value = trim((string) ($iso ?: $display));

        if ($value === '') {
            return '';
        }

        $timestamp = strtotime($value);

        if ($timestamp === false) {
            return substr(trim((string) $display), 0, 10);
        }

        return date('d.m.Y', $timestamp);
    };

    $devicePhotoDeletedPhotoTakenDate = $devicePhotoDeletedFormatDate(
        $photo['photo_taken_iso'] ?? '',
        $photo['photo_taken_display'] ?? ''
    );

    $devicePhotoDeletedFileDate = $devicePhotoDeletedFormatDate(
        $photo['file_date_iso'] ?? '',
        $photo['file_date_display'] ?? ''
    );

    $devicePhotoDeletedAt = trim((string) ($photo['deleted_at_display'] ?? ''));

    if ($devicePhotoDeletedAt !== '') {
        $devicePhotoDeletedAtTimestamp = strtotime($devicePhotoDeletedAt);

        if ($devicePhotoDeletedAtTimestamp !== false) {
            $devicePhotoDeletedAt = date('d.m.Y H:i', $devicePhotoDeletedAtTimestamp);
        }
    }
@endphp

<div class="device-photo-orphan-card"
     data-device-photo-ajax-row="deleted-photo"
     style="background: #f8f8f8; border: 1px solid #ddd; border-radius: 8px; padding: 10px;">
    <img data-device-photo-gallery="restore-deleted"
         data-device-photo-preview-src="{{ $photo['url'] }}"
         data-device-photo-taken="{{ $photo['photo_taken_iso'] ?? '' }}"
         data-device-photo-file-date="{{ $photo['file_date_iso'] ?? '' }}"
         src="{{ $photo['thumb_url'] ?? $photo['url'] }}"
         style="width: 100%; max-height: 160px; object-fit: contain; background: #fff; border-radius: 5px; margin-bottom: 8px;">

    <div class="text-muted device-photo-card-meta" style="font-size: 12px;">
        @if ($devicePhotoDeletedAt !== '')
            <div title="Stored deleted filename: {{ $photo['filename'] }}">
                <strong>Deleted:</strong>
                <span>{{ $devicePhotoDeletedAt }}</span>
            </div>
        @endif

        @if ($devicePhotoDeletedPhotoTakenDate !== '')
            <div>
                <strong>Photo taken:</strong>
                <span>{{ $devicePhotoDeletedPhotoTakenDate }}</span>
            </div>
        @endif

        @if ($devicePhotoDeletedFileDate !== '')
            <div title="File timestamp on the LibreNMS server. This may change if files are copied, restored or modified.">
                <strong>File date:</strong>
                <span>{{ $devicePhotoDeletedFileDate }}</span>
            </div>
        @endif

        <div title="Original filename before it was moved to deleted.">
            <strong>Original:</strong>
            <span>{{ $photo['original_filename'] }}</span>
        </div>

        <div>
            <strong>Size:</strong>
            <span>{{ round(($photo['size'] ?? 0) / 1024, 1) }} KB</span>
        </div>
    </div>

    <a href="{{ $photo['url'] }}"
       download="{{ $photo['filename'] }}"
       class="btn btn-default btn-sm btn-block device-photo-card-action"
       style="margin-top: 8px;">
        <i class="fa fa-download"></i> Download
    </a>

    <form method="post"
          action="{{ url('plugin/device-photo-package/action') }}"
          style="margin-top: 8px; position: relative;"
          data-device-photo-ajax="1"
          data-device-photo-ajax-success="Photo restored."
          data-device-photo-confirm-title="Restore photo?"
          data-device-photo-confirm-ok-text="Restore"
          data-device-photo-confirm-ok-class="btn-primary"
          data-device-photo-confirm-ok-icon="fa-undo"
          data-device-photo-confirm="Restore this deleted photo to the selected device? The file will be renamed to match the target device.">
        @csrf
        <input type="hidden" name="action" value="restore_deleted_photo">
        <input type="hidden" name="device_id" value="0">
        <input type="hidden" name="filename" value="{{ $photo['filename'] }}">

        <div class="input-group input-group-sm">
            <input
                type="text"
                name="target_device_query"
                class="form-control device-photo-orphan-target-input device-photo-restore-target-input"
                placeholder="Search Device ID or name"
                autocomplete="off"
                required
            >
            <span class="input-group-btn">
                <button type="submit" class="btn btn-default device-photo-card-action">
                    <i class="fa fa-undo"></i> Restore
                </button>
            </span>
        </div>
    </form>

    @if ($can_delete)
        <form method="post"
              action="{{ url('plugin/device-photo-package/action') }}"
              style="margin-top: 8px;"
              data-device-photo-ajax="1"
              data-device-photo-ajax-success="Deleted photo was permanently removed."
              data-device-photo-confirm-title="Permanently delete photo?"
              data-device-photo-confirm-ok-text="Permanently delete"
              data-device-photo-confirm-ok-class="btn-danger"
              data-device-photo-confirm-ok-icon="fa-trash"
              data-device-photo-confirm="Permanently delete this photo from the deleted folder? This cannot be undone.">
            @csrf
            <input type="hidden" name="action" value="delete_deleted_photo">
            <input type="hidden" name="device_id" value="0">
            <input type="hidden" name="filename" value="{{ $photo['filename'] }}">

            <button type="submit" class="btn btn-danger btn-sm btn-block">
                <i class="fa fa-trash"></i> Permanently delete
            </button>
        </form>
    @endif
</div>
