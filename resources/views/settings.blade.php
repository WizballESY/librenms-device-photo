@include('device-photo::partials.styles')

<div class="device-photo-plugin" style="margin: 15px;">
    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 14px;">
        <h2 style="margin: 0;">
            Device Photos Settings
            @include('device-photo::partials.version-badge')
        </h2>

        <a href="{{ url('plugin/device-photo') }}"
           class="btn btn-primary btn-sm"
           title="Device Photos overview">
            <i class="fa fa-arrow-left"></i> Device Photos Overview
        </a>
    </div>

    <p class="text-muted">
        Select which LibreNMS roles are allowed to manage device photos.
        Administrators are always allowed.
    </p>

    <div class="alert alert-warning device-photo-alpha-notice" style="font-size: 12px; max-width: 720px;">
        <strong>Notice:</strong>
        This plugin is currently an alpha release. Use at your own risk.
        Make sure you have tested it before using it in production.<br>
        Feedback and bug reports are welcome on
        <a href="https://github.com/WizballESY/librenms-device-photo/issues" target="_blank" rel="noopener noreferrer">GitHub</a>.
    </div>

    <form method="post" style="margin-top: 15px;">
        @csrf

        @php
            $groups = [
                'upload_roles' => [
                    'title' => 'Upload photos',
                    'description' => 'Roles allowed to upload new device photos.',
                ],
                'delete_roles' => [
                    'title' => 'Delete photos',
                    'description' => 'Roles allowed to delete photos. Deleted photos are moved to the deleted folder.',
                ],
                'reorder_roles' => [
                    'title' => 'Reorder photos',
                    'description' => 'Roles allowed to change photo order using drag and drop.',
                ],
            ];
        @endphp

        @foreach ($groups as $settingName => $group)
            <div class="panel panel-default" style="max-width: 720px;">
                <div class="panel-heading">
                    <strong>{{ $group['title'] }}</strong>
                </div>

                <div class="panel-body">
                    <p class="text-muted">{{ $group['description'] }}</p>

                    <input type="hidden" name="settings[{{ $settingName }}][]" value="">

                    @foreach ($roles as $role)
                        <label style="display: block; font-weight: normal;">
                            <input
                                type="checkbox"
                                name="settings[{{ $settingName }}][]"
                                value="{{ $role }}"
                                @if (in_array($role, $settings[$settingName] ?? ['admin'], true)) checked @endif
                            >
                            {{ $role }}
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach

        <button type="submit" class="btn btn-primary">
            <i class="fa fa-save"></i> Save settings
        </button>
    </form>
    <hr>

    @include('device-photo::partials.footer')
</div>
