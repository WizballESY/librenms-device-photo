<div style="margin: 15px;">
    <h4>{{ $plugin_name }} Settings</h4>

    <p class="text-muted">
        Select which LibreNMS roles are allowed to manage device photos.
        Administrators are always allowed.
    </p>

    <div class="alert alert-warning" style="font-size: 12px; max-width: 720px;">
        <strong>Notice:</strong>
        This plugin was created with assistance from AI. Use at your own risk.
        Make sure you have tested it before using it in production.
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

    @include('DevicePhoto::resources.views.partials.footer')
</div>
