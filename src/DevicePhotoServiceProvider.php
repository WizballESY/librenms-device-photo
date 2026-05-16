<?php

namespace WizballEsy\LibreNmsDevicePhoto;

use Illuminate\Support\ServiceProvider;
use LibreNMS\Interfaces\Plugins\PluginManagerInterface;
use App\Plugins\Hooks\DeviceOverviewHook;
use App\Plugins\Hooks\MenuEntryHook;
use App\Plugins\Hooks\PageHook;
use App\Plugins\Hooks\SettingsHook;
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

        $pluginManager = $this->app->make(PluginManagerInterface::class);

        $pluginManager->publishHook($pluginName, DeviceOverviewHook::class, DeviceOverview::class);
        $pluginManager->publishHook($pluginName, MenuEntryHook::class, Menu::class);
        $pluginManager->publishHook($pluginName, PageHook::class, Page::class);
        $pluginManager->publishHook($pluginName, SettingsHook::class, Settings::class);
    }
}