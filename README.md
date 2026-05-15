# LibreNMS Device Photos Plugin

A LibreNMS plugin for adding, viewing, linking and managing photos for devices.

The plugin adds a **Device Photos** widget on the normal LibreNMS device overview page, a **Manage Device Photos** page for each device, and a global **Device Photos Overview** page for administration and maintenance.

> This plugin was created and tested in a local LibreNMS environment. Use at your own risk. Test thoroughly before using it in production.

## Features

- Device photo widget on the normal LibreNMS device page.
- Per-device management page: **Manage Device Photos**.
- Global administration page: **Device Photos Overview**.
- Upload one or more photos.
- Drag and drop upload.
- Multiple drag/drop rounds before upload; new files are appended.
- Upload mixed file types in the same upload.
- Supported upload types: `jpg`, `jpeg`, `png`, `webp`, `heic`, `heif`.
- HEIC/HEIF conversion to JPG when ImageMagick supports HEIC/HEIF.
- Thumbnail generation.
- Missing thumbnail detection and generation.
- Stale thumbnail detection and cleanup.
- Shared full-size image viewer with zoom and pan.
- `Photo taken` from EXIF where available.
- `File date` from the server file timestamp.
- Link photos between devices.
- Detect and remove broken photo links.
- Detect, assign and delete orphaned photos.
- Soft-delete photos by moving them to a deleted folder.
- Private photo storage outside the public webroot.
- Authenticated image delivery through the plugin endpoint.
- Search, pagination and sortable overview table.
- Browser-saved page size preference for the overview table.

## Recommended repository layout

Keep `README.md` in the repository root so GitHub displays it automatically.

Recommended layout:

```text
README.md
DevicePhoto/
├── DeviceOverview.php
├── Menu.php
├── Page.php
├── Settings.php
└── resources/
    └── views/
        ├── device-overview.blade.php
        ├── menu.blade.php
        ├── page.blade.php
        ├── settings.blade.php
        └── partials/
            ├── footer.blade.php
            └── photo-modal.blade.php
html/
└── plugins/
    └── DevicePhoto/
        └── DevicePhoto.inc.php
```

In LibreNMS the files are installed as:

```text
/opt/librenms/app/Plugins/DevicePhoto/
/opt/librenms/html/plugins/DevicePhoto/DevicePhoto.inc.php
```

## Runtime data

Runtime data should not be committed to Git.

Photos, thumbnails and deleted photos are stored outside the public webroot:

```text
storage/app/device-photos/
storage/app/device-photos/thumbs/
storage/app/device-photos/deleted/
storage/app/device-photos/deleted/thumbs/
```

Order and link metadata:

```text
storage/app/device-photos-order/
storage/app/device-photos-links/
```

Recommended `.gitignore`:

```gitignore
storage/app/device-photos/
storage/app/device-photos-order/
storage/app/device-photos-links/
```

## Private image storage and authentication

Photos are not intended to be served directly from public URLs.

Instead, images are delivered through authenticated plugin endpoints:

```text
/plugin/v1/DevicePhoto?action=photo&filename=device-42-1.jpg
/plugin/v1/DevicePhoto?action=thumb&filename=device-42-1.jpg
```

Unauthenticated users should be redirected to the LibreNMS login page.

The plugin validates filenames before serving images.

## Terminology

### Device Photos

The general plugin name.

### Device Photos Overview

The global overview and maintenance page:

```text
/plugin/DevicePhoto
```

### Manage Device Photos

The per-device management page:

```text
/plugin/DevicePhoto?device_id=<device_id>
```

### Owned photos

Photos physically owned by a device.

Example:

```text
device-42-1.jpg
```

### Linked in

Photos owned by other devices but shown on the current device.

### Linked out

Photos owned by the current device but shown on other devices.

### Orphaned photos

Original photo files where the original LibreNMS Device ID no longer exists.

### Broken links

Photo link entries that point to a missing original photo file.

### Missing thumbnails

Active photos without a generated thumbnail.

### Stale thumbnails

Thumbnail files where the matching original active photo no longer exists.

