<?php

namespace WizballEsy\LibreNmsDevicePhoto;

use Illuminate\Support\ServiceProvider;
use LibreNMS\Interfaces\Plugins\PluginManagerInterface;
use LibreNMS\Interfaces\Plugins\Hooks\DeviceOverviewHook as DeviceOverviewHookInterface;
use LibreNMS\Interfaces\Plugins\Hooks\MenuEntryHook as MenuEntryHookInterface;
use LibreNMS\Interfaces\Plugins\Hooks\SettingsHook as SettingsHookInterface;
use LibreNMS\Interfaces\Plugins\Hooks\SinglePageHook;
use WizballEsy\LibreNmsDevicePhoto\Hooks\DeviceOverview;
use WizballEsy\LibreNmsDevicePhoto\Hooks\Menu;
use WizballEsy\LibreNmsDevicePhoto\Hooks\Page;
use WizballEsy\LibreNmsDevicePhoto\Hooks\Settings;

class DevicePhotoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/device-photo.php', 'device-photo');
    }

    public function boot(): void
    {
        $pluginName = 'device-photo';

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'device-photo');

        /*
         * Compatibility view path.
         *
         * LibreNMS local plugins commonly reference views like:
         * device-photo::resources.views.page
         *
         * Package views can also be referenced as:
         * device-photo::page
         */
        $this->loadViewsFrom(__DIR__ . '/..', 'device-photo');

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $pluginManager = $this->app->make(PluginManagerInterface::class);

        $pluginManager->publishHook($pluginName, DeviceOverviewHookInterface::class, DeviceOverview::class);
        $pluginManager->publishHook($pluginName, MenuEntryHookInterface::class, Menu::class);
        $pluginManager->publishHook($pluginName, SinglePageHook::class, Page::class);
        $pluginManager->publishHook($pluginName, SettingsHookInterface::class, Settings::class);
    }
}