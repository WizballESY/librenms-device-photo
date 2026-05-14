# LibreNMS Device Photo Plugin

A LibreNMS plugin for adding, viewing, linking and managing photos for devices.

The plugin adds a **Device Photos** box on the LibreNMS device overview page, a **Device Photo Manager** per device, and a global **Device Photos Overview** page for administration and maintenance.

> **Notice**
>
> This plugin was created and tested in a local LibreNMS environment. Use at your own risk. Test thoroughly before using it in production.

---

## Table of contents

- [Features](#features)
- [Pages](#pages)
- [How it works](#how-it-works)
- [File and directory structure](#file-and-directory-structure)
- [Requirements](#requirements)
- [Installation](#installation)
- [Permissions](#permissions)
- [Usage](#usage)
- [Device Photos Overview](#device-photos-overview)
- [Device Photo Manager](#device-photo-manager)
- [Device overview widget](#device-overview-widget)
- [Linked photos](#linked-photos)
- [Orphaned photos](#orphaned-photos)
- [Broken links](#broken-links)
- [Thumbnails](#thumbnails)
- [HEIC/HEIF upload conversion](#heicheif-upload-conversion)
- [Photo dates](#photo-dates)
- [Backup and restore](#backup-and-restore)
- [Maintenance commands](#maintenance-commands)
- [Troubleshooting](#troubleshooting)
- [Security notes](#security-notes)
- [Development notes](#development-notes)

---

## Features

- Add photos to LibreNMS devices.
- Show photos on the LibreNMS device overview page.
- Per-device photo manager.
- Global photo overview and maintenance page.
- Upload multiple photos.
- Drag and drop upload.
- Multiple drag/drop rounds before upload.
- Drag and drop photo ordering.
- Thumbnail generation.
- Full-size image viewer with zoom and pan.
- Photo date information:
  - `Photo taken` from EXIF when available.
  - `File date` from the file timestamp on the LibreNMS server.
- Link photos between devices.
- Show linked photos on other devices.
- Detect orphaned photos.
- Assign orphaned photos to existing devices.
- Detect and remove broken photo links.
- Soft-delete photos by moving them to a deleted folder.
- HEIC/HEIF upload support through automatic conversion to JPG when ImageMagick supports HEIC/HEIF.
- Plugin menu entry under `Overview -> Plugins -> Device Photos Overview`.

---

## Pages

### Device Photos Overview

```text
/plugin/DevicePhoto
```

Menu location:

```text
Overview -> Plugins -> Device Photos Overview
```

This is the global overview and maintenance page.

### Device Photo Manager

```text
/plugin/DevicePhoto?device_id=<device_id>
```

This is the per-device management page.

### Device Photo Settings

```text
/plugin/settings/DevicePhoto
```

This is the settings page.

---

## How it works

The plugin stores photos as files on disk. It does not store photos in the LibreNMS database.

Photos are named by LibreNMS `device_id`.

Example:

```text
device-42-1.jpg
device-42-2.jpg
device-42-3.webp
```

This keeps filenames stable even if a device hostname, sysName or display name changes.

The plugin uses:

- File storage for photos.
- JSON files for ordering.
- JSON files for links between devices.
- File timestamps for `File date`.
- EXIF metadata for `Photo taken` when available.

---

## File and directory structure

### Plugin files

```text
app/Plugins/DevicePhoto/
├── DeviceOverview.php
├── Menu.php
├── Page.php
├── Settings.php
└── resources/
    └── views/
        ├── device-overview.blade.php
        ├── menu.blade.php
        ├── page.blade.php
        └── settings.blade.php

html/plugins/DevicePhoto/
└── DevicePhoto.inc.php
```

### File purpose

#### `DeviceOverview.php`

LibreNMS device overview hook. Provides the Device Photos widget on a device page.

#### `device-overview.blade.php`

Renders the Device Photos box on the normal LibreNMS device overview page.

Includes thumbnail display, linked photo indicator, full-size image modal, zoom/pan and photo date footer in the modal.

#### `Menu.php`

LibreNMS plugin menu hook. Adds the plugin menu entry.

#### `menu.blade.php`

Renders the menu link.

#### `Page.php`

Backend/data provider for Device Photos Overview and Device Photo Manager.

Builds data for active photos, deleted photos, orphaned photos, broken links, linked photos, thumbnails, upload status and photo dates.

#### `page.blade.php`

Main UI for Device Photos Overview and Device Photo Manager.

Includes upload form, drag/drop upload, photo viewer, ordering, orphan handling, broken link handling and thumbnail maintenance.

#### `Settings.php`

Backend/data provider for plugin settings.

#### `settings.blade.php`

Settings UI.

#### `html/plugins/DevicePhoto/DevicePhoto.inc.php`

Action handler for POST requests.

Handles upload, HEIC/HEIF conversion, delete, reorder, link, unlink, orphan handling, thumbnail generation and broken link cleanup.

---

## Data directories

### Active photos

```text
html/device-photos/
```

Example:

```text
html/device-photos/device-42-1.jpg
html/device-photos/device-42-2.webp
```

### Thumbnails

```text
html/device-photos/thumbs/
```

Example:

```text
html/device-photos/thumbs/device-42-1.jpg
```

### Deleted photos

```text
html/device-photos/deleted/
```

Deleted photos are moved here. They are not permanently removed by the normal Delete button.

Example:

```text
device-42-1.deleted-20260514-120000.jpg
```

### Deleted thumbnails

```text
html/device-photos/deleted/thumbs/
```

When a photo is deleted, the thumbnail is moved here if it exists.

### Photo order

```text
storage/app/device-photos-order/
```

Example:

```text
storage/app/device-photos-order/device-42.json
```

### Photo links

```text
storage/app/device-photos-links/
```

Example:

```text
storage/app/device-photos-links/device-43.json
```

Example content:

```json
[
    {
        "owner_device_id": 42,
        "filename": "device-42-1.jpg"
    }
]
```

---

## Requirements

- LibreNMS with the v2 plugin system.
- PHP GD extension for thumbnail generation.
- PHP EXIF extension for reading `Photo taken` from JPG/JPEG images.
- Writable photo and JSON directories for the LibreNMS user.

Check PHP modules:

```bash
php -m | grep -Ei 'gd|exif'
```

---

## Optional dependencies

### HEIC/HEIF conversion

HEIC/HEIF upload conversion requires ImageMagick with HEIC/HEIF support.

Check:

```bash
which magick
magick -list format 2>/dev/null | grep -Ei 'HEIC|HEIF'
```

Expected example:

```text
HEIC  HEIC  rw+  High Efficiency Image Format
HEIF  HEIC  rw+  High Efficiency Image Format
```

If HEIC/HEIF conversion is available, the upload form shows:

```text
HEIC/HEIF: Available
```

---

## Installation

> Adjust paths if LibreNMS is not installed in `/opt/librenms`.

### 1. Copy plugin files

Copy the plugin files into:

```text
app/Plugins/DevicePhoto/
html/plugins/DevicePhoto/
```

Example:

```bash
cd /opt/librenms

cp -a /path/to/app/Plugins/DevicePhoto app/Plugins/DevicePhoto
cp -a /path/to/html/plugins/DevicePhoto html/plugins/DevicePhoto
```

### 2. Create data directories

```bash
cd /opt/librenms

mkdir -p html/device-photos
mkdir -p html/device-photos/thumbs
mkdir -p html/device-photos/deleted
mkdir -p html/device-photos/deleted/thumbs
mkdir -p storage/app/device-photos-order
mkdir -p storage/app/device-photos-links
```

### 3. Set ownership and permissions

```bash
cd /opt/librenms

chown -R librenms:librenms \
  app/Plugins/DevicePhoto \
  html/plugins/DevicePhoto \
  html/device-photos \
  storage/app/device-photos-order \
  storage/app/device-photos-links

find html/device-photos storage/app/device-photos-order storage/app/device-photos-links \
  -type d -exec chmod 2775 {} \;

find html/device-photos storage/app/device-photos-order storage/app/device-photos-links \
  -type f -exec chmod 664 {} \;
```

Optional ACL defaults:

```bash
setfacl -R -m u:librenms:rwx,g:librenms:rwx \
  html/device-photos storage/app/device-photos-order storage/app/device-photos-links

setfacl -R -d -m u:librenms:rwx,g:librenms:rwx \
  html/device-photos storage/app/device-photos-order storage/app/device-photos-links
```

### 4. Clear LibreNMS cache

```bash
cd /opt/librenms

sudo -u librenms php artisan view:clear
sudo -u librenms php artisan cache:clear
sudo -u librenms php artisan optimize:clear
```

### 5. Enable the plugin

Enable the plugin if required by your LibreNMS plugin setup.

After enabling, the menu entry should appear here:

```text
Overview -> Plugins -> Device Photos Overview
```

### 6. Syntax check

```bash
cd /opt/librenms

php -l app/Plugins/DevicePhoto/DeviceOverview.php
php -l app/Plugins/DevicePhoto/Menu.php
php -l app/Plugins/DevicePhoto/Page.php
php -l app/Plugins/DevicePhoto/Settings.php
php -l html/plugins/DevicePhoto/DevicePhoto.inc.php
```

Expected:

```text
No syntax errors detected
```

---

## Permissions

The plugin menu is visible to users with:

```text
global-read
```

Administrative actions are controlled by plugin settings.

Typical permission-controlled actions:

- Upload photos.
- Delete photos.
- Reorder photos.
- Link photos.
- Remove links.
- Assign orphaned photos.
- Delete orphaned photos.
- Generate thumbnails.
- Remove broken links.

Admins are allowed by default.

---

## Usage

### Open the global overview

```text
Overview -> Plugins -> Device Photos Overview
```

or:

```text
/plugin/DevicePhoto
```

### Open a device manager

From a device page, click the options button in the Device Photos box.

Direct URL:

```text
/plugin/DevicePhoto?device_id=<device_id>
```

Example:

```text
/plugin/DevicePhoto?device_id=42
```

---

## Device Photos Overview

The global overview shows:

- Devices with photos or links.
- Active files.
- Active size.
- Orphans.
- Deleted files.
- Deleted size.
- Broken links.
- Thumbnails.
- Missing thumbnails.

Problem counters turn red when the value is greater than zero:

- `orphans`
- `broken links`
- `missing thumbnails`

The table includes tooltips for:

- Device
- Owned photos
- Linked in
- Linked out
- Preview
- Actions

### Terms

#### Owned photos

Photos physically owned by the device.

Example:

```text
device-42-1.jpg
```

#### Linked in

Photos owned by another device but shown on this device.

#### Linked out

Photos owned by this device but shown on other devices.

---

## Device Photo Manager

The manager is used to manage photos for a single device.

Features:

- Upload one or more photos.
- Drag/drop photos.
- Append multiple drag/drop rounds before upload.
- Upload mixed file types, for example JPG and HEIC together.
- Reorder photos using drag and drop.
- Download photos.
- Delete photos.
- Link photos to another device.
- Add photos from another owner device.
- Remove links.
- Open full-size image modal.
- See `Photo taken` and `File date`.

### Upload status

The upload section shows:

- `upload_max_filesize`
- `post_max_size`
- `file_uploads`
- `HEIC/HEIF`

The webserver upload limit must also be high enough.

Examples:

```text
Nginx: client_max_body_size
Apache: LimitRequestBody
```

---

## Device overview widget

The normal LibreNMS device overview page shows a Device Photos box.

Features:

- Shows photos for the current device.
- Shows linked photos.
- Linked photos have a link indicator.
- Hovering the link indicator shows the owner device.
- Clicking a photo opens the full-size modal.
- Modal supports zoom and pan.
- Modal shows `Photo taken` and `File date` when available.

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

---

## Orphaned photos

An orphaned photo is an image file where the original LibreNMS Device ID no longer exists.

Example:

```text
html/device-photos/device-99999-1.jpg
```

If Device ID `99999` does not exist in LibreNMS, the photo is orphaned.

This can happen if:

- A device was deleted from LibreNMS.
- A device was restored with a different ID.
- Files were copied manually.
- Test files were left behind.

Available actions:

- Download.
- Assign to an existing device.
- Delete.

Delete means the file is moved to:

```text
html/device-photos/deleted/
```

It is not permanently removed.

---

## Broken links

A broken link is a saved photo link entry that points to a missing image file.

Example:

```json
[
    {
        "owner_device_id": 99998,
        "filename": "device-99998-1.jpg"
    }
]
```

If this file does not exist:

```text
html/device-photos/device-99998-1.jpg
```

the link is broken.

The Broken links section shows:

- Target device.
- Owner device.
- File.
- Action.

Labels:

```text
Missing device
```

The owner device no longer exists.

```text
Missing file
```

The linked image file no longer exists.

Action:

```text
Remove broken link
```

Removing a broken link only removes the JSON link entry. It does not delete any photo file.

---

## Thumbnails

Thumbnails are stored in:

```text
html/device-photos/thumbs/
```

The plugin uses thumbnails for previews when available.

Fallback behavior:

```text
If thumbnail exists:
    use thumbnail

If thumbnail is missing:
    use original image
```

The full-size image modal always opens the original image.

### Generate missing thumbnails

The global overview detects missing thumbnails.

If missing thumbnails are found, use:

```text
Generate missing thumbnails
```

This creates thumbnails for active photos that do not already have one. Existing thumbnails are not overwritten.

If GD is missing, thumbnail generation is skipped and original images are used as fallback.

---

## HEIC/HEIF upload conversion

HEIC and HEIF uploads are supported when ImageMagick with HEIC/HEIF support is available.

The plugin does not store HEIC/HEIF files as active photos.

Instead:

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

This keeps the rest of the plugin simple because all HEIC/HEIF uploads become JPG files after upload.

### Check HEIC/HEIF support

```bash
which magick
magick -list format 2>/dev/null | grep -Ei 'HEIC|HEIF'
```

Expected example:

```text
HEIC  HEIC  rw+  High Efficiency Image Format
HEIF  HEIC  rw+  High Efficiency Image Format
```

### EXIF after conversion

If ImageMagick preserves EXIF metadata, `Photo taken` should work after conversion.

You can test a converted image with:

```bash
exiftool html/device-photos/device-42-3.jpg | grep -Ei "Date/Time Original|Create Date|Modify Date"
```

`exiftool` is not required by the plugin, but it is useful for testing.

---

## Photo dates

The plugin shows two date concepts.

### Photo taken

`Photo taken` is read from EXIF metadata when available.

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

If no supported EXIF date exists, `Photo taken` is hidden.

### File date

`File date` is read from the file timestamp on the LibreNMS server.

This is based on `filemtime()`.

It may change if files are copied, restored or modified.

### Display format

Dates are formatted in the browser using the user's local browser/OS locale.

This means different users may see different formats.

Example:

```text
13.05.2026, 12:23
```

or:

```text
5/13/2026, 12:23 PM
```

This is expected behavior.

### Where dates are shown

- Device Photo Manager:
  - Under the small photo card, date only.
  - In the full-size modal, date and time.
- Device Photos Overview:
  - In the full-size modal.
- Device overview widget:
  - In the full-size modal.

---

## Backup and restore

### Backup

Recommended backup:

```bash
cd /opt/librenms

tar czf /root/DevicePhoto-backup-$(date +%F-%H%M).tar.gz \
  app/Plugins/DevicePhoto \
  html/plugins/DevicePhoto \
  html/device-photos \
  storage/app/device-photos-order \
  storage/app/device-photos-links
```

Check:

```bash
ls -lh /root/DevicePhoto-backup-*.tar.gz
```

### Restore

Example:

```bash
cd /opt/librenms

tar xzf /root/DevicePhoto-backup-YYYY-MM-DD-HHMM.tar.gz -C /opt/librenms

chown -R librenms:librenms \
  app/Plugins/DevicePhoto \
  html/plugins/DevicePhoto \
  html/device-photos \
  storage/app/device-photos-order \
  storage/app/device-photos-links
```

Clear cache:

```bash
sudo -u librenms php artisan view:clear
sudo -u librenms php artisan cache:clear
sudo -u librenms php artisan optimize:clear
```

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

### Clear LibreNMS cache

```bash
cd /opt/librenms

sudo -u librenms php artisan view:clear
sudo -u librenms php artisan cache:clear
sudo -u librenms php artisan optimize:clear
```

### List active photos

```bash
cd /opt/librenms

find html/device-photos -maxdepth 1 -type f -printf '%f %k KB\n' | sort
```

### List thumbnails

```bash
cd /opt/librenms

find html/device-photos/thumbs -maxdepth 1 -type f -printf '%f %k KB\n' | sort
```

### List deleted photos

```bash
cd /opt/librenms

find html/device-photos/deleted -maxdepth 1 -type f -printf '%f %k KB\n' | sort
```

### List deleted thumbnails

```bash
cd /opt/librenms

find html/device-photos/deleted/thumbs -maxdepth 1 -type f -printf '%f %k KB\n' | sort
```

### List link files

```bash
cd /opt/librenms

find storage/app/device-photos-links -maxdepth 1 -type f -printf '%f\n' | sort
```

### Check HEIC support

```bash
which magick
magick -list format 2>/dev/null | grep -Ei 'HEIC|HEIF'
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

### Thumbnail is missing

Use:

```text
Device Photos Overview -> Generate missing thumbnails
```

Also check PHP GD:

```bash
php -m | grep -i gd
```

### HEIC upload fails

Check ImageMagick:

```bash
which magick
magick -list format 2>/dev/null | grep -Ei 'HEIC|HEIF'
```

The manager page should show:

```text
HEIC/HEIF: Available
```

### Photo taken is missing

`Photo taken` is only shown if supported EXIF data exists.

Current EXIF support is for:

```text
jpg
jpeg
```

PNG and WebP normally show only `File date`.

HEIC/HEIF files are converted to JPG. If metadata is preserved during conversion, `Photo taken` should be shown.

### Broken links > 0

Open Device Photos Overview and scroll to Broken links.

Use:

```text
Remove broken link
```

This only removes the broken JSON link entry.

### Orphans > 0

Open Device Photos Overview and scroll to Orphaned photos.

You can:

- Download the photo.
- Assign it to an existing device.
- Delete it.

Delete moves it to the deleted folder.

---

## Security notes

- Do not allow arbitrary file types.
- The plugin validates image extensions.
- The plugin validates normal uploaded images with image checks.
- HEIC/HEIF files are converted server-side and stored as JPG.
- Deleted files are moved to a deleted folder, not permanently removed.
- Permissions should be configured so only trusted users can upload or delete photos.
- The webserver must not execute scripts from the image upload directory.

Recommended webserver behavior:

```text
html/device-photos/ should serve images only.
Do not execute scripts from this directory.
```

---

## Development notes

Current design choices:

- Photos are stored on disk, not in the database.
- Filenames use LibreNMS `device_id`.
- Links are stored as JSON.
- Ordering is stored as JSON.
- HEIC/HEIF uploads are converted to JPG.
- PNG is kept as PNG.
- WebP is kept as WebP.
- Thumbnails are optional and fail-safe.
- If thumbnail generation fails, original images are used as fallback.
- `Photo taken` is based on EXIF where supported.
- `File date` is based on file timestamp.

Known future improvement ideas:

- Refactor the image modal into a shared Blade partial.
- Add optional `exiftool` support for broader metadata reading.
- Add duplicate detection in drag/drop upload.
- Add restore UI for deleted photos.
- Add optional configurable thumbnail size.
- Add optional configurable max upload size.
- Add optional PNG-to-JPG conversion setting.
- Improve modal layout consistency across all views.

---

## Recommended test checklist

After installation or upgrade, test:

1. Open a device page with no photos.
2. Open Device Photo Manager for the device.
3. Upload JPG.
4. Upload HEIC if supported.
5. Confirm HEIC becomes JPG.
6. Confirm thumbnail is generated.
7. Confirm photo opens in full-size modal.
8. Confirm zoom and pan work.
9. Confirm `File date` appears.
10. Confirm `Photo taken` appears when EXIF exists.
11. Upload multiple files.
12. Drag/drop one file, then drag/drop another before upload.
13. Reorder photos.
14. Link a photo to another device.
15. Remove the link.
16. Create/test orphaned photo handling.
17. Create/test broken link handling.
18. Generate missing thumbnails.
19. Confirm plugin menu entry works.
20. Run PHP syntax checks.

---

## License

Add your preferred license here.

Example:

```text
MIT License
```

---

## Credits