## Requirements

- LibreNMS with the v2 plugin system.
- PHP GD extension for thumbnail generation.
- PHP EXIF extension for `Photo taken` from JPG/JPEG images.
- Writable runtime directories for the PHP/LibreNMS web process.
- Optional ImageMagick HEIC/HEIF support.

Check PHP modules:

```bash
php -m | grep -Ei 'gd|exif'
```

Check HEIC/HEIF support:

```bash
which magick
magick -list format 2>/dev/null | grep -Ei 'HEIC|HEIF'
```

Expected example:

```text
HEIC  HEIC  rw+  High Efficiency Image Format
HEIF  HEIC  rw+  High Efficiency Image Format
```

## Installation

Adjust paths if your LibreNMS installation is not located in `/opt/librenms`.

### 1. Copy plugin files

```bash
cd /opt/librenms

cp -a /path/to/repo/DevicePhoto app/Plugins/DevicePhoto
cp -a /path/to/repo/html/plugins/DevicePhoto html/plugins/DevicePhoto
```

### 2. Create runtime directories

```bash
cd /opt/librenms

mkdir -p storage/app/device-photos
mkdir -p storage/app/device-photos/thumbs
mkdir -p storage/app/device-photos/deleted
mkdir -p storage/app/device-photos/deleted/thumbs
mkdir -p storage/app/device-photos-order
mkdir -p storage/app/device-photos-links
```

### 3. Set ownership and permissions

The runtime directories must be writable by the user/group used by your LibreNMS PHP/web process.

Common LibreNMS installations use:

```text
librenms:librenms
```

Some installations may use:

```text
www-data:www-data
apache:apache
nginx:nginx
```

Use the user/group that matches your environment.

Common LibreNMS example:

```bash
cd /opt/librenms

chown -R librenms:librenms \
  app/Plugins/DevicePhoto \
  html/plugins/DevicePhoto \
  storage/app/device-photos \
  storage/app/device-photos-order \
  storage/app/device-photos-links
```

Alternative example if your PHP/webserver runs as `www-data`:

```bash
cd /opt/librenms

chown -R www-data:www-data \
  storage/app/device-photos \
  storage/app/device-photos-order \
  storage/app/device-photos-links
```

Set permissions:

```bash
cd /opt/librenms

find storage/app/device-photos storage/app/device-photos-order storage/app/device-photos-links \
  -type d -exec chmod 2775 {} \;

find storage/app/device-photos storage/app/device-photos-order storage/app/device-photos-links \
  -type f -exec chmod 664 {} \;
```

The `2` in `2775` enables the setgid bit so new files and folders normally inherit the group from the parent directory.

### 4. Check which user PHP runs as

```bash
ps aux | grep -E 'php-fpm|apache|nginx' | head
grep -R "^[[:space:]]*user\|^[[:space:]]*group" /etc/php/*/fpm/pool.d/*.conf
```

The plugin does not force `chown()` on uploaded files. It only sets file mode with `chmod()`. Owner and group are determined by your PHP/webserver environment and directory permissions.

### 5. Clear LibreNMS caches

```bash
cd /opt/librenms

sudo -u librenms php artisan view:clear
sudo -u librenms php artisan cache:clear
sudo -u librenms php artisan optimize:clear
```

If your LibreNMS user is not `librenms`, run the artisan commands as the correct LibreNMS user for your installation.

### 6. Enable the plugin

Enable the plugin in LibreNMS if required by your LibreNMS plugin setup.

After enabling, you should see:

```text
Overview -> Plugins -> Device Photos Overview
```

## Overview page

The **Device Photos Overview** page has:

- Photo library panel.
- Maintenance panel.
- Search toolbar.
- Rows per page selector: `10`, `25`, `50`, `100`, `All`.
- Saved page size preference in the browser.
- Sortable columns:
  - Device
  - Owned photos
  - Linked in
  - Linked out

Default sorting:

```text
Device A-Z
```

For numeric columns, first click sorts high-to-low.

## Manage Device Photos

The per-device page supports:

