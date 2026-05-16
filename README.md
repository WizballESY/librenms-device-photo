# LibreNMS Device Photos

**LibreNMS Device Photos** is a LibreNMS plugin for adding, viewing and managing photos for devices.

It is useful for documenting switches, firewalls, routers, servers, racks, patch panels, installation details and other physical device-related information directly inside LibreNMS.

The plugin adds:

- a **Device Photos** widget on the normal LibreNMS device page
- a **Manage Device Photos** page for each device
- a global **Device Photos Overview** page for administration and cleanup

---

## Status

This plugin is currently in **alpha**.

It is under active development and may contain bugs, incomplete documentation or breaking changes. Test thoroughly before using it in production.

---

## Screenshots

- [Device Photos widget](docs/screenshots/device-photos.png)
- [Fullscreen photo viewer](docs/screenshots/photo-fullscreen.png)
- [Manage Device Photos](docs/screenshots/manage-device-photos.png)
- [Device Photos Overview](docs/screenshots/device-photos-overview.png)

---

## Features

- Upload photos to LibreNMS devices.
- View photos directly from the normal LibreNMS device overview page.
- Manage photos per device.
- Global photo overview page.
- Private photo storage outside the public webroot.
- Authenticated image delivery through LibreNMS.
- Drag and drop upload.
- Upload multiple files at once.
- Supported upload types: `jpg`, `jpeg`, `png`, `webp`, `heic`, `heif`.
- HEIC/HEIF conversion to JPG when ImageMagick supports HEIC/HEIF.
- Thumbnail generation.
- JPEG thumbnail auto-rotation based on EXIF Orientation.
- Missing thumbnail detection and generation.
- Stale thumbnail detection and cleanup.
- Full-size image viewer with zoom and pan.
- Photo date display:
  - `Photo taken` from EXIF when available.
  - `File date` from the server file timestamp.
- Write `Photo taken` back to JPG/JPEG EXIF metadata using ExifTool.
- Reorder owned photos.
- Link photos between devices.
- Automatically remove photo links when the original owned photo is deleted.
- Detect broken photo links.
- Detect orphaned photos from removed LibreNMS devices.
- Assign orphaned photos to existing devices.
- Soft-delete photos by moving them to a deleted folder.
- Search, pagination and sortable overview table.
- Browser-saved page size preference for the overview table.
- Cache-busting image URLs.

---

## Requirements

- LibreNMS with the package/plugin system available.
- PHP `>= 8.2`.
- A writable LibreNMS storage directory.

Optional, but recommended:

- PHP GD extension for thumbnail generation.
- ExifTool for writing `Photo taken` metadata to JPG/JPEG files.
- ImageMagick with HEIC/HEIF support for HEIC/HEIF uploads.

---

## Installation

This plugin is installed as a Composer package.

Manual copy-based installation is no longer the recommended installation method.

### Install from Packagist

Recommended installation method:

```bash
cd /opt/librenms

sudo -u librenms ./lnms plugin:add wizballesy/librenms-device-photo v0.1.0-alpha.5
sudo -u librenms php artisan optimize:clear
```

This uses LibreNMS' plugin package installer and installs the package from Packagist.

After installation, open:

```text
/plugin/device-photo
```

### Development install from GitHub

For development testing before a tagged release, you can install directly from the GitHub repository:

```bash
cd /opt/librenms

sudo -u librenms php /opt/librenms/composer.phar config repositories.librenms-device-photo vcs https://github.com/WizballESY/librenms-device-photo
sudo -u librenms ./lnms plugin:add wizballesy/librenms-device-photo dev-main
sudo -u librenms php artisan optimize:clear
```

For normal users, prefer the tagged Packagist install above.

### LibreNMS validate note

Installing LibreNMS plugin packages modifies:

```text
composer.json
composer.lock
```

LibreNMS `validate` may warn that these files are locally modified after installing third-party plugin packages. This is expected because the plugin is installed as a Composer dependency inside the LibreNMS application directory.

Do not run `./scripts/github-remove` unless you intentionally want to remove local Composer changes.

---

## Storage directories

The plugin stores uploaded photos and metadata under LibreNMS `storage/app`.

Default paths:

```text
storage/app/device-photos
storage/app/device-photos/thumbs
storage/app/device-photos/deleted
storage/app/device-photos/deleted/thumbs
storage/app/device-photos-order
storage/app/device-photos-links
```

These directories are normally created automatically when the plugin page is opened or when photos are uploaded.

If you get permission warnings, verify ownership and permissions. Many LibreNMS installs use `librenms:librenms`:

