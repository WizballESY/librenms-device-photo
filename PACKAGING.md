# Packaging plan

This branch is used to prepare LibreNMS Device Photos as a Composer/Packagist-compatible package.

## Important

The current local LibreNMS plugin install method must continue to work until the Composer package version has been tested properly.

Current local install layout:

- `DevicePhoto/` is copied to `/opt/librenms/app/Plugins/DevicePhoto`
- `html/plugins/DevicePhoto/DevicePhoto.inc.php` is copied to `/opt/librenms/html/plugins/DevicePhoto/DevicePhoto.inc.php`

Runtime data currently uses:

- `storage/app/device-photos`
- `storage/app/device-photos-order`
- `storage/app/device-photos-links`

## Goal

Prepare a Composer package layout without breaking the existing local plugin release.

Planned future package layout:

- `composer.json`
- `src/DevicePhotoServiceProvider.php`
- `routes/web.php`
- `resources/views/`
- `hooks/`
- `config/device-photo.php`

## Migration rule

Do not remove the legacy/local install files until the Composer package has been tested in a LibreNMS lab environment.

## First Composer release target

The first package-style release should probably be:

`v0.2.0-alpha.1`

The existing local install alpha series should remain available as:

`v0.1.0-alpha.x`