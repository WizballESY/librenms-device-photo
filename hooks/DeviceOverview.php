<?php

namespace WizballEsy\LibreNmsDevicePhoto\Hooks;

use App\Plugins\Hooks\DeviceOverviewHook;

class DeviceOverview extends DeviceOverviewHook
{
    public function authorize(\Illuminate\Contracts\Auth\Authenticatable $user, \App\Models\Device $device): bool
    {
        return $user->can('global-read');
    }

    private function allowedRoles(array $settings, string $key): array
    {
        $roles = $settings[$key] ?? ['admin'];

        if (! is_array($roles)) {
            $roles = ['admin'];
        }

        $roles = array_values(array_filter($roles, function ($role) {
            return is_string($role) && trim($role) !== '';
        }));

        return empty($roles) ? ['admin'] : $roles;
    }

    private function userCanAction(?\Illuminate\Contracts\Auth\Authenticatable $user, array $settings, string $key): bool
    {
        if (! $user) {
            return false;
        }

        if ($user->can('admin')) {
            return true;
        }

        $allowedRoles = $this->allowedRoles($settings, $key);

        if (method_exists($user, 'getRoleNames')) {
            return $user->getRoleNames()->intersect($allowedRoles)->isNotEmpty();
        }

        if (method_exists($user, 'hasRole')) {
            foreach ($allowedRoles as $role) {
                if ($user->hasRole($role)) {
                    return true;
                }
            }
        }

        return false;
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
