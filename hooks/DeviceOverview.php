<?php

namespace WizballEsy\LibreNmsDevicePhoto\Hooks;

use App\Plugins\Hooks\DeviceOverviewHook;
use WizballEsy\LibreNmsDevicePhoto\Services\PhotoPermissionService;

class DeviceOverview extends DeviceOverviewHook
{
    public function authorize(\Illuminate\Contracts\Auth\Authenticatable $user, \App\Models\Device $device): bool
    {
        return $user->can('global-read');
    }

    private function userCanAction(?\Illuminate\Contracts\Auth\Authenticatable $user, array $settings, string $key): bool
    {
        return app(PhotoPermissionService::class)->userCanAction($user, $settings, $key);
    }

    public function data(\App\Models\Device $device, array $settings = []): array
    {
        $user = auth()->user();

        $canUpload = $this->userCanAction($user, $settings, 'upload_roles');
        $canDelete = $this->userCanAction($user, $settings, 'delete_roles');
        $canReorder = $this->userCanAction($user, $settings, 'reorder_roles');

        return [
            'title' => 'Device Photos',
            'device' => $device,
            'can_manage_photos' => $canUpload || $canDelete || $canReorder,
        ];
    }
}
