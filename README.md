# LibreNMS Device Photos

**Device Photos** is a LibreNMS plugin for adding, viewing and managing photos for devices.

It is useful for documenting switches, firewalls, routers, servers, racks, patch panels, installation details and other physical device-related information directly inside LibreNMS.

The plugin adds:

- a **Device Photos** widget on the normal LibreNMS device page
- a **Manage Device Photos** page for each device
- a global **Device Photos Overview** page for administration and cleanup

---

## Status

This plugin is currently in **alpha**.

It is under active development and may contain bugs or breaking changes. It is not recommended for production use unless you have tested it thoroughly in your own environment.

> **Alpha notice**
>
> Features may change, and bugs may exist. Test thoroughly before using this plugin in production.

---

## Features

- Upload photos to LibreNMS devices.
- View photos directly from the normal LibreNMS device overview page.
- Manage photos per device.
- Global photo overview page.
- Private photo storage outside the public webroot.
- Authenticated image delivery through LibreNMS.
- Drag and drop upload.
- Multiple drag/drop rounds before upload; new files are appended.
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
- Show stored filename on the Manage Device Photos page.
- Link photos between devices.
- Automatically remove photo links when the original owned photo is deleted.
- Detect broken photo links.
- Detect orphaned photos from removed LibreNMS devices.
- Assign orphaned photos to existing devices.
- Soft-delete photos by moving them to a deleted folder.
- Search, pagination and sortable overview table.
- Browser-saved page size preference for the overview table.
- Cache-busting image URLs to avoid stale browser-cached photos after delete/re-upload.

---

## Installation

Adjust paths if your LibreNMS installation is not located in `/opt/librenms`.

### Option A: Install from release archive

Download the latest release archive from GitHub Releases:

```text
https://github.com/WizballESY/librenms-device-photo/releases
```

Example using the `v0.1.0-alpha.2` tar archive:

```bash
cd /tmp

wget https://github.com/WizballESY/librenms-device-photo/releases/download/v0.1.0-alpha.2/librenms-device-photo-v0.1.0-alpha.2.tar.gz

tar xzf librenms-device-photo-v0.1.0-alpha.2.tar.gz

cd /opt/librenms

cp -a /tmp/librenms-device-photo-v0.1.0-alpha.2/DevicePhoto app/Plugins/DevicePhoto
mkdir -p html/plugins
cp -a /tmp/librenms-device-photo-v0.1.0-alpha.2/html/plugins/DevicePhoto html/plugins/DevicePhoto
```

Then continue with storage directory creation, permissions and cache clearing below.

The same release is also available as a ZIP archive:

```text
https://github.com/WizballESY/librenms-device-photo/releases/download/v0.1.0-alpha.2/librenms-device-photo-v0.1.0-alpha.2.zip
```

### Option B: Install from Git clone

```bash
cd /tmp

git clone https://github.com/Wizball/librenms-device-photo.git

cd /opt/librenms

cp -a /tmp/librenms-device-photo/DevicePhoto app/Plugins/DevicePhoto
mkdir -p html/plugins
cp -a /tmp/librenms-device-photo/html/plugins/DevicePhoto html/plugins/DevicePhoto
```

Alternative using `rsync` from a cloned repository:

```bash
cd /tmp/librenms-device-photo

rsync -av --delete DevicePhoto/ /opt/librenms/app/Plugins/DevicePhoto/
mkdir -p /opt/librenms/html/plugins/DevicePhoto
rsync -av --delete html/plugins/DevicePhoto/ /opt/librenms/html/plugins/DevicePhoto/
```

### 1. Create storage directories

The plugin stores uploaded photos, thumbnails, deleted photos and local metadata under `storage/app`.

```bash
cd /opt/librenms

mkdir -p storage/app/device-photos
mkdir -p storage/app/device-photos/thumbs
mkdir -p storage/app/device-photos/deleted
mkdir -p storage/app/device-photos/deleted/thumbs
mkdir -p storage/app/device-photos-order
mkdir -p storage/app/device-photos-links
```

### 2. Set ownership and permissions

The storage directories must be writable by the user/group used by your LibreNMS PHP/web process.

Many LibreNMS installations use:

```text
librenms:librenms
```

Other installations may use:

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

find storage/app/device-photos storage/app/device-photos-order storage/app/device-photos-links \
  -type d -exec chmod 2775 {} \;

find storage/app/device-photos storage/app/device-photos-order storage/app/device-photos-links \
  -type f -exec chmod 664 {} \;
