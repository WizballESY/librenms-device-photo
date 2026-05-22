<tr class="device-photo-overview-row device-photo-links-row"
    data-device-photo-links="{{ $row['device_id'] }}"
    style="display: none;"
    data-filter="{{ strtolower($row['device_id'] . ' ' . $row['name'] . ' ' . collect($row['linked_in'])->pluck('filename')->implode(' ') . ' ' . collect($row['linked_out'])->pluck('filename')->implode(' ')) }}">
    <td colspan="6" style="background: #fafafa;">
        @if (!empty($row['linked_out']))
            <div style="margin-bottom: 10px;">
                <strong>Photos shared to other devices</strong>

                <div style="margin-top: 5px;">
                    @foreach ($row['linked_out'] as $link)
                        <div style="margin: 4px 0 0 18px;">
                            <span class="label label-primary"
                                  style="margin-right: 5px;"
                                  title="This photo is owned by this device. It is shared to the device listed on this line.">
                                <i class="fa fa-home"></i> Owned
                            </span>

                            <code>{{ $link['filename'] }}</code>
                            shared to

                            <a href="{{ url('plugin/device-photo') }}?device_id={{ $link['target_device_id'] }}">
                                @if (!empty($link['target_name']))
                                    {{ $link['target_name'] }}
                                @else
                                    device-{{ $link['target_device_id'] }}
                                @endif
                                <span class="text-muted">(Device ID {{ $link['target_device_id'] }})</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if (!empty($row['linked_in']))
            <div>
                <strong>Photos linked from other devices</strong>

                <div style="margin-top: 5px;">
                    @foreach ($row['linked_in'] as $link)
                        <div style="margin: 4px 0 0 18px;">
                            <span class="label label-success"
                                  style="margin-right: 5px;"
                                  title="This photo is linked from another device. The device listed on this line owns the original photo.">
                                <i class="fa fa-link"></i> Linked
                            </span>

                            <code>{{ $link['filename'] }}</code>
                            from

                            <a href="{{ url('plugin/device-photo') }}?device_id={{ $link['owner_device_id'] }}">
                                @if (!empty($link['owner_name']))
                                    {{ $link['owner_name'] }}
                                @else
                                    device-{{ $link['owner_device_id'] }}
                                @endif
                                <span class="text-muted">(Device ID {{ $link['owner_device_id'] }})</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </td>
</tr>
