<?php

namespace WizballEsy\LibreNmsDevicePhoto\Services;

use App\Models\Plugin;

class PhotoSettingsService
{
    /**
     * Package plugin name.
     */
    public const PACKAGE_PLUGIN_NAME = 'device-photo';

    /**
     * Legacy local plugin name.
     */
    public const LEGACY_PLUGIN_NAME = 'DevicePhoto';

    public function settings(): array
    {
        $plugin = Plugin::where('plugin_name', self::PACKAGE_PLUGIN_NAME)->first();

        /*
         * During migration, fall back to the legacy local plugin name.
         * This helps preserve settings when moving from local install to package install.
         */
        if (! $plugin) {
            $plugin = Plugin::where('plugin_name', self::LEGACY_PLUGIN_NAME)->first();
        }

        $settings = $plugin ? ($plugin->settings ?? []) : [];

        return is_array($settings) ? $settings : [];
    }

    public function pluginNames(): array
    {
        return [
            self::PACKAGE_PLUGIN_NAME,
            self::LEGACY_PLUGIN_NAME,
        ];
    }
}