- Upload photos.
- Drag/drop photos.
- Upload mixed file types.
- Reorder owned photos.
- Download photos.
- Delete photos.
- Link photos to another device.
- Remove links.
- Open photos in the shared full-size viewer.
- See `Photo taken` and `File date`.

## HEIC/HEIF upload conversion

HEIC/HEIF uploads are converted to JPG when ImageMagick supports HEIC/HEIF.

Example:

```text
IMG_1234.HEIC
-> device-42-3.jpg
```

Flow:

```text
Upload HEIC/HEIF
-> check ImageMagick HEIC/HEIF support
-> convert to JPG
-> save as device-<id>-<number>.jpg
-> generate thumbnail
-> show as normal photo
```

## Photo dates

### Photo taken

Read from EXIF metadata when available.

Current support:

```text
jpg
jpeg
```

Priority:

```text
DateTimeOriginal
DateTimeDigitized
IFD0 DateTime
```

### File date

Read from the file timestamp on the LibreNMS server using `filemtime()`.

Dates are formatted in the browser using the user's local browser/OS locale.

## Linked photos

Linked photos allow one device to display a photo owned by another device.

Example:

```text
Device 43 displays device-42-1.jpg
Owner device: 42
Target device: 43
```

The link is stored in:

```text
storage/app/device-photos-links/device-43.json
```

Example:

```json
[
    {
        "owner_device_id": 42,
        "filename": "device-42-1.jpg"
    }
]
```

Removing a link does not delete the original photo.

## Orphaned photos

An orphaned photo is an image file where the original LibreNMS Device ID no longer exists.

Example:

```text
storage/app/device-photos/device-99999-1.jpg
```

Available actions:

- Download.
- Assign to an existing device.
- Delete.

Delete means the file is moved to:

```text
storage/app/device-photos/deleted/
```

It is not permanently removed.

If an orphaned photo has a thumbnail, the thumbnail follows the original photo when assigning or deleting.

## Broken links

A broken link is a saved photo link entry that points to a missing image file.

Action:

```text
Remove broken link
```

Removing a broken link only removes the JSON link entry. It does not delete any photo file.

## Thumbnails

Thumbnails are stored in:

```text
storage/app/device-photos/thumbs/
```

Fallback behavior:

```text
If thumbnail exists:
    use thumbnail

If thumbnail is missing:
    use original image
```

The full-size image viewer always opens the original image.

## Missing and stale thumbnails

### Missing thumbnails

Original active photo exists, but thumbnail does not exist.

Use:

```text
Generate missing thumbnails
```

### Stale thumbnails

Thumbnail exists, but original active photo does not exist.

Use:

```text
Clean stale thumbnails
```

This only deletes files from:

```text
storage/app/device-photos/thumbs/
```

It does not delete original photos.

## Backup and restore

### Backup

```bash
cd /opt/librenms

tar czf /root/DevicePhoto-backup-$(date +%F-%H%M).tar.gz \
  app/Plugins/DevicePhoto \
  html/plugins/DevicePhoto \
  storage/app/device-photos \
  storage/app/device-photos-order \
  storage/app/device-photos-links
```

### Restore

```bash
cd /opt/librenms

tar xzf /root/DevicePhoto-backup-YYYY-MM-DD-HHMM.tar.gz -C /opt/librenms
```

Then set ownership to match your LibreNMS/PHP environment and clear cache.

## Maintenance commands

List plugin files:

```bash
cd /opt/librenms

find app/Plugins/DevicePhoto html/plugins/DevicePhoto \
  -type f \( -name "*.php" -o -name "*.blade.php" \) \
  -printf '%p\n' | sort
```

PHP syntax check:

```bash
cd /opt/librenms

php -l app/Plugins/DevicePhoto/DeviceOverview.php
php -l app/Plugins/DevicePhoto/Menu.php
php -l app/Plugins/DevicePhoto/Page.php
php -l app/Plugins/DevicePhoto/Settings.php
php -l html/plugins/DevicePhoto/DevicePhoto.inc.php
```

