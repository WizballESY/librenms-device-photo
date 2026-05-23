# Alpha release test checklist

Use this checklist before tagging a new alpha release.

## Basic checks

- [ ] `git status --short` is clean
- [ ] PHP lint passes for changed PHP/Blade files
- [ ] README install examples point to the new alpha version
- [ ] Test install has been synced and LibreNMS caches cleared

## Device photo manager

- [ ] Open Manage Device Photos for a device with owned photos
- [ ] Preview/zoom owned photo
- [ ] Download owned photo
- [ ] Set photo taken on JPEG photo
- [ ] Delete owned photo via AJAX
- [ ] Restore deleted photo to a device
- [ ] Restored photo is appended at the end without breaking mixed order

## Linked photos

- [ ] Add incoming linked photo from another device
- [ ] Already linked button updates correctly
- [ ] Remove incoming linked photo
- [ ] Link owned photo to another device
- [ ] Remove outgoing/shared link
- [ ] Linked/owned labels and device names look correct before and after refresh

## Ordering

- [ ] Drag/drop owned photos
- [ ] Drag/drop linked photos
- [ ] Drag/drop mixed owned + linked photos
- [ ] Save order
- [ ] Refresh page and verify order is preserved
- [ ] Assign orphaned photo and verify existing mixed order is preserved
- [ ] Restore deleted photo and verify existing mixed order is preserved

## Overview and maintenance

- [ ] Device Photos Overview loads
- [ ] Search works
- [ ] Sorting works
- [ ] Pagination works
- [ ] Show links / Hide links works
- [ ] Manage orphaned photos works
- [ ] Assign orphaned photo removes card live
- [ ] Delete orphaned photo removes card live
- [ ] Remove broken link removes row live
- [ ] “No maintenance issues found” appears after last issue is cleared by AJAX
- [ ] Generate missing thumbnails works
- [ ] Clean stale thumbnails works

## Permissions smoke test

- [ ] Read-only user can view photos
- [ ] User without upload role cannot upload/link/assign
- [ ] User without delete role cannot delete/remove links
- [ ] Admin can perform all actions

## Final release checks

- [ ] README updated
- [ ] Release notes written
- [ ] Tag created and pushed
- [ ] GitHub release marked as pre-release
