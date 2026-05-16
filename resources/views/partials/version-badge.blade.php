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

<a href="{{ $releaseUrl }}"
   target="_blank"
   rel="noopener noreferrer"
   class="label label-warning"
   title="This plugin is currently in alpha. Features may change and bugs may exist."
   style="font-size: 11px; vertical-align: middle; margin-left: 8px; color: #fff; text-decoration: none;">
    {{ $label }}
</a>
