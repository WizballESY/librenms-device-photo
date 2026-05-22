<tr data-device-photo-ajax-row="broken-link">
    <td>
        <a href="{{ url('plugin/device-photo') }}?device_id={{ $link['target_device_id'] }}">
            <code>Device ID: {{ $link['target_device_id'] }}</code>
        </a>
        @if (!empty($link['target_name']))
            <br>
            <a href="{{ url('plugin/device-photo') }}?device_id={{ $link['target_device_id'] }}">
                {{ $link['target_name'] }}
            </a>
        @endif
    </td>

    <td>
        @if (!empty($link['owner_name']))
            <a href="{{ url('plugin/device-photo') }}?device_id={{ $link['owner_device_id'] }}">
                <code>Device ID: {{ $link['owner_device_id'] }}</code>
            </a>
            <br>
            <a href="{{ url('plugin/device-photo') }}?device_id={{ $link['owner_device_id'] }}">
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
            <form method="post" action="{{ url('plugin/device-photo-package/action') }}"
                data-device-photo-ajax="1"
                data-device-photo-ajax-success="Broken link removed."
                data-device-photo-confirm-title="Remove broken link?"
                data-device-photo-confirm-ok-text="Remove link"
                data-device-photo-confirm-ok-class="btn-warning"
                data-device-photo-confirm-ok-icon="fa-unlink"
                data-device-photo-confirm="Remove this broken photo link? The original photo file is already missing.">
                @csrf
                <input type="hidden" name="action" value="remove_broken_link">
                <input type="hidden" name="return_to" value="overview">
                <input type="hidden" name="return_anchor" value="device-photo-broken-links">
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
