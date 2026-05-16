# DevicePhoto migration map

This document tracks the migration from the legacy LibreNMS local plugin endpoint to the Composer/package structure.

## Legacy endpoint

Current legacy endpoint:

- `html/plugins/DevicePhoto/DevicePhoto.inc.php`

Current size:

- about 1577 lines

This file must not be removed until the Composer package version has feature parity.

## Existing package-preparation files

- `composer.json`
- `src/DevicePhotoServiceProvider.php`
- `config/device-photo.php`
- `hooks/`
- `resources/views/`

## GET image actions

The legacy endpoint currently supports:

- `action=photo`
- `action=thumb`
- `action=deleted_photo`
- `action=deleted_thumb`

Security requirements:

- `photo` and `thumb` require `global-read`
- `deleted_photo` and `deleted_thumb` require `delete_roles`
- strict filename validation
- realpath base-directory check
- private cache header
- `X-Content-Type-Options: nosniff`

## Package image route

A package test route has been added for image serving:

- `GET plugin/device-photo-package/image`

This route is handled by:

- `src/Http/Controllers/PhotoController.php`

Supported actions:

- `action=photo`
- `action=thumb`
- `action=deleted_photo`
- `action=deleted_thumb`

Important:

- This is currently a package test route only.
- Existing views still use the legacy endpoint `plugin/v1/DevicePhoto`.
- The legacy endpoint must not be removed until the package route has been tested.

## Package image route test status

Tested manually in LibreNMS with the Composer/path package installed.

Working:

- `action=photo` returns the original image
- `action=thumb` returns the thumbnail
- invalid extension returns `400 Bad Request`
- invalid filename returns `400 Bad Request`
- unknown action returns `405 Method Not Allowed`

The route required Laravel `web` middleware to access the LibreNMS authenticated session.

Current test route:

- `GET plugin/device-photo-package/image`

The legacy endpoint is still in use by existing views:

- `plugin/v1/DevicePhoto`

## Package page and hook test status

Tested manually in LibreNMS with both the legacy local plugin and the Composer/path package enabled.

Working:

- `/plugin/device-photo` loads the package plugin page
- package hooks are published using LibreNMS plugin interfaces
- package page links use `/plugin/device-photo`
- package image URLs use `/plugin/device-photo-package/image`
- device overview images are served by the package image controller
- legacy local plugin remains available as `/plugin/DevicePhoto`

Important:

- The legacy local plugin must remain enabled for now.
- Existing package POST forms still use the legacy endpoint `/plugin/v1/DevicePhoto`.
- Do not disable the legacy `DevicePhoto` plugin until package POST controllers have feature parity.

## POST-only state-changing actions

All state-changing actions must remain POST-only.

Current POST actions:

- `upload`
- `delete`
- `set_photo_taken`
- `add_link`
- `remove_link`
- `remove_outgoing_link`
- `add_incoming_link`
- `save_order`
- `assign_orphan_photo`
- `delete_orphan_photo`
- `remove_broken_link`
- `generate_missing_thumbnails`
- `clean_stale_thumbnails`

## Permission requirements

Current behavior:

- normal viewing requires `global-read`
- upload actions require `upload_roles`
- delete actions require `delete_roles`
- reorder actions require `reorder_roles`
- admin always has access
- roles default to `admin` if settings are missing or invalid

## Runtime storage

Current storage paths:

- `storage/app/device-photos`
- `storage/app/device-photos/thumbs`
- `storage/app/device-photos/deleted`
- `storage/app/device-photos/deleted/thumbs`
- `storage/app/device-photos-order`
- `storage/app/device-photos-links`

## Validation/security behavior to preserve

- strict device-photo filename validation
- basename handling
- realpath directory containment check
- MIME-vs-extension validation on upload
- image pixel limit
- upload byte limit
- HEIC/HEIF conversion availability check
- HEIC/HEIF conversion to JPEG
- JSON writes with `LOCK_EX`
- deleted photos moved, not permanently deleted
- thumbnails moved with deleted photos
- stale links removed when photos are deleted
- stale thumbnails can be cleaned
- missing thumbnails can be generated
- orphaned photos can be assigned or moved to deleted
- linked photos are stored as JSON per target device
- photo order is stored as JSON per device

## Suggested package split

Future package structure should probably split the legacy endpoint into:

- `src/Http/Controllers/PhotoController.php`
  - GET photo/thumb/deleted image serving

