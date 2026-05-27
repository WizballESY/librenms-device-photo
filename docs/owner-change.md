# Change photo owner / Move to another device

This document describes how the Device Photos plugin handles changing the owner of an existing owned photo.

The feature is also referred to as:

- Change photo owner
- Move photo to another device
- Move to another device

The operation is intentionally conservative because one photo can affect several pieces of plugin state:

- the original photo file
- the thumbnail file
- the owner device
- linked-photo JSON files
- mixed photo order JSON files

## Terminology

### Owned photo

An owned photo is a physical photo file whose filename starts with the owning device ID.

Example:

~~~text
device-108-1.jpg
~~~

This means device `108` owns the photo.

Owned photos are stored in:

~~~text
storage/app/device-photos/
~~~

Thumbnails for owned photos are stored in:

~~~text
storage/app/device-photos/thumbs/
~~~

### Linked photo

A linked photo is a reference from one device to a photo owned by another device.

Links are stored as JSON files in:

~~~text
storage/app/device-photos-links/
~~~

Example link entry:

~~~json
{
    "owner_device_id": 109,
    "filename": "device-109-7.jpg"
}
~~~

This means the target device displays a photo owned by device `109`.

### Mixed order keys

The plugin supports mixed ordering of owned and linked photos.

Owned photo order key:

~~~text
device-108-1.jpg
~~~

Linked photo order key:

~~~text
linked:109:device-109-7.jpg
~~~

Order files are stored in:

~~~text
storage/app/device-photos-order/
~~~

Example:

~~~text
storage/app/device-photos-order/device-108.json
~~~

## What "move to another device" means

Moving a photo to another device changes the owner of the original photo.

Example:

~~~text
Before:
device-109-7.jpg is owned by device 109

Move to:
device 108

After:
device-108-1.jpg is owned by device 108
~~~

The physical file is renamed to match the new owner device ID and the next available photo number for that device.

The old owner no longer owns the photo.

## What happens during owner change

The backend performs the operation in a strict order.

### 1. Validate everything first

Before any file is moved, the action validates:

- current device exists
- user has permission through `upload_roles`
- filename belongs to the current owner device
- original photo file exists
- target device exists
- target device is not the same as the current owner device
- a safe new target filename can be generated

### 2. Find the next available filename on the target device

The target filename is generated from the target device ID and next available number.

Example:

~~~text
device-108-1.jpg
device-108-2.jpg
device-108-3.jpg
~~~

If `device-108-1.jpg` already exists, the next free number is used.

### 3. Rename the original photo first

The original photo file is renamed before links, thumbnails or order files are updated.

Example:

~~~text
storage/app/device-photos/device-109-7.jpg
~~~

becomes:

~~~text
storage/app/device-photos/device-108-1.jpg
~~~

This is the most important safety rule.

If this rename fails, the operation stops immediately.

When rename fails:

- links are not updated
- order files are not updated
- thumbnail files are not updated
- the original state is left untouched as far as possible

### 4. Move or regenerate the thumbnail

After the original file has been renamed successfully, the plugin moves the existing thumbnail if it exists.

Example:

~~~text
storage/app/device-photos/thumbs/device-109-7.jpg
~~~

becomes:

~~~text
storage/app/device-photos/thumbs/device-108-1.jpg
~~~

The plugin then creates or refreshes the thumbnail for the new filename if possible.

Thumbnails are treated as cache. The original photo is the source of truth.

### 5. Update linked-photo JSON files

Existing links to the moved photo are updated automatically.

Example:

~~~json
{
    "owner_device_id": 109,
    "filename": "device-109-7.jpg"
}
~~~

becomes:

~~~json
{
    "owner_device_id": 108,
    "filename": "device-108-1.jpg"
}
~~~

This means other devices that already linked to the photo keep their link. They do not need to recreate the link manually.

## Link behavior

### Existing links from other devices are preserved

If another device links to the moved photo, the link is updated to the new owner and filename.

Example:

~~~text
Before:
Device 50 links to linked:109:device-109-7.jpg

After moving photo to device 108:
Device 50 links to linked:108:device-108-1.jpg
~~~

