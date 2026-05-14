# LibreNMS Device Photos Plugin

A LibreNMS plugin for adding, viewing, linking and managing photos for devices.

The plugin adds a **Device Photos** box on the normal LibreNMS device overview page, a **Manage Device Photos** page for each device, and a global **Device Photos Overview** page for administration and maintenance.

> **Notice**
>
> This plugin was created and tested in a local LibreNMS environment. Use at your own risk.
> Test thoroughly before using it in production.

---

## Table of contents

- [Features](#features)
- [Terminology](#terminology)
- [Pages and navigation](#pages-and-navigation)
- [How the plugin works](#how-the-plugin-works)
- [File and directory structure](#file-and-directory-structure)
- [Runtime data directories](#runtime-data-directories)
- [Requirements](#requirements)
- [Optional features and dependencies](#optional-features-and-dependencies)
- [Installation](#installation)
- [Ownership and permissions](#ownership-and-permissions)
- [Usage](#usage)
- [Device Photos Overview](#device-photos-overview)
- [Manage Device Photos](#manage-device-photos)
- [Device overview widget](#device-overview-widget)
- [Shared photo viewer](#shared-photo-viewer)
- [Linked photos](#linked-photos)
- [Orphaned photos](#orphaned-photos)
- [Broken links](#broken-links)
- [Thumbnails](#thumbnails)
- [Missing and stale thumbnails](#missing-and-stale-thumbnails)
- [HEIC/HEIF upload conversion](#heicheif-upload-conversion)
- [Photo dates](#photo-dates)
- [Upload behavior](#upload-behavior)
- [Backup and restore](#backup-and-restore)
- [Maintenance commands](#maintenance-commands)
- [Troubleshooting](#troubleshooting)
- [Security notes](#security-notes)
- [Development notes](#development-notes)
- [Recommended test checklist](#recommended-test-checklist)

---

## Features

- Add photos to LibreNMS devices.
- Show device photos directly on the normal LibreNMS device overview page.
- Global overview of all device photos, links and maintenance status.
- Per-device photo management page.
- Upload one or more photos.
- Drag and drop upload.
- Multiple drag/drop rounds before upload. Newly dropped files are appended instead of replacing the current selection.
- Upload mixed file types in the same upload, for example JPG and HEIC together.
- Drag and drop ordering of owned photos.
- Thumbnail generation.
- Shared full-size image viewer used across all plugin views.
- Full-size image viewer with zoom and pan.
- Photo date information:
  - `Photo taken` from EXIF where available.
  - `File date` from the file timestamp on the LibreNMS server.
- Link photos between devices.
- Show linked photos on other devices.
- Detect orphaned photos.
- Assign orphaned photos to existing devices.
- Detect and remove broken photo links.
- Detect missing thumbnails.
- Detect and clean stale thumbnails.
- Soft-delete photos by moving them to a deleted folder.
- HEIC/HEIF upload support through automatic conversion to JPG when ImageMagick supports HEIC/HEIF.
- Plugin menu entry under LibreNMS:
  - `Overview -> Plugins -> Device Photos Overview`

---

## Terminology

### Device Photos

The general plugin name.

### Device Photos Overview

The global overview and maintenance page.

URL:

```text
/plugin/DevicePhoto
```

### Manage Device Photos

The per-device management page.

URL:

```text
/plugin/DevicePhoto?device_id=<device_id>
```

### Device Photos widget

The small photo box shown on the normal LibreNMS device overview page.

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

---

## Pages and navigation

### Device Photos Overview

LibreNMS menu:

```text
Overview -> Plugins -> Device Photos Overview
```

Direct URL:

```text
/plugin/DevicePhoto
```

This page shows photo library status, maintenance status, devices with photos or links, orphaned photos, broken links and thumbnail maintenance.

### Manage Device Photos

Direct URL:

```text
/plugin/DevicePhoto?device_id=<device_id>
```

Example:

```text
/plugin/DevicePhoto?device_id=42
```

This page is used to manage photos for one device.

### Device Photos settings

Direct URL:

```text
/plugin/settings/DevicePhoto
```

---

## How the plugin works

The plugin stores photos as files on disk. It does not store photos in the LibreNMS database.

Photos are named by LibreNMS `device_id`.

Example:

```text
device-42-1.jpg
device-42-2.jpg
device-42-3.webp
```

This makes photo filenames stable if the device hostname, sysName or display name changes.

The plugin uses:

- File storage for photos.
- JSON files for ordering.
- JSON files for links between devices.
- File timestamps for `File date`.
- EXIF metadata for `Photo taken` when available.
- Generated thumbnails as cache/derived files.

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
        ├── settings.blade.php
        └── partials/
            ├── footer.blade.php
            └── photo-modal.blade.php

html/plugins/DevicePhoto/
└── DevicePhoto.inc.php
```

### Main responsibilities

#### `DeviceOverview.php`

LibreNMS device overview hook. Provides the Device Photos widget on a normal LibreNMS device page.

#### `device-overview.blade.php`

Renders the Device Photos widget on the normal LibreNMS device overview page.

Includes:

- Thumbnail display.
- Linked photo indicator.
- Manage device photos button.
- Shared full-size photo viewer through the shared modal partial.

#### `Menu.php`

LibreNMS plugin menu hook. Adds the menu entry:

```text
Overview -> Plugins -> Device Photos Overview
```

#### `menu.blade.php`

Renders the plugin menu link.

#### `Page.php`

Backend/data provider for Device Photos Overview and Manage Device Photos.

It builds arrays for:

- Active photos.
- Deleted photos.
- Orphaned photos.
- Broken links.
- Linked photos.
- Thumbnail status.
- Stale thumbnail status.
- Photo dates.
- Upload status.
- HEIC/HEIF conversion status.

#### `page.blade.php`

Main UI for:

- Device Photos Overview.
- Manage Device Photos.
- Upload form.
- Drag/drop upload.
- Photo ordering.
- Photo linking.
- Orphaned photo handling.
- Broken link handling.
- Thumbnail maintenance.
- Shared photo viewer include.
- Footer include.

#### `partials/photo-modal.blade.php`

Shared full-size photo viewer.

Used by:

- Device Photos Overview.
- Manage Device Photos.
- Device Photos widget on the normal LibreNMS device overview page.

Provides:

- Modal HTML.
- Modal CSS.
- JavaScript click handler.
- Zoom.
- Pan.
- Reset.
- Close with button, backdrop or Escape.
- Date footer in the modal.

Photos open the shared viewer when an element has:

```html
data-device-photo-preview-src="..."
```

Optional date attributes:

```html
data-device-photo-taken="..."
data-device-photo-file-date="..."
```

#### `partials/footer.blade.php`

Shared footer for plugin pages.

Contains plugin credit and GitHub link.

#### `Settings.php`

Backend/data provider for plugin settings.

#### `settings.blade.php`

Settings UI.

#### `html/plugins/DevicePhoto/DevicePhoto.inc.php`

Action handler for POST requests.

Handles:

- Upload.
- HEIC/HEIF conversion.
- Delete.
- Reorder.
- Link photo.
- Remove link.
- Assign orphaned photo.
- Delete orphaned photo.
- Generate missing thumbnails.
- Clean stale thumbnails.
- Remove broken link.

---

## Runtime data directories

These directories contain runtime data and should normally not be committed to Git.

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

Thumbnails are cache/derived files.

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

When a photo is deleted, its thumbnail is moved here if it exists.

### Photo order

```text
storage/app/device-photos-order/
```

Example:

```text
storage/app/device-photos-order/device-42.json
```

This stores the manual photo order for a device.

### Photo links

```text
storage/app/device-photos-links/
```

Example:

```text
storage/app/device-photos-links/device-43.json
```

This stores photos shown on a device but owned by another device.

Example:

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
- PHP with:
  - GD extension for thumbnail generation.
  - EXIF extension for reading `Photo taken` from JPG/JPEG images.
- Writable runtime directories for the PHP/LibreNMS web process.
- Webserver access to serve files from:
  - `html/device-photos/`

Recommended PHP modules:

```text
gd
exif
```

Check:

```bash
php -m | grep -Ei 'gd|exif'
```

---

## Optional features and dependencies

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

If not available, HEIC/HEIF upload will fail with a clear error.

### exiftool

`exiftool` is not required by the plugin.

It is useful for testing metadata:

```bash
exiftool html/device-photos/device-42-1.jpg | grep -Ei "Date/Time Original|Create Date|Modify Date"
```

---

## Installation

> Adjust paths if your LibreNMS installation is not located in `/opt/librenms`.

### 1. Copy plugin files

Copy the plugin into the LibreNMS plugin directories:

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

### 2. Create runtime data directories

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

chown -R librenms:librenms   app/Plugins/DevicePhoto   html/plugins/DevicePhoto   html/device-photos   storage/app/device-photos-order   storage/app/device-photos-links
```

Alternative example if your PHP/webserver runs as `www-data`:

```bash
cd /opt/librenms

chown -R www-data:www-data   html/device-photos   storage/app/device-photos-order   storage/app/device-photos-links
```

Set directory and file permissions:

```bash
cd /opt/librenms

find html/device-photos storage/app/device-photos-order storage/app/device-photos-links   -type d -exec chmod 2775 {} \;

find html/device-photos storage/app/device-photos-order storage/app/device-photos-links   -type f -exec chmod 664 {} \;
```

The `2` in `2775` enables the setgid bit so new files and folders normally inherit the group from the parent directory.

Optional ACL defaults if your environment uses ACLs:

```bash
setfacl -R -m u:librenms:rwx,g:librenms:rwx html/device-photos storage/app/device-photos-order storage/app/device-photos-links
setfacl -R -d -m u:librenms:rwx,g:librenms:rwx html/device-photos storage/app/device-photos-order storage/app/device-photos-links
```

Adjust `librenms` in the ACL example if your PHP/web process uses another user/group.

### 4. Check which user PHP runs as

Examples:

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

### 7. Syntax check

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

## Ownership and permissions

Uploaded files are created by the PHP/webserver process.

After upload or conversion, the plugin sets file mode to:

```text
664
```

Example:

```text
-rw-rw-r--
```

The plugin does not hardcode or force ownership to `librenms`.

This is intentional. It makes the plugin more portable.

Correct ownership depends on your environment:

```text
librenms:librenms
www-data:www-data
apache:apache
nginx:nginx
custom-user:custom-group
```

The important rule is:

```text
The PHP/LibreNMS web process must be able to read and write:
- html/device-photos
- html/device-photos/thumbs
- html/device-photos/deleted
- html/device-photos/deleted/thumbs
- storage/app/device-photos-order
- storage/app/device-photos-links
```

Check uploaded files:

```bash
cd /opt/librenms

ls -lh html/device-photos | head
ls -lh html/device-photos/thumbs | head
```

If uploads work but later actions fail, check:

- file owner
- file group
- directory permissions
- setgid bit
- ACLs
- PHP-FPM pool user/group
- webserver user/group

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

### Open Manage Device Photos for a device

From a normal LibreNMS device page, click the options button in the Device Photos widget.

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

- Photo library status.
- Maintenance status.
- Devices with photos or links.
- Orphaned photos.
- Broken links.
- Thumbnail maintenance.

### Photo library panel

Shows:

- Devices
- Active photos
- Active size
- Deleted photos
- Deleted size

Size counters do not include thumbnails.

### Maintenance panel

Shows health/cleanup status.

If no issues are found, it shows:

```text
No maintenance issues found
```

If issues are found, it only shows the counters that need attention, for example:

```text
1 missing thumbnails
```

Possible maintenance counters:

- Orphans
- Broken links
- Missing thumbnails
- Stale thumbnails

### Filter

The overview page includes a filter field:

```text
Filter by Device ID, device name or filename
```

### Devices table

Columns:

- Device
- Owned photos
- Linked in
- Linked out
- Preview
- Actions

Header tooltips explain the meaning of each column.

---

## Manage Device Photos

The per-device page is used to manage photos for one device.

Features:

- Upload photos.
- Drag/drop photos.
- Append multiple drag/drop rounds before upload.
- Upload mixed file types, for example JPG and HEIC together.
- Reorder owned photos using drag and drop.
- Download photos.
- Delete photos.
- Link photos to another device.
- Add linked photos from another owner device.
- Remove links.
- Open photos in the shared full-size viewer.
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

The normal LibreNMS device overview page shows a Device Photos widget.

Features:

- Shows owned photos for the current device.
- Shows linked photos.
- Linked photos have a link indicator.
- Hovering the link indicator shows the owner device.
- Clicking a photo opens the shared full-size photo viewer.
- The viewer supports zoom and pan.
- The viewer shows `Photo taken` and `File date` when available.
- The options button opens Manage Device Photos for the device.

---

## Shared photo viewer

The shared viewer is implemented in:

```text
app/Plugins/DevicePhoto/resources/views/partials/photo-modal.blade.php
```

It is included by:

```text
app/Plugins/DevicePhoto/resources/views/page.blade.php
app/Plugins/DevicePhoto/resources/views/device-overview.blade.php
```

Any element with this attribute opens the shared viewer:

```html
data-device-photo-preview-src="URL_TO_ORIGINAL_PHOTO"
```

Optional date attributes:

```html
data-device-photo-taken="2026-05-05T22:03:02+02:00"
data-device-photo-file-date="2026-05-13T12:23:00+02:00"
```

The viewer supports:

- Click to open.
- Zoom in/out buttons.
- Mouse wheel zoom.
- Pan/drag.
- Reset.
- Close button.
- Backdrop close.
- Escape key close.
- Date footer.

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

### Orphaned thumbnails

If an orphaned photo has a thumbnail, the thumbnail follows the original photo.

Assign orphaned photo:

```text
html/device-photos/device-99999-1.jpg
-> html/device-photos/device-62-5.jpg

html/device-photos/thumbs/device-99999-1.jpg
-> html/device-photos/thumbs/device-62-5.jpg
```

Delete orphaned photo:

```text
html/device-photos/device-99999-1.jpg
-> html/device-photos/deleted/...

html/device-photos/thumbs/device-99999-1.jpg
-> html/device-photos/deleted/thumbs/...
```

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

The full-size image viewer always opens the original image.

### Generate missing thumbnails

The global overview detects missing thumbnails.

If missing thumbnails are found, use:

```text
Generate missing thumbnails
```

This creates thumbnails for active photos that do not already have one. Existing thumbnails are not overwritten unless the related function refreshes a specific thumbnail after a rename/assign operation.

If GD is missing, thumbnail generation is skipped and original images are used as fallback.

---

## Missing and stale thumbnails

### Missing thumbnails

A missing thumbnail means:

```text
Original active photo exists
Thumbnail does not exist
```

Example:

```text
html/device-photos/device-42-1.jpg exists
html/device-photos/thumbs/device-42-1.jpg missing
```

Use:

```text
Generate missing thumbnails
```

### Stale thumbnails

A stale thumbnail means:

```text
Thumbnail exists
Original active photo does not exist
```

Example:

```text
html/device-photos/thumbs/device-99999-1.jpg exists
html/device-photos/device-99999-1.jpg missing
```

Use:

```text
Clean stale thumbnails
```

This only deletes files from:

```text
html/device-photos/thumbs/
```

It does not delete original photos.

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

- Manage Device Photos:
  - Under the small owned-photo card, date-only.
  - In the full-size viewer, date and time.
- Device Photos Overview:
  - In the full-size viewer.
- Device Photos widget on the normal LibreNMS device page:
  - In the full-size viewer.

---

## Upload behavior

Allowed upload types:

```text
jpg
jpeg
png
webp
heic
heif
```

Upload behavior:

```text
jpg/jpeg/png/webp -> stored as uploaded
heic/heif        -> converted to jpg
```

Uploaded and converted files are stored as:

```text
device-<device_id>-<number>.<extension>
```

Examples:

```text
device-42-1.jpg
device-42-2.png
device-42-3.webp
device-42-4.jpg   # from HEIC/HEIF conversion
```

The plugin validates normal image uploads and checks HEIC/HEIF conversion availability before converting HEIC/HEIF files.

---

## Backup and restore

### Backup

Recommended backup:

```bash
cd /opt/librenms

tar czf /root/DevicePhoto-backup-$(date +%F-%H%M).tar.gz   app/Plugins/DevicePhoto   html/plugins/DevicePhoto   html/device-photos   storage/app/device-photos-order   storage/app/device-photos-links
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
```

Then set ownership to match your LibreNMS/PHP environment.

Common LibreNMS example:

```bash
chown -R librenms:librenms   app/Plugins/DevicePhoto   html/plugins/DevicePhoto   html/device-photos   storage/app/device-photos-order   storage/app/device-photos-links
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

find app/Plugins/DevicePhoto html/plugins/DevicePhoto   -type f \( -name "*.php" -o -name "*.blade.php" \)   -printf '%p\n' | sort
```

Expected structure:

```text
app/Plugins/DevicePhoto/DeviceOverview.php
app/Plugins/DevicePhoto/Menu.php
app/Plugins/DevicePhoto/Page.php
app/Plugins/DevicePhoto/Settings.php
app/Plugins/DevicePhoto/resources/views/device-overview.blade.php
app/Plugins/DevicePhoto/resources/views/menu.blade.php
app/Plugins/DevicePhoto/resources/views/page.blade.php
app/Plugins/DevicePhoto/resources/views/partials/footer.blade.php
app/Plugins/DevicePhoto/resources/views/partials/photo-modal.blade.php
app/Plugins/DevicePhoto/resources/views/settings.blade.php
html/plugins/DevicePhoto/DevicePhoto.inc.php
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

### Check for stale thumbnails manually

```bash
cd /opt/librenms

comm -23   <(find html/device-photos/thumbs -maxdepth 1 -type f -printf '%f\n' | sort)   <(find html/device-photos -maxdepth 1 -type f -printf '%f\n' | sort)
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

Nginx:

```text
client_max_body_size
```

Apache:

```text
LimitRequestBody
```

Also check PHP:

```text
upload_max_filesize
post_max_size
```

### Upload fails because of permissions

Check that runtime directories are writable by your PHP/LibreNMS web process.

```bash
cd /opt/librenms

ls -ld html/device-photos
ls -ld html/device-photos/thumbs
ls -ld storage/app/device-photos-order
ls -ld storage/app/device-photos-links
```

Check PHP-FPM/webserver user:

```bash
ps aux | grep -E 'php-fpm|apache|nginx' | head
grep -R "^[[:space:]]*user\|^[[:space:]]*group" /etc/php/*/fpm/pool.d/*.conf
```

### Thumbnail is missing

Use the global overview:

```text
Device Photos Overview -> Generate missing thumbnails
```

Also check PHP GD:

```bash
php -m | grep -i gd
```

### Stale thumbnails found

Use the global overview:

```text
Device Photos Overview -> Clean stale thumbnails
```

This removes thumbnails without matching original active photos.

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

If it says `Not available`, install ImageMagick with HEIC/HEIF support.

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
- The webserver must be configured so PHP code cannot execute from the image upload directory.

Recommended webserver behavior:

```text
html/device-photos/ should serve images only.
Do not execute scripts from this directory.
```

---

## Development notes

### Current design choices

- Photos are stored on disk, not in the database.
- Filenames use LibreNMS `device_id`.
- Links are stored as JSON.
- Ordering is stored as JSON.
- HEIC/HEIF uploads are converted to JPG.
- PNG is kept as PNG.
- WebP is kept as WebP.
- Thumbnails are optional and fail-safe.
- If thumbnail generation fails, original images are used as fallback.
- Missing thumbnails can be generated from the overview page.
- Stale thumbnails can be cleaned from the overview page.
- `Photo taken` is based on EXIF where supported.
- `File date` is based on file timestamp.
- The photo viewer is shared between plugin pages and the normal device overview widget.
- The footer is shared between plugin pages.

### Why owner is part of the filename

Current filename format:

```text
device-<device_id>-<number>.<extension>
```

Example:

```text
device-42-1.jpg
```

This makes files easy to understand and repair manually.

Tradeoff:

- Moving ownership to another device requires renaming the original file and thumbnail.
- Link JSON entries must be updated if ownership is moved.
- This is simpler than a full metadata/index database.

### Runtime data should not be committed

Recommended `.gitignore` entries for a GitHub repository:

```gitignore
html/device-photos/
storage/app/device-photos-order/
storage/app/device-photos-links/
```

If your repository only contains the plugin source directories, do not include local photos or runtime JSON data.

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
17. Create/test orphaned photo handling.
18. Assign an orphaned photo to a device.
19. Delete an orphaned photo.
20. Create/test broken link handling.
21. Generate missing thumbnails.
22. Clean stale thumbnails.
23. Confirm plugin menu entry works.
24. Open plugin settings.
25. Run PHP syntax checks.

---

## License

Add your preferred license here.

Example:

```text
MIT License
```

---

## Credits

Created by Wizball


