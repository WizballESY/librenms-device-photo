<?php

namespace App\Plugins\DevicePhoto;

use App\Plugins\Hooks\SettingsHook;
use Illuminate\Support\Facades\DB;

class Settings extends SettingsHook
{
    public function authorize(\Illuminate\Contracts\Auth\Authenticatable $user): bool
    {
        return $user->can('admin');
    }

    public function data(array $settings = []): array
    {
        $roles = DB::table('roles')
            ->select('name')
            ->orderBy('name')
            ->pluck('name')
            ->toArray();

        foreach (['upload_roles', 'delete_roles', 'reorder_roles'] as $key) {
            if (empty($settings[$key]) || ! is_array($settings[$key])) {
                $settings[$key] = ['admin'];
            }

            $settings[$key] = array_values(array_filter($settings[$key], function ($role) {
                return is_string($role) && trim($role) !== '';
            }));
        }

        return [
            'settings' => $settings,
            'roles' => $roles,
        ];
    }
}