```bash
cd /opt/librenms

mkdir -p \
  storage/app/device-photos/thumbs \
  storage/app/device-photos/deleted/thumbs \
  storage/app/device-photos-order \
  storage/app/device-photos-links

chown -R librenms:librenms \
  storage/app/device-photos \
  storage/app/device-photos-order \
  storage/app/device-photos-links

find storage/app/device-photos storage/app/device-photos-order storage/app/device-photos-links \
  -type d -exec chmod 2775 {} \;

find storage/app/device-photos storage/app/device-photos-order storage/app/device-photos-links \
  -type f -exec chmod 664 {} \;
```

---

## Configuration

Default configuration:

```php
'photos_path' => 'app/device-photos',
'order_path' => 'app/device-photos-order',
'links_path' => 'app/device-photos-links',
'max_upload_bytes' => 10 * 1024 * 1024,
'max_pixels' => 40000000,
'allowed_extensions' => [
    'jpg',
    'jpeg',
    'png',
    'webp',
    'heic',
    'heif',
],
```

---

## Optional dependencies

### PHP GD

PHP GD is used for thumbnail generation.

Check if GD is loaded:

```bash
php -m | grep -i '^gd$' || echo "GD missing"
```

### ExifTool

ExifTool is used when writing `Photo taken` metadata to JPG/JPEG files.

```bash
command -v exiftool || echo "exiftool missing"
```

### ImageMagick HEIC/HEIF support

ImageMagick is used for HEIC/HEIF conversion.

```bash
command -v magick || echo "magick missing"

magick -list format 2>/dev/null | grep -Ei 'HEIC|HEIF' || echo "No HEIC/HEIF support shown"
```

HEIC/HEIF uploads require ImageMagick with HEIC/HEIF read support. Uploaded HEIC/HEIF photos are converted to JPG.

---

## Internal package endpoints

The plugin page is available through LibreNMS as:

```text
/plugin/device-photo
```

The package also uses internal authenticated endpoints:

```text
/plugin/device-photo-package/image
/plugin/device-photo-package/action
```

These are used for image delivery and POST actions.

---

## Migrating from old manual/local install

Older development versions used a manual local plugin layout.

If you previously installed an old manual version, back it up and remove the old local plugin code:

```bash
cd /opt/librenms

if [ -d app/Plugins/DevicePhoto ] || [ -d html/plugins/DevicePhoto ]; then
  tar -czf /root/DevicePhoto-local-backup-$(date +%Y%m%d-%H%M%S).tgz \
    app/Plugins/DevicePhoto \
    html/plugins/DevicePhoto \
    2>/dev/null || true
fi

rm -rf app/Plugins/DevicePhoto
rm -rf html/plugins/DevicePhoto

sudo -u librenms php artisan optimize:clear
```

Do **not** remove the storage directories unless you intentionally want to delete all photos and metadata:

```text
storage/app/device-photos
storage/app/device-photos-order
storage/app/device-photos-links
```

---

## Updating

To update to a specific release:

```bash
cd /opt/librenms

sudo -u librenms ./lnms plugin:add wizballesy/librenms-device-photo v0.1.0-alpha.5
sudo -u librenms php artisan optimize:clear
```

Replace `v0.1.0-alpha.5` with the version you want to install.

LibreNMS `validate` may warn that `composer.json` and `composer.lock` are modified after installing or updating third-party plugin packages. This is expected because the plugin is installed as a Composer dependency inside the LibreNMS application directory.

---

## Uninstall

Disable the plugin:

```bash
cd /opt/librenms

sudo -u librenms ./lnms plugin:disable device-photo
sudo -u librenms php artisan optimize:clear
```

Remove the package:

```bash
cd /opt/librenms

sudo -u librenms composer remove wizballesy/librenms-device-photo
sudo -u librenms php artisan optimize:clear
```

This does not remove stored photos or metadata.

To remove stored plugin data manually:

```bash
cd /opt/librenms

rm -rf storage/app/device-photos
rm -rf storage/app/device-photos-order
rm -rf storage/app/device-photos-links
```

Be careful: this deletes uploaded photos and metadata.

---

## Security notes

- Images are served through authenticated package endpoints.
- Users must have LibreNMS `global-read` access to view photos.
- Deleted photos require delete permissions to view.
- POST actions use LibreNMS/Laravel web middleware and CSRF protection.
- Uploaded filenames are normalized and validated.
- Original photos are stored outside the public webroot.

---

## Support

If this plugin saves you time or helps your LibreNMS setup, you can optionally support the project here:

[Support the project](https://paypal.me/WizballESY)

Donations are optional and are not required to use the plugin.

---

## License

GPL-3.0-or-later.
