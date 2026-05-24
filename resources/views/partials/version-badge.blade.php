@php
    $packageName = 'wizballesy/librenms-device-photo';
    $version = null;

    try {
        if (class_exists(\Composer\InstalledVersions::class)) {
            $version = \Composer\InstalledVersions::getPrettyVersion($packageName);
        }
    } catch (\Throwable $e) {
        $version = null;
    }

    $version = is_string($version) && trim($version) !== '' ? trim($version) : 'dev';

    $label = str_contains(strtolower($version), 'alpha')
        ? 'Alpha ' . $version
        : $version;

    $releaseUrl = 'https://github.com/WizballESY/librenms-device-photo/releases';
@endphp

<style>
    html.dark .device-photo-version-badge.label-warning {
        background: #8a6428 !important;
        border-color: #a77a32 !important;
        color: #fff3d6 !important;
    }

    html.dark .device-photo-version-badge.label-warning:hover,
    html.dark .device-photo-version-badge.label-warning:focus {
        background: #9a7330 !important;
        border-color: #bd8b3b !important;
        color: #ffffff !important;
    }
</style>

<a href="{{ $releaseUrl }}"
   target="_blank"
   rel="noopener noreferrer"
   class="label label-warning device-photo-version-badge"
   title="This plugin is currently in alpha. Features may change and bugs may exist."
   style="font-size: 11px; vertical-align: middle; margin-left: 8px; color: #fff; text-decoration: none;">
    {{ $label }}
</a>