### Target device self-link is removed

A special case exists when the target device already links to the photo before the move.

Example:

~~~text
Before:
Device 108 has linked:109:device-109-7.jpg
Device 109 owns device-109-7.jpg
~~~

When the photo is moved to device 108, the link would become a self-link:

~~~text
linked:108:device-108-1.jpg
~~~

That is not useful because device 108 now owns the photo.

So the plugin removes the self-link and converts the order position to an owned photo entry:

~~~text
device-108-1.jpg
~~~

This preserves the photo position in the target device's mixed order.

## Order behavior

### Old owner order

The old owner loses the owned photo entry.

Example:

~~~text
device-109-7.jpg
~~~

is removed from:

~~~text
storage/app/device-photos-order/device-109.json
~~~

### Target device order

If the target device already had the photo as a linked photo, that linked order key is converted to an owned photo key in the same position.

Example:

~~~text
Before:
linked:109:device-109-7.jpg
linked:109:device-109-4.jpg

After:
device-108-1.jpg
linked:109:device-109-4.jpg
~~~

If the target device did not already link to the photo, the moved photo is appended to the target device's owned photo order.

### Other linked devices

Other devices that linked to the old owner filename have their order keys updated.

Example:

~~~text
Before:
linked:109:device-109-7.jpg

After:
linked:108:device-108-1.jpg
~~~

## Old owner behavior

The old owner does not automatically get a new link back to the moved photo.

This is intentional.

Moving a photo means:

- the target device becomes the owner
- the old owner no longer owns the photo
- the old owner does not automatically keep showing the photo

A future UI option may add behavior such as:

~~~text
Keep this photo linked on the current device
~~~

But that should be an explicit user choice, not hidden automatic behavior.

## Safety rules

The most important safety rule is:

~~~text
Do not update links or order until the original file rename has succeeded.
~~~

The current backend follows this rule.

The operation only updates thumbnail, links and order after:

- the source file was renamed
- the target file exists

## Failure behavior

If validation fails, the action returns an error status such as:

~~~text
device_not_found
permission_denied
invalid_filename
not_found
invalid_target_device
same_target_device
owner_change_failed
~~~

If the original file rename fails, the action returns:

~~~text
owner_change_failed
~~~

and does not update links or order.

## Tested scenario

The backend was tested with device `109` as the original owner and device `108` as the target device.

Before the move:

~~~text
Device 109 owned:
device-109-7.jpg

Device 108 linked to:
linked:109:device-109-7.jpg
linked:109:device-109-4.jpg
linked:109:device-109-6.jpg
~~~

The photo was moved from device `109` to device `108`.

After the move:

~~~text
device-109-7.jpg was gone
device-108-1.jpg existed
thumbs/device-109-7.jpg was gone
thumbs/device-108-1.jpg existed
~~~

Device 108 order became:

~~~json
[
    "device-108-1.jpg",
    "linked:109:device-109-4.jpg",
    "linked:109:device-109-6.jpg"
]
~~~

Device 109 order no longer contained:

~~~text
device-109-7.jpg
~~~

Device 108 links no longer contained a self-link to the moved photo, but kept its other links to device 109 photos.

## Implementation notes

Relevant backend pieces:

~~~text
ActionController::changePhotoOwner()
ActionController::nextAvailablePhotoFilename()
ActionController::linkedTargetDeviceIdsForPhoto()
ActionController::pruneOrderForDevice()
PhotoLinkService::updateFilenameReferences()
PhotoOrderService::replaceKey()
~~~

`PhotoOrderService::replaceKey()` is used to preserve mixed-order positions when a linked photo key must become another linked key or an owned key.

Examples:

~~~text
linked:109:device-109-7.jpg
~~~

can become:

~~~text
linked:108:device-108-1.jpg
~~~

or, for target self-link conversion:

~~~text
device-108-1.jpg
~~~

## Current limitations

There is currently no UI button for this feature.

The backend action exists, but the user-facing form/button still needs to be added.

The old owner does not automatically receive a link back to the moved photo.

Live progress is not shown for this operation.
