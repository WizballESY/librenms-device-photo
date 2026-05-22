<div class="device-photo-orphan-card"
     style="background: #f8f8f8; border: 1px solid #ddd; border-radius: 8px; padding: 10px;">
    <img data-device-photo-gallery="overview-orphans"
         data-device-photo-preview-src="{{ $photo['url'] }}"
         data-device-photo-taken="{{ $photo['photo_taken_iso'] ?? '' }}"
         data-device-photo-file-date="{{ $photo['file_date_iso'] ?? '' }}"
         src="{{ $photo['thumb_url'] ?? $photo['url'] }}"
         style="width: 100%; max-height: 160px; object-fit: contain; background: #fff; border-radius: 5px; margin-bottom: 8px;">

    <div style="font-size: 12px;">
        <strong>{{ $photo['filename'] }}</strong><br>
        <span class="text-muted">Missing Device ID: {{ $photo['device_id'] }}</span>
    </div>

    <a href="{{ $photo['url'] }}"
       download="{{ $photo['filename'] }}"
       class="btn btn-default btn-xs btn-block"
       style="margin-top: 8px;">
        <i class="fa fa-download"></i> Download
    </a>

    @if ($can_upload)
        <form method="post"
              action="{{ url('plugin/device-photo-package/action') }}"
              style="margin-top: 8px; position: relative;"
              data-device-photo-ajax="1"
              data-device-photo-ajax-success="Orphaned photo assigned."
              data-device-photo-ajax-remove-card="orphaned-photo"
              data-device-photo-confirm-title="Assign orphaned photo?"
              data-device-photo-confirm-ok-text="Assign"
              data-device-photo-confirm-ok-class="btn-primary"
              data-device-photo-confirm-ok-icon="fa-check"
              data-device-photo-confirm="Assign this orphaned photo to the selected device? The file will be renamed.">
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
        <form method="post"
              action="{{ url('plugin/device-photo-package/action') }}"
              style="margin-top: 8px;"
              data-device-photo-ajax="1"
              data-device-photo-ajax-success="Orphaned photo moved to deleted photos."
              data-device-photo-ajax-remove-card="orphaned-photo"
              data-device-photo-confirm-title="Delete orphaned photo?"
              data-device-photo-confirm-ok-text="Delete"
              data-device-photo-confirm-ok-class="btn-danger"
              data-device-photo-confirm-ok-icon="fa-trash"
              data-device-photo-confirm="Delete this orphaned photo? It will be moved to the deleted folder and can be restored manually.">
            @csrf
            <input type="hidden" name="action" value="delete_orphan_photo">
            <input type="hidden" name="device_id" value="0">
            <input type="hidden" name="return_to" value="overview">
            <input type="hidden" name="return_anchor" value="device-photo-orphaned-photos">
            <input type="hidden" name="filename" value="{{ $photo['filename'] }}">

            <button type="submit" class="btn btn-danger btn-xs btn-block">
                <i class="fa fa-trash"></i> Delete
            </button>
        </form>
    @endif
</div>
