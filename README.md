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

- Upload and manage photos for LibreNMS devices.
- Show device photos directly on the normal LibreNMS device page.
- Use a dedicated management page for upload, reorder, delete, restore and metadata actions.
- Link photos between devices without duplicating the original image file.
- Reorder owned and linked photos together.
- Restore deleted photos from a deleted-photo area.
- Detect orphaned photos, broken links, missing thumbnails and stale thumbnails.
- Generate and clean thumbnails from the maintenance panel.
- Store photos privately outside the public webroot.
- Serve images through authenticated LibreNMS endpoints.
- Optional support for ExifTool and HEIC/HEIF conversion.

---

## Owned and linked photos

A photo is always owned by one LibreNMS device.

An owned photo is stored with the owner device ID in the filename, for example:

~~~text
device-108-1.jpg
~~~

A linked photo is not copied. Instead, another device can show a reference to the owner device photo.

Example:

~~~text
Device 109 owns:
device-109-2.jpg

Device 108 can link to that photo and show it as:
linked:109:device-109-2.jpg
~~~

This makes it possible to reuse the same physical photo across related devices, racks, stacks, modules or shared installation views without storing duplicate image files.

Deleting an owned photo removes the original photo and cleans up links that pointed to it. Removing a link only removes the reference from the target device; it does not delete the original photo.

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

sudo -u librenms ./lnms plugin:add wizballesy/librenms-device-photo v0.1.0-alpha.30
sudo -u librenms php artisan optimize:clear
sudo -u librenms php artisan view:clear
```

This uses LibreNMS' plugin package installer and installs the package from Packagist.

After installation, open:

```text
/plugin/device-photo
```

## Testing

For alpha release testing, see the [alpha release test checklist](docs/testing-alpha.md).

### Development install from GitHub

For development testing before a tagged release, you can install directly from the GitHub repository:

```bash
cd /opt/librenms

sudo -u librenms php /opt/librenms/composer.phar config repositories.librenms-device-photo vcs https://github.com/WizballESY/librenms-device-photo
sudo -u librenms ./lnms plugin:add wizballesy/librenms-device-photo dev-main
sudo -u librenms php artisan optimize:clear
sudo -u librenms php artisan view:clear
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

The plugin stores photos and metadata under LibreNMS `storage/app`.

Default paths:

~~~text
storage/app/device-photos                 Uploaded photos
storage/app/device-photos/thumbs          Active thumbnails
storage/app/device-photos-deleted         Deleted photos
storage/app/device-photos-deleted/thumbs  Deleted thumbnails
storage/app/device-photos-order           Per-device photo order JSON
storage/app/device-photos-links           Linked-photo JSON
~~~

These directories are normally created automatically.

Photos are stored outside the public webroot and are served through authenticated LibreNMS plugin endpoints.

If you need to repair permissions, many LibreNMS installs use `librenms:librenms`:

~~~bash
cd /opt/librenms

mkdir -p \
  storage/app/device-photos/thumbs \
  storage/app/device-photos-deleted/thumbs \
  storage/app/device-photos-order \
  storage/app/device-photos-links

chown -R librenms:librenms \
  storage/app/device-photos \
  storage/app/device-photos-deleted \
  storage/app/device-photos-order \
  storage/app/device-photos-links
~~~

---

## Backup

Before installing, upgrading or uninstalling the plugin, back up the plugin data stored under LibreNMS `storage/app`.

The most important paths are:

    storage/app/device-photos
    storage/app/device-photos-deleted
    storage/app/device-photos-order
    storage/app/device-photos-links

This includes uploaded photos, thumbnails, deleted photos, saved photo order and photo links.

Example backup command:

    cd /opt/librenms

    sudo tar -czf /root/librenms-device-photo-backup-$(date +%Y%m%d-%H%M).tar.gz \
      storage/app/device-photos \
      storage/app/device-photos-deleted \
      storage/app/device-photos-order \
      storage/app/device-photos-links

To inspect the backup:

    tar -tzf /root/librenms-device-photo-backup-YYYYMMDD-HHMM.tar.gz | head

Keep this backup somewhere safe before making major changes.

---

## Configuration

The default configuration is suitable for most installs.

Main defaults:

~~~php
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
~~~

### Link and order metadata

A photo is owned by one device, but it can be linked to other devices.

Link metadata is stored per target device:

~~~text
storage/app/device-photos-links/device-<target-device-id>.json
~~~

Example:

~~~json
[
  {
    "owner_device_id": 109,
    "filename": "device-109-2.png"
  }
]
~~~

Photo order is stored per device:

~~~text
storage/app/device-photos-order/device-<device-id>.json
~~~

Order files may contain both owned photos and linked photo keys:

~~~json
[
  "device-108-1.jpg",
  "linked:109:device-109-2.png",
  "device-108-2.jpg"
]
~~~

The plugin keeps existing order where possible, removes stale order entries, and appends new valid photos or links.

### Deleted photo restore

Deleted photos are moved to:

~~~text
storage/app/device-photos-deleted
storage/app/device-photos-deleted/thumbs
~~~

Deleted filenames include the original filename and a deletion timestamp:

~~~text
device-108-5.deleted-20260518-174625.jpg
device-108-5.deleted-20260518-174625-2.jpg
~~~

When restoring a deleted photo, the admin selects the target LibreNMS device. The plugin does not blindly restore the photo to the old device ID from the deleted filename.

Example:

~~~text
device-108-5.deleted-20260518-174625.jpg
-> device-109-3.jpg
~~~

Older alpha releases stored deleted photos under `storage/app/device-photos/deleted`. The maintenance panel can detect and migrate that old deleted photo storage.

---

## Optional dependencies

On Debian/Ubuntu, the recommended optional packages are:

    sudo apt install php-gd libimage-exiftool-perl imagemagick libheif1 libheif-plugin-libde265 libde265-0

Package names may vary between distributions.

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

## Updating

To update to a specific release:

```bash
cd /opt/librenms

sudo -u librenms ./lnms plugin:add wizballesy/librenms-device-photo v0.1.0-alpha.30
sudo -u librenms php artisan optimize:clear
sudo -u librenms php artisan view:clear
```

Replace `v0.1.0-alpha.30` with the version you want to install.

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
rm -rf storage/app/device-photos-deleted
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