- `src/Http/Controllers/ActionController.php`
  - POST actions

- `src/Services/PhotoStorageService.php`
  - paths, filename validation, list photos

- `src/Services/PhotoPermissionService.php`
  - role handling and action permission checks

- `src/Services/ThumbnailService.php`
  - create thumbnails, stale cleanup, missing thumbnail generation

- `src/Services/PhotoUploadService.php`
  - upload validation and storage

- `src/Services/PhotoLinkService.php`
  - linked photo JSON handling

- `src/Services/PhotoOrderService.php`
  - order JSON handling

- `src/Services/PhotoMetadataService.php`
  - photo taken / EXIF handling

## Package services created

The following package services have been created as migration building blocks:

- `src/Services/PhotoPathService.php`
  - storage paths for photos, thumbnails, deleted photos, order JSON and links JSON

- `src/Services/PhotoFilenameService.php`
  - strict filename validation, content type detection and next filename generation

- `src/Services/PhotoPermissionService.php`
  - role handling, admin override and action permission checks

- `src/Services/PhotoSettingsService.php`
  - package and legacy plugin settings lookup

- `src/Services/JsonFileService.php`
  - JSON read/write helpers with `LOCK_EX`

- `src/Services/PhotoOrderService.php`
  - photo ordering JSON handling

- `src/Services/PhotoLinkService.php`
  - linked photo JSON handling

- `src/Services/PhotoImageService.php`
  - ImageMagick HEIC/HEIF checks, HEIC conversion, thumbnail generation and stale thumbnail cleanup

- `src/Services/PhotoMetadataService.php`
  - exiftool availability, photo taken parsing and EXIF date writing

## Remaining legacy POST forms in package views

The package views still contain POST forms pointing to the legacy endpoint:

- `/plugin/v1/DevicePhoto`

Current actions found in `resources/views/page.blade.php`:

- `generate_missing_thumbnails`
- `clean_stale_thumbnails`
- `remove_link`
- `remove_outgoing_link`
- `assign_orphan_photo`
- `delete_orphan_photo`
- `remove_broken_link`
- `upload`
- `save_order`
- `add_link`
- `delete`
- `add_incoming_link`
- `set_photo_taken`

Recommended migration order:

1. `save_order`
2. `remove_link` and `remove_outgoing_link`
3. `add_link` and `add_incoming_link`
4. `clean_stale_thumbnails` and `generate_missing_thumbnails`
5. `set_photo_taken`
6. `delete`
7. `assign_orphan_photo`, `delete_orphan_photo` and `remove_broken_link`
8. `upload`

Important:

- Do not switch all POST forms at once.
- Migrate and test one action group at a time.
- Keep the legacy local `DevicePhoto` plugin enabled until all package POST actions have feature parity.

## Package save_order action test status

The first package POST action has been migrated and tested.

Migrated action:

- `save_order`

Package route:

- `POST plugin/device-photo-package/action`

Controller:

- `src/Http/Controllers/ActionController.php`

Working:

- photo order can be changed from `/plugin/device-photo?device_id=<id>`
- save order redirects back to `/plugin/device-photo`
- order persists after refresh
- only the `save_order` form has been moved to the package action route

Still legacy:

- all other POST actions still use `/plugin/v1/DevicePhoto`

Important:

- keep the legacy local `DevicePhoto` plugin enabled
- continue migrating one POST action group at a time

## Package link removal action test status

The next package POST action group has been migrated and tested.

Migrated actions:

- `remove_link`
- `remove_outgoing_link`

Package route:

- `POST plugin/device-photo-package/action`

Controller:

- `src/Http/Controllers/ActionController.php`

Working:

- linked photos can be removed from `/plugin/device-photo`
- outgoing linked photo references can be removed
- original photo files are not deleted
- actions redirect back to `/plugin/device-photo`
- `return_to=overview` is honored for overview actions

Still legacy:

- `generate_missing_thumbnails`
- `clean_stale_thumbnails`
- `assign_orphan_photo`
- `delete_orphan_photo`
- `remove_broken_link`
- `upload`
- `add_link`
- `delete`
- `add_incoming_link`
- `set_photo_taken`

Important:

- keep the legacy local `DevicePhoto` plugin enabled
- continue migrating one POST action group at a time

## Migration rule

Do not remove or modify the legacy endpoint until the package route/controller version has been tested against the same actions.

Feature parity must be checked action by action.