```

The `2` in `2775` enables the setgid bit so new files and directories normally inherit the parent directory group.

Check which user PHP runs as:

```bash
ps aux | grep -E 'php-fpm|apache|nginx' | head
grep -R "^[[:space:]]*user\|^[[:space:]]*group" /etc/php/*/fpm/pool.d/*.conf
```

The plugin does not force ownership with `chown()` on uploaded files. It only sets file mode with `chmod()`. Ownership is controlled by your PHP/webserver user and filesystem permissions.

### 3. Clear LibreNMS caches

```bash
cd /opt/librenms

sudo -u librenms php artisan view:clear
sudo -u librenms php artisan cache:clear
sudo -u librenms php artisan optimize:clear
```

If your LibreNMS user is not `librenms`, run the commands as the correct LibreNMS user.

### 4. Enable the plugin

Enable the plugin in LibreNMS if required by your LibreNMS plugin setup.

After enabling, the menu entry should appear under:

```text
Overview -> Plugins -> Device Photos Overview
```

---

## Optional dependencies

### HEIC/HEIF upload support

HEIC and HEIF uploads require ImageMagick with HEIC/HEIF support.

Check:

```bash
which magick
magick -list format 2>/dev/null | grep -Ei 'HEIC|HEIF'
```

### Set Photo taken support

Writing `Photo taken` back to JPG/JPEG EXIF metadata requires ExifTool.

Check:

```bash
which exiftool
exiftool -ver
```

On Debian/Ubuntu:

```bash
apt install libimage-exiftool-perl
```

---

## Requirements

- LibreNMS with the v2 plugin system.
- PHP GD extension for thumbnail generation.
- PHP EXIF extension for reading photo dates and JPEG orientation.
- Writable storage directories for the LibreNMS PHP/web process.
- Optional: ImageMagick with HEIC/HEIF support.
- Optional: ExifTool for writing `Photo taken` metadata back to JPG/JPEG files.

Check PHP modules:

```bash
php -m | grep -Ei 'gd|exif'
```

---

## Repository layout

```text
README.md
LICENSE
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

When installed in LibreNMS, the files should be placed here:

```text
/opt/librenms/app/Plugins/DevicePhoto/
/opt/librenms/html/plugins/DevicePhoto/DevicePhoto.inc.php
```

---

## Storage model

The plugin stores uploaded photos locally on the LibreNMS server.

Photos and thumbnails are stored outside the public webroot:

```text
storage/app/device-photos/
storage/app/device-photos/thumbs/
storage/app/device-photos/deleted/
storage/app/device-photos/deleted/thumbs/
```

Ordering and link metadata are stored as JSON:

```text
storage/app/device-photos-order/
storage/app/device-photos-links/
```

These directories contain installation-specific data such as uploaded photos, thumbnails, deleted photos, ordering data and photo links.

They should be backed up as part of the LibreNMS installation. Do not expose or publish these directories, because they may contain private infrastructure photos and local metadata.

---

## Private photo access

Photos are not intended to be served directly from a public URL.

Instead, images are delivered through authenticated LibreNMS plugin endpoints:

```text
/plugin/v1/DevicePhoto?action=photo&filename=device-42-1.jpg
/plugin/v1/DevicePhoto?action=thumb&filename=device-42-1.jpg
```

Unauthenticated users should be redirected to the LibreNMS login page.

The plugin validates filenames before serving images.

---

## Usage

### Device Photos widget

The normal LibreNMS device page shows a **Device Photos** widget.

The widget can show:

- owned photos for the current device
- photos linked from other devices
- link indicators
- thumbnails
- full-size photo viewer

### Manage Device Photos

Open the per-device manager from the Device Photos widget or directly:

```text
/plugin/DevicePhoto?device_id=<device_id>
```

Example:

```text
/plugin/DevicePhoto?device_id=42
```

The manager supports:

- upload
- drag and drop upload
- reorder owned photos
- download
- set `Photo taken` for JPG/JPEG files
- delete
- link photo to another device
- remove links
- open the full-size viewer

The stored filename is shown under each photo for troubleshooting.

### Device Photos Overview

Open from the LibreNMS menu:

```text
Overview -> Plugins -> Device Photos Overview
```

Direct URL:

```text
/plugin/DevicePhoto
```

The overview page includes:

- Photo library panel
- Maintenance panel
- Search toolbar
- pagination
- sortable table
- orphaned photos
- broken links
- thumbnail maintenance

---

## Device Photos Overview details

### Photo library panel

Shows:

- Devices
- Active photos
- Active size
- Deleted photos
- Deleted size

Size counters do not include thumbnails.

### Maintenance panel

Shows cleanup status.

If no issues are found:

```text
No maintenance issues found
```

If issues are found, only counters that need attention are shown.

Possible maintenance counters:

- Orphans
- Broken links
- Missing thumbnails
- Stale thumbnails

### Search, pagination and sorting

The overview table supports:

- search by device ID or device name
- rows per page: 10, 25, 50, 100 or All
- previous/next pagination
- page size preference saved in the browser
- sortable columns:
  - Device
  - Owned photos
  - Linked in
  - Linked out

Default sorting:

```text
Device A-Z
```

For numeric columns, first click sorts high-to-low.

---

## HEIC/HEIF uploads

HEIC and HEIF uploads are supported when ImageMagick has HEIC/HEIF support.

The plugin does not store HEIC/HEIF files as active photos. They are converted to JPG.

Example:

```text
IMG_1234.HEIC
-> device-42-3.jpg
```

Upload flow:

```text
Upload HEIC/HEIF
-> check ImageMagick support
-> convert to JPG
-> save as device-<id>-<number>.jpg
-> generate thumbnail
```

---

## Photo dates and EXIF

The plugin can show two date values.

### Photo taken

Read from EXIF metadata when available.

Current read support:

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

If no supported EXIF date exists, `Photo taken` is hidden.

### Set Photo taken

When ExifTool is installed, the Manage Device Photos page can write `Photo taken` back to JPG/JPEG EXIF metadata.

This writes to the original photo file using ExifTool:

```text
DateTimeOriginal
CreateDate
ModifyDate
```

Only JPG/JPEG files are supported for writing `Photo taken`.

PNG and WebP files are not modified.

### File date

Read from the file timestamp on the LibreNMS server using `filemtime()`.

File date may change if files are copied, restored or modified.

Dates are formatted in the browser using the user's local browser/OS locale.

---

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

If an original owned photo is deleted, links from other devices pointing to that original photo are automatically removed.

---

## Orphaned photos

An orphaned photo is an image file where the original LibreNMS Device ID no longer exists.

Example:

```text
storage/app/device-photos/device-99999-1.jpg
```

If Device ID `99999` does not exist in LibreNMS, the photo is orphaned.

Available actions:

- download
- assign to an existing device
- delete

Delete moves the file to:

```text
storage/app/device-photos/deleted/
```

It is not permanently removed.

If an orphaned photo has a thumbnail, the thumbnail follows the original photo when assigning or deleting.

---

## Broken links

A broken link is a saved photo link entry that points to a missing image file.

Action:

```text
Remove broken link
```

Removing a broken link only removes the JSON link entry. It does not delete any photo file.

Normally, deleting an owned original photo also removes links pointing to that photo automatically, so normal delete operations should not create broken links.

---

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

### EXIF orientation

Many phones store JPEG pixels sideways and use EXIF Orientation to tell viewers how the image should be displayed.

The plugin reads JPEG EXIF Orientation when generating thumbnails and rotates thumbnails accordingly.

This makes thumbnails match the orientation shown by the full-size image viewer.

### Missing thumbnails

A missing thumbnail means the original active photo exists but the thumbnail does not exist.

Use:

```text
Generate missing thumbnails
```

### Stale thumbnails

A stale thumbnail means a thumbnail exists but the matching original active photo does not exist.

Use:

```text
Clean stale thumbnails
```

This only deletes files from:

```text
storage/app/device-photos/thumbs/
```

It does not delete original photos.

---

## Backup and restore

### Backup

The runtime storage directories contain uploaded photos and local metadata. Include them in backups.

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

After restore, set ownership and clear LibreNMS caches.

---

## Uninstall

Disable the plugin in LibreNMS first if your installation requires it.

Remove the plugin source files:

```bash
cd /opt/librenms

rm -rf app/Plugins/DevicePhoto
rm -rf html/plugins/DevicePhoto
```

Clear LibreNMS caches:

```bash
cd /opt/librenms

sudo -u librenms php artisan view:clear
sudo -u librenms php artisan cache:clear
sudo -u librenms php artisan optimize:clear
```

Uploaded photos and metadata are stored separately under:

```text
storage/app/device-photos/
storage/app/device-photos-order/
storage/app/device-photos-links/
```

To keep uploaded photos and metadata, leave these directories in place.

To permanently remove all uploaded photos and plugin metadata:

```bash
cd /opt/librenms

rm -rf storage/app/device-photos
rm -rf storage/app/device-photos-order
rm -rf storage/app/device-photos-links
```

Warning: this permanently removes uploaded photos, thumbnails, deleted photos, ordering data and photo links.

---

## Maintenance commands

### List plugin files

```bash
cd /opt/librenms

find app/Plugins/DevicePhoto html/plugins/DevicePhoto \
  -type f \( -name "*.php" -o -name "*.blade.php" \) \
  -printf '%p\n' | sort
```

### PHP syntax check

```bash
cd /opt/librenms

php -l app/Plugins/DevicePhoto/DeviceOverview.php
php -l app/Plugins/DevicePhoto/Menu.php
php -l app/Plugins/DevicePhoto/Page.php
php -l app/Plugins/DevicePhoto/Settings.php
php -l html/plugins/DevicePhoto/DevicePhoto.inc.php
```

### Clear cache

```bash
cd /opt/librenms

sudo -u librenms php artisan view:clear
sudo -u librenms php artisan cache:clear
sudo -u librenms php artisan optimize:clear
```

---

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

Check PHP/web process user:

```bash
ps aux | grep -E 'php-fpm|apache|nginx' | head
grep -R "^[[:space:]]*user\|^[[:space:]]*group" /etc/php/*/fpm/pool.d/*.conf
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

### Set Photo taken is not available

Check ExifTool:

```bash
which exiftool
exiftool -ver
```

On Debian/Ubuntu:

```bash
apt install libimage-exiftool-perl
```

Only JPG/JPEG files support writing `Photo taken` through the plugin.

### Old image still appears after delete/re-upload

The plugin adds a cache-busting `v=` parameter to image URLs based on the file timestamp.

If stale images still appear, clear the browser cache and make sure the page source contains image URLs like:

```text
/plugin/v1/DevicePhoto?action=thumb&filename=device-42-1.jpg&v=...
```

### Direct image URL redirects to login

This is expected for unauthenticated users.

Images are served through authenticated plugin endpoints.

Example:

```text
/plugin/v1/DevicePhoto?action=photo&filename=device-42-1.jpg
```

A user who is not logged in should be redirected to the LibreNMS login page.

---

### Photo access permissions

Device Photo currently uses LibreNMS `global-read` permission for photo viewing.

It does not currently enforce per-device access restrictions on photo files. This means that a LibreNMS user with `global-read` access may be able to view plugin photos if they know the photo URL.

Deleted photo access requires the configured delete role.

## Security notes

- Uploaded photos may contain sensitive infrastructure information.
- Photos are stored outside the public webroot.
- Photos are served through an authenticated LibreNMS plugin endpoint.
- The plugin validates file extensions.
- Standard image uploads are validated as image files.
- HEIC/HEIF uploads are converted server-side and stored as JPG.
- Writing `Photo taken` modifies EXIF metadata in the original JPG/JPEG file.
- Deleted files are moved to a deleted folder and are not permanently removed immediately.
- Configure permissions so only trusted users can upload, modify or delete photos.
- State-changing actions require POST requests.
- Photo link actions require explicit upload/delete permissions.
- Plugin pages require LibreNMS `global-read` access.
- Uploads are limited by file size and image dimensions.
- ImageMagick HEIC/HEIF conversion uses resource limits.
- JSON metadata writes use file locking.
- Do not expose runtime photo directories or local metadata files publicly.

---

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
- JPEG thumbnails respect EXIF Orientation.
- The photo viewer is shared between plugin pages and the normal device overview widget.
- The footer is shared between plugin pages.

### Known future improvement ideas

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

---

## Test checklist

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
12. Confirm rotated phone photos generate correctly oriented thumbnails.
13. Upload multiple files.
14. Drag/drop one file, then drag/drop another before upload.
15. Reorder photos.
16. Set `Photo taken` on a JPG/JPEG file if ExifTool is available.
17. Link a photo to another device.
18. Remove the link.
19. Delete an owned photo that is linked to another device and confirm the link is removed automatically.
20. Test orphaned photo handling.
21. Assign an orphaned photo to a device.
22. Delete an orphaned photo.
23. Test broken link handling.
24. Generate missing thumbnails.
25. Clean stale thumbnails.
26. Confirm unauthenticated image URLs redirect to login.
27. Confirm plugin menu entry works.
28. Open plugin settings.
29. Run PHP syntax checks.

---

## License

GNU General Public License v3.0.

See [LICENSE](LICENSE).

---

## Credits

Created by Wizball.

This plugin was developed with assistance from AI.
