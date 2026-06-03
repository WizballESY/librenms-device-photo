# Roadmap / ideas / technical debt

This document tracks ideas, follow-up tasks and technical debt for the LibreNMS Device Photos plugin.

Items here are not necessarily bugs. Some are polish, hardening or future improvements.

## Follow-up candidates

### Overview filter for devices without photos

Status: idea.

Consider adding an overview option/filter to show devices that currently have no owned photos and no linked photos.

Possible use cases:

~~~text
Find devices that still need photos.
Find devices that are missing both uploaded and linked documentation photos.
Help with rollout/completeness checks.
~~~

Possible UI:

~~~text
Overview filter:
[ ] Show devices without photos
[ ] Include devices without owned or linked photos
~~~

Needs design clarification:

~~~text
Should linked photos count as "has photos"?
Should this include all devices or only devices visible in the current LibreNMS device list/search scope?
Should this be admin-only or visible to all users with plugin read access?
~~~

### Orphan assignment hardening

Status: started.

- Use no-overwrite move for assigning orphaned photos.
- Move orphan thumbnail handling after link/order state updates.
- Make orphan thumbnail handling fail-safe with `try/catch`.
- Test orphan assignment with a device that had no photos before assignment.

### Restore deleted photo hardening

Status: planned.

- Review restore deleted photo file move behavior.
- Consider using no-overwrite move when restoring deleted photos.
- Move thumbnail handling after active photo/order state is updated.
- Make restored thumbnail handling fail-safe.

### AJAX stale state after orphan assignment

Status: known issue.

When assigning an orphaned photo to a device that previously had no photos, the backend updates correctly, but the current page search/device state may not include the target device until page reload.

Observed behavior:

~~~text
Assign orphan photo to device 108.
Device 108 had no photos before assignment.
Search did not find device 108 until page reload.
~~~

Possible fix:

~~~text
Reload page after successful AJAX assign_orphan_photo.
~~~

Alternative future fix:

~~~text
Update the frontend device/photo search state dynamically after assignment.
~~~

Recommended alpha approach:

~~~text
Use page reload after successful orphan assignment.
~~~

## Beta candidates

### Manual-check warning after partial state update failure

Status: mostly implemented in alpha29.

Owner-change, orphan assignment, restore, upload and delete can physically move or create a file successfully, then update link/order JSON state.

If a JSON write fails after the physical file operation, the original file is not lost, but UI/link/order state can become partially inconsistent. In these cases the plugin now returns warning statuses instead of plain success.

Implemented status keys include:

~~~text
uploaded_with_warnings
deleted_with_warnings
assigned_with_warnings
restored_with_warnings
photo_owner_changed_with_warnings
~~~

Future work:

~~~text
Review whether warning-state should also include more best-effort prune operations or remain limited to the current metadata-sensitive flows.
~~~

This needs service methods to return reliable status values.

### Return status from link/order services

Some service methods currently return `void` or ignored bool values.

Review:

~~~text
PhotoLinkService::updateFilenameReferences()
PhotoLinkService::add()
PhotoLinkService::remove()
PhotoOrderService::remove()
PhotoOrderService::replaceKey()
PhotoOrderService::save()
~~~

Goal:

~~~text
Return bool or structured result so controllers can detect partial state failure.
~~~

### Cache photo date data while building page data

Some photo date metadata may be calculated repeatedly for the same file.

Improve by calculating once per photo:

~~~text
$dateData = $this->photoDateData($path);
~~~

Then reuse the values.

### Device search scalability

Current autocomplete/search may include the full device list in the page.

For large LibreNMS installs, consider an AJAX search endpoint for devices.

## UI / design ideas

### Owned photo card action layout

Owner cards now have several actions.

Consider a cleaner grouping:

- primary actions
- metadata actions
- link/move actions
- destructive actions

Ideas:

- Keep common actions visible.
- Move advanced actions into a dropdown or modal launcher group.
- Keep delete visually separated.
- Avoid adding more permanent controls directly to cards.

### Linked photo card action layout

After owned-card polish, review linked-card layout too.

Linked cards should stay simpler than owned cards.

### Consistent modal style

Review modals for:

- Move to another device
- Set photo taken
- Confirm action
- Restore / orphan actions

Goal:

~~~text
Same spacing, button layout and dark-mode behavior.
~~~

## Maintenance ideas

### Backup/temp cleanup

Old `.bak`, `.tmp`, `.old`, `.orig`, `~` files under plugin storage should not affect normal operation, but a maintenance check could list them.

Do not auto-delete without admin confirmation.

### Thumbnail generation progress

Current thumbnail generation shows loading state.

Future improvement:

~~~text
Show count generated so far.
~~~

This likely needs progress polling or chunked AJAX processing.

## Notes

Keep changes small.

Preferred flow:

~~~text
patch -> diff -> lint -> sync -> test -> commit -> push
~~~
