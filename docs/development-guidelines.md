# Development guidelines

This document describes project conventions for the LibreNMS Device Photos plugin.

The goal is to keep changes small, predictable and easy to review.

## Change workflow

Use small controlled commits.

Preferred workflow:

~~~text
1. Patch one focused change
2. Review git diff
3. Run PHP lint
4. Sync to test LibreNMS install
5. Clear caches
6. Test in browser or CLI
7. Commit
8. Push
~~~

Avoid combining unrelated changes in the same commit.

Good examples:

~~~text
Add backend owner change action
Document photo owner change behavior
Add owner change UI for owned photos
~~~

Avoid commits that mix backend behavior, UI redesign and documentation unless the change is very small.

## CSS and Blade rules

All new CSS must live in:

~~~text
resources/views/partials/styles.blade.php
~~~

Do not add new local `<style>` blocks in Blade templates.

Do not add new inline styles for new features unless there is a strong reason and it is discussed first.

Prefer scoped reusable classes.

Good:

~~~html
<div class="device-photo-owner-change-current-file">
~~~

Avoid:

~~~html
<div style="font-size: 12px;">
~~~

Existing inline styles may still exist in older code. Do not expand that pattern when adding new functionality.

## Page scope classes

Use page scope classes when styling page-specific behavior.

Known page classes:

~~~text
device-photo-overview-page
device-photo-manage-page
device-photo-deleted-page
~~~

Prefer selectors scoped to the plugin or page context.

Good:

~~~css
.device-photo-manage-page .device-photo-target-suggestions {
    ...
}
~~~

Avoid broad global selectors that may affect LibreNMS core UI.

## Owned and linked photo cards

Owned photo card partial:

~~~text
resources/views/partials/manager-owned-photo-card.blade.php
~~~

Linked photo card partial:

~~~text
resources/views/partials/manager-linked-photo-card.blade.php
~~~

Rules:

- Owned-photo-only actions belong only in the owned photo card.
- Linked-photo-only actions belong only in the linked photo card.
- Do not add owner actions to linked photo cards unless the action is explicitly meant for linked photos.
- Keep permanent controls on cards minimal.
- Prefer modal dialogs or collapsible advanced sections for complex actions.

Examples of owned-photo actions:

~~~text
Download
Set photo taken
Link this photo to another device
Move to another device
Delete
~~~

Examples of linked-photo actions:

~~~text
Download
Manage owner photos
Remove link
~~~

## Advanced actions

Actions that affect several pieces of state should use a modal or other deliberate UI.

Examples:

~~~text
Move to another device
Set photo taken
Restore deleted photo
Assign orphaned photo
~~~

Avoid adding permanent search fields or large controls directly to cards when the action is rarely used or has important consequences.

## Confirmation and modal policy

Use clear wording for destructive or complex actions.

The message should explain what changes and what does not change.

For owner change, the important policy is:

~~~text
Existing links from other devices will be updated.
The current device will no longer display this photo unless a new link is added.
~~~

Do not hide important side effects behind vague button labels.

## Backend safety rules

For operations that move or rename physical files, validate everything first.

Do not update links, order JSON or other references until the critical file operation has succeeded.

For owner change, the required sequence is:

~~~text
1. Validate source device, target device, filename and permissions
2. Find safe target filename
3. Check target file does not already exist
4. Rename original file
5. Verify target file exists
6. Move or regenerate thumbnail
7. Update links
8. Update order JSON
9. Prune stale order entries
~~~

If the original file rename fails, do not update links or order.

## JSON and locking

JSON files used for links and order are part of plugin state.

Use locked mutation helpers when modifying JSON state.

Preferred pattern:

~~~text
JsonFileService::mutateArrayWithLock()
~~~

Do not use direct write operations for state that can be updated concurrently unless there is a clear reason.

## Order key rules

Owned photo order key:

~~~text
device-108-1.jpg
~~~

Linked photo order key:

~~~text
linked:109:device-109-7.jpg
~~~

When a photo changes owner, linked order keys must follow the new owner and filename.

Example:

~~~text
linked:109:device-109-7.jpg
~~~

becomes:

~~~text
linked:108:device-108-1.jpg
~~~

If the target device already linked to the photo, the self-link should become an owned order key:

~~~text
device-108-1.jpg
~~~

## AJAX policy

AJAX is useful for small actions that can update the UI safely.

Examples:

~~~text
remove_link
remove_outgoing_link
delete owned photo
restore deleted photo
assign orphaned photo
generate missing thumbnails
clean stale thumbnails
~~~

For new complex actions, a normal POST is acceptable as the first implementation.

Convert to AJAX later only when the backend behavior is proven and the UI update path is clear.

## Documentation policy

Complex behavior must be documented in `docs/`.

Owner change behavior is documented in:

~~~text
docs/owner-change.md
~~~

Testing notes can be documented in:

~~~text
docs/testing-alpha.md
~~~

If a feature has non-obvious side effects, document:

- what the feature does
- what it deliberately does not do
- which files or JSON state it changes
- important safety rules
- tested scenarios

## Release notes

For alpha releases, keep README examples aligned with the latest alpha tag.

When preparing a new alpha release, verify:

~~~text
README.md
docs/
composer.json
git tag
GitHub release notes
~~~

## Things to avoid

Avoid:

- new Blade `<style>` blocks
- new inline styles for fresh work
- large mixed commits
- changing service provider route/plugin gating without investigation
- broad CSS selectors that affect LibreNMS outside the plugin
- state-changing GET actions
- file rename logic that updates JSON before verifying the rename succeeded
