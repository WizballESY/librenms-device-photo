<?php

namespace WizballEsy\LibreNmsDevicePhoto\Services;

use Illuminate\Contracts\Auth\Authenticatable;

class PhotoPermissionService
{
    public function allowedRoles(array $settings, string $key): array
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

    public function userCanAction(?Authenticatable $user, array $settings, string $key): bool
    {
        if (! $user) {
            return false;
        }

        if ($user->can('admin')) {
            return true;
        }

        $allowedRoles = $this->allowedRoles($settings, $key);

        if (method_exists($user, 'getRoleNames')) {
            $roleNames = $user->getRoleNames();

            if ($roleNames instanceof \Illuminate\Support\Collection) {
                if ($roleNames->intersect($allowedRoles)->isNotEmpty()) {
                    return true;
                }
            } elseif (is_array($roleNames) && array_intersect($roleNames, $allowedRoles)) {
                return true;
            }
        }

        if (method_exists($user, 'hasRole')) {
            foreach ($allowedRoles as $role) {
                if ($user->hasRole($role)) {
                    return true;
                }
            }
        }

        foreach ($allowedRoles as $role) {
            if ($user->can($role)) {
                return true;
            }
        }

        return false;
    }
}