Clear cache:

```bash
cd /opt/librenms

sudo -u librenms php artisan view:clear
sudo -u librenms php artisan cache:clear
sudo -u librenms php artisan optimize:clear
```

## Troubleshooting

### Upload fails with 413 Request Entity Too Large

The webserver upload limit is too low.

Examples:

```text
Nginx: client_max_body_size
Apache: LimitRequestBody
```

Also check PHP:

```text
upload_max_filesize
post_max_size
```

### Upload fails because of permissions

Check runtime directory permissions:

```bash
cd /opt/librenms

ls -ld storage/app/device-photos
ls -ld storage/app/device-photos/thumbs
ls -ld storage/app/device-photos-order
ls -ld storage/app/device-photos-links
```

### HEIC upload fails

Check ImageMagick:

```bash
which magick
magick -list format 2>/dev/null | grep -Ei 'HEIC|HEIF'
```

The Manage Device Photos page should show:

```text
HEIC/HEIF: Available
```

### Direct photo URL does not work

This is expected for unauthenticated users.

Images are served through an authenticated plugin endpoint:

```text
/plugin/v1/DevicePhoto?action=photo&filename=device-42-1.jpg
```

A user who is not logged in should be redirected to the LibreNMS login page.

## Security notes

- Do not allow arbitrary file types.
- The plugin validates image extensions.
- The plugin validates normal uploaded images with image checks.
- HEIC/HEIF files are converted server-side and stored as JPG.
- Photos are stored outside the public webroot.
- Photos are served through an authenticated LibreNMS plugin endpoint.
- Deleted files are moved to a deleted folder, not permanently removed.
- Permissions should be configured so only trusted users can upload or delete photos.

## Development notes

Current design choices:

- Photos are stored on disk, not in the database.
- Runtime photos are stored under `storage/app/device-photos`.
- Filenames use LibreNMS `device_id`.
- Links are stored as JSON.
- Ordering is stored as JSON.
- HEIC/HEIF uploads are converted to JPG.
- PNG is kept as PNG.
- WebP is kept as WebP.
- Thumbnails are optional and fail-safe.
- Missing thumbnails can be generated from the overview page.
- Stale thumbnails can be cleaned from the overview page.
- The photo viewer is shared between plugin pages and the normal device overview widget.
- The footer is shared between plugin pages.

## Known future improvement ideas

- Add a "Move photo to another device" function for owned photos.
- Add optional `exiftool` support for broader metadata reading.
- Add duplicate detection in drag/drop upload.
- Add restore UI for deleted photos.
- Add optional configurable thumbnail size.
- Add optional configurable max upload size.
- Add optional PNG-to-JPG conversion setting.
- Extend JSON structures with optional `sort_order`, captions or labels.
- Add cleanup/repair tools for JSON order/link files.
- Package the plugin as a Composer package later if needed.

## Recommended test checklist

After installation or upgrade, test:

1. Open Device Photos Overview.
2. Open Manage Device Photos for a device.
3. Open the normal LibreNMS device page with the Device Photos widget.
4. Click a photo from each view and confirm the shared viewer opens.
5. Test zoom and pan.
6. Confirm `File date` appears.
7. Confirm `Photo taken` appears when EXIF exists.
8. Upload JPG.
9. Upload HEIC if supported.
10. Confirm HEIC becomes JPG.
11. Confirm thumbnail is generated.
12. Upload multiple files.
13. Drag/drop one file, then drag/drop another before upload.
14. Reorder photos.
15. Link a photo to another device.
16. Remove the link.
17. Test orphaned photo handling.
18. Assign an orphaned photo to a device.
19. Delete an orphaned photo.
20. Test broken link handling.
21. Generate missing thumbnails.
22. Clean stale thumbnails.
23. Confirm unauthenticated image URLs redirect to login.
24. Confirm plugin menu entry works.
25. Open plugin settings.
26. Run PHP syntax checks.

## License

Add your preferred license here.

Example:

```text
MIT License
```

## Credits

Created by Wizball.

This plugin was developed with assistance from AI.
