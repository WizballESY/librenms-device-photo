<?php

namespace WizballEsy\LibreNmsDevicePhoto\Hooks;

use App\Plugins\Hooks\MenuEntryHook;

class Menu extends MenuEntryHook
{
    public function authorize(\Illuminate\Contracts\Auth\Authenticatable $user, array $settings = []): bool
    {
        /*
         * Show the Device Photos menu entry to users with global read access.
         * Upload/delete/reorder permissions are still controlled inside the plugin.
         */
        return $user->can('global-read');
    }

    public function data(array $settings = []): array
    {
        return [];
    }
}
