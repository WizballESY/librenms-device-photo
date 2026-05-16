
<style>
    /*
     * LibreNMS dark mode compatibility.
     * LibreNMS sets dark mode on html.dark.
     */
    html.dark .device-photo-plugin .device-photo-summary-panel,
    html.dark .device-photo-plugin .device-photo-overview-toolbar,
    html.dark .device-photo-plugin .device-photo-manager-card,
    html.dark .device-photo-plugin .device-photo-orphan-card,
    html.dark .device-photo-plugin .device-photo-confirm-box {
        background: #2f3842 !important;
        border-color: #4b5563 !important;
        color: #d8dee9 !important;
    }

    html.dark .device-photo-plugin .device-photo-summary-panel-header,
    html.dark .device-photo-plugin .device-photo-summary-item .number,
    html.dark .device-photo-plugin h3,
    html.dark .device-photo-plugin h4 {
        color: #f3f4f6 !important;
    }

    html.dark .device-photo-plugin .device-photo-summary-panel-description,
    html.dark .device-photo-plugin .device-photo-summary-item .label,
    html.dark .device-photo-plugin .text-muted {
        color: #aeb8c2 !important;
    }

    html.dark .device-photo-plugin .device-photo-summary-item {
        background: #26303a !important;
        border-color: #4b5563 !important;
        color: #d8dee9 !important;
    }

    html.dark .device-photo-plugin .device-photo-maintenance-ok {
        background: #1f3a2a !important;
        border-color: #2f6b45 !important;
        color: #9fe0b4 !important;
    }

    html.dark .device-photo-plugin .device-photo-dropzone {
        background: #26303a !important;
        border-color: #6b7280 !important;
        color: #d8dee9 !important;
    }

    html.dark .device-photo-plugin .device-photo-dropzone .main-text {
        color: #f3f4f6 !important;
    }

    html.dark .device-photo-plugin .device-photo-dropzone .sub-text,
    html.dark .device-photo-plugin .device-photo-selected-files {
        color: #aeb8c2 !important;
    }

    html.dark .device-photo-plugin .device-photo-file-list {
        background: #26303a !important;
        border-color: #4b5563 !important;
        color: #d8dee9 !important;
    }

    html.dark .device-photo-plugin input,
    html.dark .device-photo-plugin select,
    html.dark .device-photo-plugin textarea,
    html.dark .device-photo-plugin .form-control {
        background: #1f252c !important;
        border-color: #4b5563 !important;
        color: #e5e7eb !important;
    }

    html.dark .device-photo-plugin input::placeholder,
    html.dark .device-photo-plugin textarea::placeholder {
        color: #98a3ad !important;
    }

    html.dark .device-photo-plugin .alert-info {
        background: #26303a !important;
        border-color: #4b5563 !important;
        color: #d8dee9 !important;
    }

    html.dark .device-photo-plugin code {
        background: rgba(255,255,255,0.08);
        color: #ffb4c8;
        border-color: rgba(255,255,255,0.08);
    }
</style>


<style>
    /*
     * LibreNMS dark mode fix for expanded linked-photo rows.
     */
    html.dark .device-photo-plugin .device-photo-links-row,
    html.dark .device-photo-plugin .device-photo-links-row td,
    html.dark .device-photo-plugin tr.device-photo-links-row,
    html.dark .device-photo-plugin tr.device-photo-links-row > td {
        background: #26303a !important;
        border-color: #4b5563 !important;
        color: #d8dee9 !important;
    }

    html.dark .device-photo-plugin .device-photo-links-row a {
        color: #8ecbff !important;
    }

    html.dark .device-photo-plugin .device-photo-links-row code {
        background: rgba(255,255,255,0.08) !important;
        color: #ffb4c8 !important;
        border-color: rgba(255,255,255,0.08) !important;
    }

    html.dark .device-photo-plugin .device-photo-links-row .text-muted {
        color: #aeb8c2 !important;
    }

    html.dark .device-photo-plugin .device-photo-links-row .btn-warning {
        background: #8a5a16 !important;
        border-color: #a66b18 !important;
        color: #fff !important;
    }
</style>


<style>
    /*
     * LibreNMS dark mode fix for expanded linked-photo row inner content.
     */
    html.dark .device-photo-plugin .device-photo-links-row td > div,
    html.dark .device-photo-plugin .device-photo-links-row td > div *,
    html.dark .device-photo-plugin .device-photo-links-row td div {
        background-color: #26303a !important;
        border-color: #4b5563 !important;
    }

    html.dark .device-photo-plugin .device-photo-links-row td > div,
    html.dark .device-photo-plugin .device-photo-links-row td div,
    html.dark .device-photo-plugin .device-photo-links-row td span,
    html.dark .device-photo-plugin .device-photo-links-row td strong {
        color: #d8dee9 !important;
    }

    html.dark .device-photo-plugin .device-photo-links-row td code {
        background: rgba(255,255,255,0.08) !important;
        color: #ffb4c8 !important;
    }
</style>


<style>
    /*
     * LibreNMS dark mode fix for DevicePhoto device search suggestion lists.
     */
    html.dark .device-photo-plugin .device-photo-target-suggestions,
    html.dark .device-photo-plugin .device-photo-orphan-suggestions {
        background: #26303a !important;
        border-color: #4b5563 !important;
        color: #d8dee9 !important;
        box-shadow: 0 8px 24px rgba(0,0,0,0.45) !important;
    }

    html.dark .device-photo-plugin .device-photo-target-suggestion,
    html.dark .device-photo-plugin .device-photo-orphan-suggestion {
        background: #26303a !important;
        border-color: #3f4a56 !important;
        color: #d8dee9 !important;
    }

    html.dark .device-photo-plugin .device-photo-target-suggestion:hover,
    html.dark .device-photo-plugin .device-photo-orphan-suggestion:hover {
        background: #35414d !important;
        color: #ffffff !important;
    }

    html.dark .device-photo-plugin .device-photo-target-suggestion .device-id,
    html.dark .device-photo-plugin .device-photo-orphan-suggestion .device-id {
        color: #ff7aa8 !important;
    }

    html.dark .device-photo-plugin .device-photo-target-suggestion .device-name,
    html.dark .device-photo-plugin .device-photo-orphan-suggestion .device-name {
        color: #cbd5e1 !important;
    }
</style>


<style>
    /*
     * LibreNMS dark mode fix for orphaned photo cards.
     */
    html.dark .device-photo-plugin .device-photo-orphan-card {
        background: #2f3842 !important;
        border-color: #4b5563 !important;
        color: #d8dee9 !important;
        box-shadow: none !important;
    }

    html.dark .device-photo-plugin .device-photo-orphan-card img {
        background: #1f252c !important;
        border-color: #4b5563 !important;
    }

    html.dark .device-photo-plugin .device-photo-orphan-card strong,
    html.dark .device-photo-plugin .device-photo-orphan-card .device-photo-orphan-filename {
        color: #f3f4f6 !important;
    }

    html.dark .device-photo-plugin .device-photo-orphan-card .text-muted,
    html.dark .device-photo-plugin .device-photo-orphan-card .device-photo-orphan-meta,
    html.dark .device-photo-plugin .device-photo-orphan-card div {
        color: #aeb8c2 !important;
    }

    html.dark .device-photo-plugin .device-photo-orphan-card input,
    html.dark .device-photo-plugin .device-photo-orphan-card .form-control {
        background: #1f252c !important;
        border-color: #4b5563 !important;
        color: #e5e7eb !important;
    }

    html.dark .device-photo-plugin .device-photo-orphan-card input::placeholder {
        color: #98a3ad !important;
    }

    html.dark .device-photo-plugin .device-photo-orphan-card .input-group-btn .btn,
    html.dark .device-photo-plugin .device-photo-orphan-card .btn-default {
        background: #26303a !important;
        border-color: #4b5563 !important;
        color: #f3f4f6 !important;
    }

    html.dark .device-photo-plugin .device-photo-orphan-card .input-group-btn .btn:hover,
    html.dark .device-photo-plugin .device-photo-orphan-card .btn-default:hover {
        background: #35414d !important;
        border-color: #64748b !important;
    }
</style>


<style>
    /*
     * LibreNMS dark mode fix for DevicePhoto confirmation dialogs.
     */
    html.dark .device-photo-plugin .device-photo-confirm-box,
    html.dark .device-photo-confirm-box {
        background: #2f3842 !important;
        border-color: #4b5563 !important;
        color: #d8dee9 !important;
    }

    html.dark .device-photo-plugin .device-photo-confirm-title,
    html.dark .device-photo-confirm-title {
        color: #f3f4f6 !important;
    }

    html.dark .device-photo-plugin .device-photo-confirm-message,
    html.dark .device-photo-confirm-message {
        color: #d8dee9 !important;
    }

    html.dark .device-photo-plugin .device-photo-confirm-backdrop,
    html.dark .device-photo-confirm-backdrop {
        background: rgba(0, 0, 0, 0.65) !important;
    }

    html.dark .device-photo-plugin .device-photo-confirm-actions .btn-default,
    html.dark .device-photo-confirm-actions .btn-default {
        background: #26303a !important;
        border-color: #4b5563 !important;
        color: #f3f4f6 !important;
    }

    html.dark .device-photo-plugin .device-photo-confirm-actions .btn-default:hover,
    html.dark .device-photo-confirm-actions .btn-default:hover {
        background: #35414d !important;
        border-color: #64748b !important;
        color: #ffffff !important;
    }
</style>


<style>
    /*
     * LibreNMS dark mode fix for problem/warning counters.
     */
    html.dark .device-photo-plugin .device-photo-summary-item.is-problem {
        background: #3a1f24 !important;
        border-color: #7f2d3a !important;
        color: #ffb4c8 !important;
    }

    html.dark .device-photo-plugin .device-photo-summary-item.is-problem .number,
    html.dark .device-photo-plugin .device-photo-summary-item.is-problem .label {
        color: #ffb4c8 !important;
    }

    html.dark .device-photo-plugin .device-photo-summary-item.is-warning {
        background: #3b2f18 !important;
        border-color: #7a5a1d !important;
        color: #f6d38b !important;
    }

    html.dark .device-photo-plugin .device-photo-summary-item.is-warning .number,
    html.dark .device-photo-plugin .device-photo-summary-item.is-warning .label {
        color: #f6d38b !important;
    }

    html.dark .device-photo-plugin .label-danger {
        background-color: #7f2d3a !important;
        color: #ffffff !important;
    }

    html.dark .device-photo-plugin .label-warning {
        background-color: #8a5a16 !important;
        color: #ffffff !important;
    }
</style>






<style>
    /*
     * LibreNMS dark mode custom visible icon for datetime-local fields.
     * Some browsers do not allow reliable styling of the native calendar icon,
     * so we visually hide it and draw a light icon as an input background.
     */
    html.dark .device-photo-plugin input[type="datetime-local"],
    html.dark .device-photo-plugin #device-photo-set-taken-input {
        color-scheme: dark;
        padding-right: 34px !important;
        background-color: #1f252c !important;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3E%3Cpath fill='%23e5e7eb' d='M3 1h1v2h8V1h1v2h1.5A1.5 1.5 0 0 1 16 4.5v9A1.5 1.5 0 0 1 14.5 15h-13A1.5 1.5 0 0 1 0 13.5v-9A1.5 1.5 0 0 1 1.5 3H3V1zm11.5 5h-13v7.5a.5.5 0 0 0 .5.5h12a.5.5 0 0 0 .5-.5V6zM2 4a.5.5 0 0 0-.5.5V5h13v-.5A.5.5 0 0 0 14 4H2z'/%3E%3C/svg%3E") !important;
        background-repeat: no-repeat !important;
        background-position: right 10px center !important;
        background-size: 14px 14px !important;
    }

    html.dark .device-photo-plugin input[type="datetime-local"]::-webkit-calendar-picker-indicator,
    html.dark .device-photo-plugin #device-photo-set-taken-input::-webkit-calendar-picker-indicator {
        opacity: 0 !important;
        cursor: pointer;
    }
</style>


<style>
    /*
     * LibreNMS dark mode fix for incoming owner photo cards.
     */
    html.dark .device-photo-plugin .device-photo-incoming-owner-card {
        background: #2f3842 !important;
        border-color: #4b5563 !important;
        color: #d8dee9 !important;
        box-shadow: none !important;
    }

    html.dark .device-photo-plugin .device-photo-incoming-owner-card img {
        background: #1f252c !important;
        border-color: #4b5563 !important;
    }

    html.dark .device-photo-plugin .device-photo-incoming-owner-card .btn-default {
        background: #26303a !important;
        border-color: #4b5563 !important;
        color: #f3f4f6 !important;
    }

    html.dark .device-photo-plugin .device-photo-incoming-owner-card .btn-default:hover {
        background: #35414d !important;
        border-color: #64748b !important;
        color: #ffffff !important;
    }
</style>


<style>
    /*
     * LibreNMS dark mode fix for linked photo cards.
     */
    html.dark .device-photo-plugin .device-photo-linked-photo-card {
        background: #2f3842 !important;
        border-color: #4b5563 !important;
        color: #d8dee9 !important;
        box-shadow: none !important;
    }

    html.dark .device-photo-plugin .device-photo-linked-photo-card img {
        background: #1f252c !important;
        border-color: #4b5563 !important;
    }

    html.dark .device-photo-plugin .device-photo-linked-photo-card .device-photo-linked-owner-box {
        background: #26303a !important;
        border-color: #4b5563 !important;
        color: #d8dee9 !important;
    }

    html.dark .device-photo-plugin .device-photo-linked-photo-card strong {
        color: #f3f4f6 !important;
    }

    html.dark .device-photo-plugin .device-photo-linked-photo-card a {
        color: #8ecbff !important;
    }

    html.dark .device-photo-plugin .device-photo-linked-photo-card code {
        background: rgba(255,255,255,0.08) !important;
        color: #ffb4c8 !important;
        border-color: rgba(255,255,255,0.08) !important;
    }

    html.dark .device-photo-plugin .device-photo-linked-photo-card .btn-default {
        background: #26303a !important;
        border-color: #4b5563 !important;
        color: #f3f4f6 !important;
    }

    html.dark .device-photo-plugin .device-photo-linked-photo-card .btn-default:hover {
        background: #35414d !important;
        border-color: #64748b !important;
        color: #ffffff !important;
    }
</style>

<div class="container-fluid device-photo-plugin">
    <h2 style="margin-bottom: 14px;">
        {{ ($global_overview ?? false) ? 'Device Photos Overview' : 'Manage Device Photos' }}
        <span class="label label-warning" title="This plugin is currently in alpha. Features may change and bugs may exist." style="font-size: 11px; vertical-align: middle; margin-left: 8px;">
            ALPHA
        </span>
    </h2>

    @if ($device)
        <div style="margin-top: 10px; margin-bottom: 18px;">
            <a href="{{ url('device/' . $device->device_id) }}" class="btn btn-default btn-sm">
                <i class="fa fa-arrow-left"></i> Back to device
            </a>

            <a href="{{ url('plugin/device-photo') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-camera"></i> Device Photos Overview
            </a>
        </div>
    @endif

    <div class="alert alert-warning" style="font-size: 12px;">
        <strong>Notice:</strong>
        This plugin was created with assistance from AI. Use at your own risk.
        Make sure you have tested it before using it in production.
    </div>

    @if ($message)
        <div class="alert alert-success">{{ $message }}</div>
    @endif

    @if ($error)
        <div class="alert alert-danger">{{ $error }}</div>
    @endif

    @include('device-photo::partials.photo-modal')



    @if ($global_overview ?? false)
        @php
            $overview = $global_photo_overview ?? ['rows' => [], 'orphaned_photos' => [], 'broken_links' => []];
        @endphp

        <div class="panel panel-default">
            <div class="panel-heading">
                <strong><i class="fa fa-camera"></i> Device Photos Overview</strong>
            </div>

            <div class="panel-body">
                <p class="text-muted">
                    This page shows devices with owned photos, linked photos, and orphaned photo files.
                    Upload and photo deletion are handled from each device's photo manager.
                </p>

                <style>
                    .device-photo-links-row td {
                        border-top: 0;
                    }

                    .device-photo-links-row td > div,
                    .device-photo-links-row td {
                        background: #fafafa;
                    }
                </style>

                <style>
                    .device-photo-summary-panels {
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(360px, 1fr));
                        gap: 12px;
                        margin: 16px 0 16px 0;
                    }

                    .device-photo-summary-panel {
                        background: #fff;
                        border: 1px solid #ddd;
                        border-radius: 8px;
                        padding: 12px;
                        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
                    }

                    .device-photo-summary-panel-header {
                        display: flex;
                        align-items: center;
                        gap: 7px;
                        margin-bottom: 10px;
                        color: #444;
                        font-weight: bold;
                        font-size: 14px;
                    }

                    .device-photo-summary-panel-header i {
                        color: #555;
                    }

                    .device-photo-summary-panel-description {
                        margin-top: -4px;
                        margin-bottom: 10px;
                        color: #777;
                        font-size: 12px;
                    }

                    .device-photo-summary-panel-items {
                        display: flex;
                        flex-wrap: wrap;
                        gap: 7px;
                        align-items: center;
                    }

                    .device-photo-summary-item {
                        display: inline-flex;
                        align-items: baseline;
                        gap: 4px;
                        padding: 7px 10px;
                        background: #f8f8f8;
                        border: 1px solid #ddd;
                        border-radius: 7px;
                        white-space: nowrap;
                        line-height: 1.15;
                        cursor: help;
                    }

                    .device-photo-summary-item .number {
                        display: inline-block;
                        font-weight: bold;
                        font-size: 15px;
                        color: #555;
                    }

                    .device-photo-summary-item .label {
                        display: inline-block;
                        font-size: 12px;
                        color: #666;
                    }

                    .device-photo-summary-item.is-problem {
                        background: #f2dede;
                        border-color: #ebccd1;
                    }

                    .device-photo-summary-item.is-problem .number,
                    .device-photo-summary-item.is-problem .label {
                        color: #a94442;
                    }

                    .device-photo-maintenance-ok {
                        display: inline-flex;
                        align-items: center;
                        gap: 6px;
                        padding: 7px 10px;
                        background: #dff0d8;
                        border: 1px solid #d6e9c6;
                        border-radius: 7px;
                        color: #3c763d;
                        font-size: 12px;
                        font-weight: bold;
                    }
                </style>

                @php
                    $maintenanceIssueCount =
                        count($overview['orphaned_photos'] ?? []) +
                        count($overview['broken_links'] ?? []) +
                        (int) ($overview['missing_thumbnail_count'] ?? 0) +
                        (int) ($overview['stale_thumbnail_count'] ?? 0);
                @endphp

                <div class="device-photo-summary-panels">
                    <div class="device-photo-summary-panel">
                        <div class="device-photo-summary-panel-header">
                            <i class="fa fa-camera"></i>
                            Photo library
                        </div>

                        <div class="device-photo-summary-panel-description">
                            Current photo inventory and storage usage. Sizes do not include thumbnails.
                        </div>

                        <div class="device-photo-summary-panel-items">
                            <span class="device-photo-summary-item" title="Number of devices that currently have owned photos or linked photos.">
                                <span class="number">{{ count($overview['rows'] ?? []) }}</span><span class="label">devices</span>
                            </span>

                            <span class="device-photo-summary-item" title="Photos currently available in the main photo folder.">
                                <span class="number">{{ $overview['active_photo_count'] ?? 0 }}</span><span class="label">active photos</span>
                            </span>

                            <span class="device-photo-summary-item" title="Total size of active original photos. Thumbnails are not included.">
                                <span class="number">{{ $overview['active_photo_mb'] ?? 0 }} MB</span><span class="label">active size</span>
                            </span>

                            <span class="device-photo-summary-item" title="Photos moved to the deleted folder.">
                                <span class="number">{{ $overview['deleted_photo_count'] ?? 0 }}</span><span class="label">deleted photos</span>
                            </span>

                            <span class="device-photo-summary-item" title="Total size of deleted original photos. Deleted thumbnails are not included.">
                                <span class="number">{{ $overview['deleted_photo_mb'] ?? 0 }} MB</span><span class="label">deleted size</span>
                            </span>
                        </div>
                    </div>

                    <div class="device-photo-summary-panel">
                        <div class="device-photo-summary-panel-header">
                            <i class="fa fa-wrench"></i>
                            Maintenance
                        </div>

                        <div class="device-photo-summary-panel-description">
                            Cleanup checks for orphaned photos, broken links and thumbnail cache issues.
                        </div>

                        <div class="device-photo-summary-panel-items">
                            @if ($maintenanceIssueCount === 0)
                                <span class="device-photo-maintenance-ok" title="No orphaned photos, broken links, missing thumbnails or stale thumbnails were found.">
                                    <i class="fa fa-check-circle"></i>
                                    No maintenance issues found
                                </span>
                            @else
                                @if (count($overview['orphaned_photos'] ?? []) > 0)
                                    <span class="device-photo-summary-item is-problem" title="Photos where the original LibreNMS device ID no longer exists.">
                                        <span class="number">{{ count($overview['orphaned_photos'] ?? []) }}</span><span class="label">orphans</span>
                                    </span>
                                @endif

                                @if (count($overview['broken_links'] ?? []) > 0)
                                    <span class="device-photo-summary-item is-problem" title="Photo links that point to a missing original file.">
                                        <span class="number">{{ count($overview['broken_links'] ?? []) }}</span><span class="label">broken links</span>
                                    </span>
                                @endif

                                @if (($overview['missing_thumbnail_count'] ?? 0) > 0)
                                    <span class="device-photo-summary-item is-problem" title="Active photos without a generated thumbnail.">
                                        <span class="number">{{ $overview['missing_thumbnail_count'] ?? 0 }}</span><span class="label">missing thumbnails</span>
                                    </span>
                                @endif

                                @if (($overview['stale_thumbnail_count'] ?? 0) > 0)
                                    <span class="device-photo-summary-item is-problem" title="Thumbnail files where the original active photo no longer exists.">
                                        <span class="number">{{ $overview['stale_thumbnail_count'] ?? 0 }}</span><span class="label">stale thumbnails</span>
                                    </span>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <style>
                    .device-photo-target-suggestions {
                        display: none;
                        position: absolute;
                        z-index: 9999;
                        left: 0;
                        top: 100%;
                        margin-top: 4px;
                        background: #fff;
                        border: 1px solid #ccc;
                        border-radius: 4px;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                        max-height: 320px;
                        overflow-y: auto;
                        min-width: 260px;
                        width: 100%;
                        font-size: 12px;
                    }

                    .device-photo-target-suggestion {
                        padding: 6px 8px;
                        cursor: pointer;
                        border-bottom: 1px solid #eee;
                    }

                    .device-photo-target-suggestion:hover {
                        background: #f3f7fb;
                    }

                    .device-photo-target-suggestion .device-id {
                        font-family: monospace;
                        color: #b00040;
                    }

                    .device-photo-target-suggestion .device-name {
                        margin-left: 6px;
                    }
                </style>

                <script>
                    window.DevicePhotoTargetDevices = @json(
                        collect($link_target_devices ?? [])->values()
                    );

                    document.addEventListener('DOMContentLoaded', function () {
                        var maxResults = 20;
                        var targetDevices = window.DevicePhotoTargetDevices || [];
                        var ownerDevices = window.DevicePhotoOwnerDevices || [];

                        function normalize(value) {
                            return String(value || '').toLowerCase();
                        }

                        function findMatches(query) {
                            query = String(query || '').trim();

                            if (query === '') {
                                return [];
                            }

                            var q = normalize(query);
                            var exactId = [];
                            var startsWithId = [];
                            var startsWithName = [];
                            var containsName = [];

                            devices.forEach(function (device) {
                                var id = String(device.device_id);
                                var label = String(device.label || '');
                                var labelLower = normalize(label);

                                if (id === query) {
                                    exactId.push(device);
                                } else if (id.indexOf(query) === 0) {
                                    startsWithId.push(device);
                                } else if (labelLower.indexOf(q) === 0) {
                                    startsWithName.push(device);
                                } else if (labelLower.indexOf(q) !== -1) {
                                    containsName.push(device);
                                }
                            });

                            return exactId
                                .concat(startsWithId)
                                .concat(startsWithName)
                                .concat(containsName)
                                .slice(0, maxResults);
                        }

                        function closeAllSuggestions() {
                            document.querySelectorAll('.device-photo-target-suggestions').forEach(function (box) {
                                box.style.display = 'none';
                            });
                        }

                        function ensureSuggestionBox(input) {
                            var wrapper = input.closest('.input-group');
                            var box = wrapper.parentNode.querySelector('.device-photo-target-suggestions');

                            if (!box) {
                                box = document.createElement('div');
                                box.className = 'device-photo-target-suggestions';
                                wrapper.parentNode.appendChild(box);
                            }

                            return box;
                        }

                        function renderSuggestions(input) {
                            var box = ensureSuggestionBox(input);
                            var matches = findMatches(input.value);

                            box.innerHTML = '';

                            if (matches.length === 0) {
                                box.style.display = 'none';
                                return;
                            }

                            matches.forEach(function (device) {
                                var item = document.createElement('div');
                                item.className = 'device-photo-target-suggestion';

                                var id = document.createElement('span');
                                id.className = 'device-id';
                                id.textContent = device.device_id;

                                var name = document.createElement('span');
                                name.className = 'device-name';
                                name.textContent = device.label || '';

                                item.appendChild(id);
                                item.appendChild(document.createTextNode(' - '));
                                item.appendChild(name);

                                item.addEventListener('mousedown', function (e) {
                                    e.preventDefault();
                                    input.value = device.device_id + ' - ' + (device.label || '');
                                    box.style.display = 'none';
                                });

                                box.appendChild(item);
                            });

                            box.style.display = 'block';
                        }

                        document.querySelectorAll('.device-photo-target-input').forEach(function (input) {
                            input.addEventListener('input', function () {
                                renderSuggestions(input);
                            });

                            input.addEventListener('focus', function () {
                                renderSuggestions(input);
                            });

                            input.addEventListener('keydown', function (e) {
                                if (e.key === 'Escape') {
                                    closeAllSuggestions();
                                }
                            });
                        });

                        document.addEventListener('click', function (e) {
                            if (!e.target.closest('.device-photo-target-suggestions') && !e.target.closest('.device-photo-target-input')) {
                                closeAllSuggestions();
                            }
                        });
                    });
                </script>

                @if (empty($overview['gd_available']))
                    <div class="alert alert-warning" style="font-size: 12px; padding: 8px 10px;">
                        <strong>Thumbnail generation unavailable:</strong>
                        PHP GD extension is not installed. Original images will be used as thumbnails.
                    </div>
                @elseif (empty($overview['thumb_dir_writable']))
                    <div class="alert alert-danger" style="font-size: 12px; padding: 8px 10px;">
                        <strong>Thumbnail folder is not writable:</strong>
                        Check permissions on <code>storage/app/device-photos/thumbs</code>.
                    </div>
                @elseif (($overview['missing_thumbnail_count'] ?? 0) > 0)
                    <div class="alert alert-warning" style="font-size: 12px; padding: 8px 10px;">
                        <form method="post" action="{{ url('plugin/device-photo-package/action') }}" data-device-photo-confirm="Generate missing thumbnails for active photos? Existing thumbnails will not be overwritten." style="display: inline;">
                            @csrf
                            <input type="hidden" name="action" value="generate_missing_thumbnails">
                            <input type="hidden" name="device_id" value="0">
                            <input type="hidden" name="return_to" value="overview">

                            <strong>{{ $overview['missing_thumbnail_count'] ?? 0 }}</strong>
                            active photo{{ ($overview['missing_thumbnail_count'] ?? 0) === 1 ? '' : 's' }} missing thumbnails.

                            <button type="submit" class="btn btn-warning btn-xs" style="margin-left: 8px;">
                                <i class="fa fa-magic"></i> Generate missing thumbnails
                            </button>
                        </form>
                    </div>
                @endif

                @if (($overview['stale_thumbnail_count'] ?? 0) > 0)
                    <div class="alert alert-warning" style="font-size: 12px; padding: 8px 10px;">
                        <form method="post" action="{{ url('plugin/device-photo-package/action') }}" data-device-photo-confirm="Remove stale thumbnails that no longer have a matching original photo?" style="display: inline;">
                            @csrf
                            <input type="hidden" name="action" value="clean_stale_thumbnails">
                            <input type="hidden" name="device_id" value="0">
                            <input type="hidden" name="return_to" value="overview">

                            <strong>{{ $overview['stale_thumbnail_count'] ?? 0 }}</strong>
                            stale thumbnail{{ ($overview['stale_thumbnail_count'] ?? 0) === 1 ? '' : 's' }} without matching original photo.

                            <button type="submit" class="btn btn-warning btn-xs" style="margin-left: 8px;">
                                <i class="fa fa-trash"></i> Clean stale thumbnails
                            </button>
                        </form>
                    </div>
                @endif

                <style>
                    .device-photo-sort-header {
                        cursor: pointer;
                        user-select: none;
                        white-space: nowrap;
                    }

                    .device-photo-sort-header:hover {
                        background: #f5f5f5;
                    }

                    .device-photo-sort-header .device-photo-sort-label {
                        display: inline-flex;
                        align-items: center;
                        gap: 4px;
                    }

                    .device-photo-sort-header .device-photo-sort-indicator {
                        display: inline-block;
                        min-width: 10px;
                        color: #999;
                        font-size: 10px;
                    }

                    .device-photo-sort-header.is-active .device-photo-sort-indicator {
                        color: #333;
                    }

                    .device-photo-sort-header:not(.is-active) .device-photo-sort-indicator::before {
                        content: "\f0dc";
                        font-family: FontAwesome;
                        color: #bbb;
                    }
                </style>

                <div class="device-photo-overview-toolbar" style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center; justify-content: space-between; margin: 0 0 16px 0; padding: 10px; background: #f8f8f8; border: 1px solid #ddd; border-radius: 8px;">
                    <div style="display: flex; flex: 1 1 420px; gap: 8px; align-items: center; min-width: 260px;">
                        <span class="text-muted" style="font-size: 13px; white-space: nowrap;">
                            <i class="fa fa-search"></i>
                            Search
                        </span>

                        <input
                            type="text"
                            id="device-photo-overview-filter"
                            class="form-control"
                            placeholder="Search by device ID or device name"
                            style="max-width: 640px;"
                        >
                    </div>

                    <div style="display: flex; gap: 6px; align-items: center; flex: 0 0 auto;">
                        <span class="text-muted" style="font-size: 12px;">Show</span>
                        <select id="device-photo-overview-page-size" class="form-control input-sm" style="width: auto;">
                            <option value="10">10</option>
                            <option value="25" selected>25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="all">All</option>
                        </select>
                        <span class="text-muted" style="font-size: 12px;">per page</span>
                    </div>
                </div>

                <h4 style="margin-top: 0;">Devices with photos or links</h4>

                @if (empty($overview['rows']))
                    <div class="alert alert-info">No device photos or linked photos found.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover table-condensed" id="device-photo-overview-table">
                            <thead>
                                <tr>
                                    <th class="device-photo-sort-header" data-sort-key="device" title="Sort by device name.">
                                        <span class="device-photo-sort-label">Device <span class="device-photo-sort-indicator"></span></span>
                                    </th>
                                    <th class="device-photo-sort-header" data-sort-key="owned" title="Sort by number of photos physically owned by this device.">
                                        <span class="device-photo-sort-label">Owned photos <span class="device-photo-sort-indicator"></span></span>
                                    </th>
                                    <th class="device-photo-sort-header" data-sort-key="linked_in" title="Sort by number of photos owned by other devices, but shown on this device.">
                                        <span class="device-photo-sort-label">Linked in <span class="device-photo-sort-indicator"></span></span>
                                    </th>
                                    <th class="device-photo-sort-header" data-sort-key="linked_out" title="Sort by number of photos owned by this device, but shown on other devices.">
                                        <span class="device-photo-sort-label">Linked out <span class="device-photo-sort-indicator"></span></span>
                                    </th>
                                    <th title="Thumbnail preview of photos owned by this device.">Preview</th>
                                    <th title="Open the device or manage photos for this device.">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($overview['rows'] as $row)
                                    <tr class="device-photo-overview-row"
                                        data-device-photo-row="{{ $row['device_id'] }}"
                                        data-sort-device="{{ strtolower($row['name'] . ' ' . $row['device_id']) }}"
                                        data-sort-owned="{{ $row['owned_count'] }}"
                                        data-sort-linked-in="{{ $row['linked_in_count'] }}"
                                        data-sort-linked-out="{{ $row['linked_out_count'] }}"
                                        data-filter="{{ strtolower($row['device_id'] . ' ' . $row['name'] . ' ' . collect($row['owned_photos'])->pluck('filename')->implode(' ') . ' ' . collect($row['linked_in'])->pluck('filename')->implode(' ') . ' ' . collect($row['linked_out'])->pluck('filename')->implode(' ')) }}">
                                        <td>
                                            <a href="{{ url('device/' . $row['device_id']) }}">
                                                <code>Device ID: {{ $row['device_id'] }}</code>
                                            </a>
                                            <br>
                                            <a href="{{ url('device/' . $row['device_id']) }}">
                                                {{ $row['name'] }}
                                            </a>
                                        </td>
                                        <td>{{ $row['owned_count'] }}</td>
                                        <td>{{ $row['linked_in_count'] }}</td>
                                        <td>{{ $row['linked_out_count'] }}</td>
                                        <td>
                                            @if (!empty($row['owned_photos']))
                                                <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                                    @foreach ($row['owned_photos'] as $photo)
                                                        <a href="{{ $photo['url'] }}" data-device-photo-preview-src="{{ $photo['url'] }}" title="{{ $photo['filename'] }}">
                                                            <img data-device-photo-preview-src="{{ $photo['url'] }}"
                                    data-device-photo-taken="{{ $photo['photo_taken_iso'] ?? '' }}"
                                    data-device-photo-file-date="{{ $photo['file_date_iso'] ?? '' }}" src="{{ $photo['thumb_url'] ?? $photo['url'] }}" style="width: 54px; height: 42px; object-fit: contain; border: 1px solid #ddd; border-radius: 4px; background: #fff;">
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted">No owned photos</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a class="btn btn-default btn-xs" href="{{ url('device/' . $row['device_id']) }}">
                                                Open device
                                            </a>
                                            <a class="btn btn-primary btn-xs" href="{{ url('plugin/device-photo') }}?device_id={{ $row['device_id'] }}">
                                                Manage photos
                                            </a>

                                            @if (!empty($row['linked_in']) || !empty($row['linked_out']))
                                                <button type="button"
                                                        class="btn btn-info btn-xs device-photo-toggle-links"
                                                        data-device-photo-target="{{ $row['device_id'] }}">
                                                    Show links
                                                </button>
                                            @endif
                                        </td>
                                    </tr>

                                    @if (!empty($row['linked_in']) || !empty($row['linked_out']))
                                        <tr class="device-photo-overview-row device-photo-links-row"
                                            data-device-photo-links="{{ $row['device_id'] }}"
                                            style="display: none;"
                                            data-filter="{{ strtolower($row['device_id'] . ' ' . $row['name'] . ' ' . collect($row['linked_in'])->pluck('filename')->implode(' ') . ' ' . collect($row['linked_out'])->pluck('filename')->implode(' ')) }}">
                                            <td colspan="6" style="background: #fafafa;">
                                                @if (!empty($row['linked_in']))
                                                    <div style="margin-bottom: 8px;">
                                                        <strong>Linked photos shown on this device:</strong>
                                                        <ul style="margin: 4px 0 0 18px;">
                                                            @foreach ($row['linked_in'] as $link)
                                                                <li>
                                                                    {{ $link['filename'] }}
                                                                    from
                                                                    <a href="{{ url('device/' . $link['owner_device_id']) }}">
                                                                        Device ID: {{ $link['owner_device_id'] }}
                                                                    </a>
                                                                    @if (!empty($link['owner_name']))
                                                                        - <a href="{{ url('device/' . $link['owner_device_id']) }}">{{ $link['owner_name'] }}</a>
                                                                    @endif

                                                                    @if ($can_delete)
                                                                        <form method="post" action="{{ url('plugin/device-photo-package/action') }}" style="display: inline;" data-device-photo-confirm="Remove this link? The original photo will not be deleted.">
                                                                            @csrf
                                                                            <input type="hidden" name="action" value="remove_link">
                                                                            <input type="hidden" name="return_to" value="overview">
                                                                            <input type="hidden" name="device_id" value="{{ $row['device_id'] }}">
                                                                            <input type="hidden" name="owner_device_id" value="{{ $link['owner_device_id'] }}">
                                                                            <input type="hidden" name="filename" value="{{ $link['filename'] }}">
                                                                            <button type="submit" class="btn btn-warning btn-xs">
                                                                                Remove link
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif

                                                @if (!empty($row['linked_out']))
                                                    <div>
                                                        <strong>Owned photos linked to other devices:</strong>
                                                        <ul style="margin: 4px 0 0 18px;">
                                                            @foreach ($row['linked_out'] as $link)
                                                                <li>
                                                                    {{ $link['filename'] }}
                                                                    linked to
                                                                    <a href="{{ url('device/' . $link['target_device_id']) }}">
                                                                        Device ID: {{ $link['target_device_id'] }}
                                                                    </a>
                                                                    @if (!empty($link['target_name']))
                                                                        - <a href="{{ url('device/' . $link['target_device_id']) }}">{{ $link['target_name'] }}</a>
                                                                    @endif

                                                                    @if ($can_delete)
                                                                        <form method="post" action="{{ url('plugin/device-photo-package/action') }}" style="display: inline;" data-device-photo-confirm="Remove this outgoing link? The original photo will not be deleted.">
                                                                            @csrf
                                                                            <input type="hidden" name="action" value="remove_outgoing_link">
                                                                            <input type="hidden" name="return_to" value="overview">
                                                                            <input type="hidden" name="device_id" value="{{ $row['device_id'] }}">
                                                                            <input type="hidden" name="target_device_id" value="{{ $link['target_device_id'] }}">
                                                                            <input type="hidden" name="filename" value="{{ $link['filename'] }}">
                                                                            <button type="submit" class="btn btn-warning btn-xs">
                                                                                Remove link
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div id="device-photo-overview-pagination" style="display: flex; flex-wrap: wrap; gap: 8px; align-items: center; justify-content: flex-end; margin: 10px 0 0 0;">
                        <span id="device-photo-overview-page-info" class="text-muted" style="font-size: 12px;"></span>

                        <button type="button" class="btn btn-default btn-xs" id="device-photo-overview-prev">
                            <i class="fa fa-chevron-left"></i> Previous
                        </button>

                        <button type="button" class="btn btn-default btn-xs" id="device-photo-overview-next">
                            Next <i class="fa fa-chevron-right"></i>
                        </button>
                    </div>
                @endif

                <hr>

                <style>
                    .device-photo-orphan-suggestions {
                        display: none;
                        position: absolute;
                        z-index: 99999;
                        left: 0;
                        bottom: 100%;
                        margin-bottom: 4px;
                        background: #fff;
                        border: 1px solid #ccc;
                        border-radius: 4px;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.20);
                        max-height: 320px;
                        overflow-y: auto;
                        min-width: 260px;
                        width: 100%;
                        font-size: 12px;
                    }

                    .device-photo-orphan-suggestion {
                        padding: 6px 8px;
                        cursor: pointer;
                        border-bottom: 1px solid #eee;
                    }

                    .device-photo-orphan-suggestion:hover {
                        background: #f3f7fb;
                    }

                    .device-photo-orphan-suggestion .device-id {
                        font-family: monospace;
                        color: #b00040;
                    }

                    .device-photo-orphan-suggestion .device-name {
                        margin-left: 6px;
                    }
                </style>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var orphanDevices = @json(collect($link_target_devices ?? [])->values());
                        var maxResults = 20;

                        function normalize(value) {
                            return String(value || '').toLowerCase();
                        }

                        function findOrphanMatches(query) {
                            query = String(query || '').trim();

                            if (query === '') {
                                return [];
                            }

                            var q = normalize(query);
                            var exactId = [];
                            var startsWithId = [];
                            var startsWithName = [];
                            var containsName = [];

                            orphanDevices.forEach(function (device) {
                                var id = String(device.device_id);
                                var label = String(device.label || '');
                                var labelLower = normalize(label);

                                if (id === query) {
                                    exactId.push(device);
                                } else if (id.indexOf(query) === 0) {
                                    startsWithId.push(device);
                                } else if (labelLower.indexOf(q) === 0) {
                                    startsWithName.push(device);
                                } else if (labelLower.indexOf(q) !== -1) {
                                    containsName.push(device);
                                }
                            });

                            return exactId
                                .concat(startsWithId)
                                .concat(startsWithName)
                                .concat(containsName)
                                .slice(0, maxResults);
                        }

                        function closeOrphanSuggestions() {
                            document.querySelectorAll('.device-photo-orphan-suggestions').forEach(function (box) {
                                box.style.display = 'none';
                            });
                        }

                        function ensureOrphanSuggestionBox(input) {
                            var form = input.closest('form');
                            var box = form.querySelector('.device-photo-orphan-suggestions');

                            if (!box) {
                                box = document.createElement('div');
                                box.className = 'device-photo-orphan-suggestions';
                                form.appendChild(box);
                            }

                            return box;
                        }

                        function renderOrphanSuggestions(input) {
                            var box = ensureOrphanSuggestionBox(input);
                            var matches = findOrphanMatches(input.value);

                            box.innerHTML = '';

                            if (matches.length === 0) {
                                box.style.display = 'none';
                                return;
                            }

                            matches.forEach(function (device) {
                                var item = document.createElement('div');
                                item.className = 'device-photo-orphan-suggestion';

                                var id = document.createElement('span');
                                id.className = 'device-id';
                                id.textContent = device.device_id;

                                var name = document.createElement('span');
                                name.className = 'device-name';
                                name.textContent = device.label || '';

                                item.appendChild(id);
                                item.appendChild(document.createTextNode(' - '));
                                item.appendChild(name);

                                item.addEventListener('mousedown', function (e) {
                                    e.preventDefault();
                                    input.value = device.device_id + ' - ' + (device.label || '');
                                    box.style.display = 'none';
                                });

                                box.appendChild(item);
                            });

                            box.style.display = 'block';
                        }

                        document.querySelectorAll('.device-photo-orphan-target-input').forEach(function (input) {
                            input.addEventListener('input', function () {
                                renderOrphanSuggestions(input);
                            });

                            input.addEventListener('focus', function () {
                                renderOrphanSuggestions(input);
                            });

                            input.addEventListener('keydown', function (e) {
                                if (e.key === 'Escape') {
                                    closeOrphanSuggestions();
                                }
                            });
                        });

                        document.addEventListener('click', function (e) {
                            if (!e.target.closest('.device-photo-orphan-suggestions') && !e.target.closest('.device-photo-orphan-target-input')) {
                                closeOrphanSuggestions();
                            }
                        });
                    });
                </script>

                <h4>Orphaned photos</h4>

                <p class="text-muted">
                    Orphaned photos are image files where the original LibreNMS Device ID no longer exists.
                    This can happen if a device was deleted from LibreNMS, restored with a different ID,
                    or if files were copied manually.
                    You can assign the photo to an existing device, download it, or delete it. Deleted photos are moved to the deleted folder and are not permanently removed.
                </p>

                @if (empty($overview['orphaned_photos']))
                    <div class="alert alert-info">No orphaned photos found.</div>
                @else
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 240px)); gap: 14px;">
                        @foreach ($overview['orphaned_photos'] as $photo)
                            <div class="device-photo-orphan-card" style="background: #f8f8f8; border: 1px solid #ddd; border-radius: 8px; padding: 10px;">
                                <img data-device-photo-preview-src="{{ $photo['url'] }}"
                                    data-device-photo-taken="{{ $photo['photo_taken_iso'] ?? '' }}"
                                    data-device-photo-file-date="{{ $photo['file_date_iso'] ?? '' }}" src="{{ $photo['thumb_url'] ?? $photo['url'] }}" style="width: 100%; max-height: 160px; object-fit: contain; background: #fff; border-radius: 5px; margin-bottom: 8px;">
                                <div style="font-size: 12px;">
                                    <strong>{{ $photo['filename'] }}</strong><br>
                                    <span class="text-muted">Missing Device ID: {{ $photo['device_id'] }}</span>
                                </div>
                                <a href="{{ $photo['url'] }}" download="{{ $photo['filename'] }}" class="btn btn-default btn-xs btn-block" style="margin-top: 8px;">
                                    <i class="fa fa-download"></i> Download
                                </a>

                                @if ($can_upload)
                                    <form method="post" action="{{ url('plugin/device-photo-package/action') }}" style="margin-top: 8px; position: relative;" data-device-photo-confirm="Assign this orphaned photo to the selected device? The file will be renamed.">
                                        @csrf
                                        <input type="hidden" name="action" value="assign_orphan_photo">
                                        <input type="hidden" name="device_id" value="0">
                                        <input type="hidden" name="filename" value="{{ $photo['filename'] }}">

                                        <div class="input-group input-group-sm" style="max-width: 220px;">
                                            <input
                                                type="text"
                                                name="target_device_query"
                                                class="form-control device-photo-orphan-target-input"
                                                placeholder="Search Device ID or name"
                                                autocomplete="off"
                                                required
                                            >
                                            <span class="input-group-btn">
                                                <button type="submit" class="btn btn-primary">
                                                    Assign
                                                </button>
                                            </span>
                                        </div>
                                    </form>
                                @endif

                                @if ($can_delete)
                                    <form method="post" action="{{ url('plugin/device-photo-package/action') }}" style="margin-top: 8px;" data-device-photo-confirm="Delete this orphaned photo? It will be moved to the deleted folder and can be restored manually.">
                                        @csrf
                                        <input type="hidden" name="action" value="delete_orphan_photo">
                                        <input type="hidden" name="device_id" value="0">
                                        <input type="hidden" name="filename" value="{{ $photo['filename'] }}">

                                        <button type="submit" class="btn btn-danger btn-xs btn-block">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                <style>
                    .device-photo-confirm-backdrop {
                        display: none;
                        position: fixed;
                        inset: 0;
                        background: rgba(0, 0, 0, 0.45);
                        z-index: 20000;
                        align-items: center;
                        justify-content: center;
                    }

                    .device-photo-confirm-box {
                        background: #fff;
                        border-radius: 10px;
                        box-shadow: 0 8px 30px rgba(0,0,0,0.35);
                        width: 420px;
                        max-width: calc(100vw - 40px);
                        padding: 18px 20px;
                        border-top: 5px solid #337ab7;
                    }

                    .device-photo-confirm-title {
                        font-size: 18px;
                        font-weight: bold;
                        margin-bottom: 10px;
                    }

                    .device-photo-confirm-message {
                        font-size: 14px;
                        line-height: 1.45;
                        margin-bottom: 18px;
                        color: #333;
                    }

                    .device-photo-confirm-actions {
                        text-align: right;
                    }

                    .device-photo-confirm-actions .btn {
                        margin-left: 6px;
                    }
                </style>

                <div id="device-photo-confirm-backdrop" class="device-photo-confirm-backdrop">
                    <div class="device-photo-confirm-box">
                        <div class="device-photo-confirm-title">
                            <i class="fa fa-exclamation-circle"></i> Confirm action
                        </div>

                        <div id="device-photo-confirm-message" class="device-photo-confirm-message"></div>

                        <div class="device-photo-confirm-actions">
                            <button type="button" class="btn btn-default" id="device-photo-confirm-cancel">
                                Cancel
                            </button>
                            <button type="button" class="btn btn-primary" id="device-photo-confirm-ok">
                                Continue
                            </button>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var pendingForm = null;
                        var backdrop = document.getElementById('device-photo-confirm-backdrop');
                        var message = document.getElementById('device-photo-confirm-message');
                        var ok = document.getElementById('device-photo-confirm-ok');
                        var cancel = document.getElementById('device-photo-confirm-cancel');

                        if (!backdrop || !message || !ok || !cancel) {
                            return;
                        }

                        document.querySelectorAll('form[data-device-photo-confirm]').forEach(function (form) {
                            form.addEventListener('submit', function (e) {
                                if (form.getAttribute('data-device-photo-confirmed') === '1') {
                                    return;
                                }

                                e.preventDefault();

                                pendingForm = form;
                                message.textContent = form.getAttribute('data-device-photo-confirm') || 'Continue?';
                                backdrop.style.display = 'flex';
                            });
                        });

                        ok.addEventListener('click', function () {
                            if (!pendingForm) {
                                backdrop.style.display = 'none';
                                return;
                            }

                            pendingForm.setAttribute('data-device-photo-confirmed', '1');
                            backdrop.style.display = 'none';
                            pendingForm.submit();
                        });

                        cancel.addEventListener('click', function () {
                            pendingForm = null;
                            backdrop.style.display = 'none';
                        });

                        backdrop.addEventListener('click', function (e) {
                            if (e.target === backdrop) {
                                pendingForm = null;
                                backdrop.style.display = 'none';
                            }
                        });

                        document.addEventListener('keydown', function (e) {
                            if (e.key === 'Escape') {
                                pendingForm = null;
                                backdrop.style.display = 'none';
                            }
                        });
                    });
                </script>

                <hr>
                <h4>Broken links</h4>

                <p class="text-muted">
                    Broken links are photo link entries that point to an image file that no longer exists.
                    The target device still has a saved link, but the original photo file is missing.
                    Removing a broken link only removes the link entry. It does not delete any photo file.
                </p>

                @if (empty($overview['broken_links']))
                    <div class="alert alert-info">No broken links found.</div>
                @else

                    <div class="table-responsive">
                        <table class="table table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th>Target device</th>
                                    <th>Owner device</th>
                                    <th>File</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($overview['broken_links'] as $link)
                                    <tr>
                                        <td>
                                            <a href="{{ url('device/' . $link['target_device_id']) }}">
                                                <code>Device ID: {{ $link['target_device_id'] }}</code>
                                            </a>
                                            @if (!empty($link['target_name']))
                                                <br>
                                                <a href="{{ url('device/' . $link['target_device_id']) }}">
                                                    {{ $link['target_name'] }}
                                                </a>
                                            @endif
                                        </td>

                                        <td>
                                            @if (!empty($link['owner_name']))
                                                <a href="{{ url('device/' . $link['owner_device_id']) }}">
                                                    <code>Device ID: {{ $link['owner_device_id'] }}</code>
                                                </a>
                                                <br>
                                                <a href="{{ url('device/' . $link['owner_device_id']) }}">
                                                    {{ $link['owner_name'] }}
                                                </a>
                                            @else
                                                <code>Device ID: {{ $link['owner_device_id'] }}</code>
                                                <br>
                                                <span class="label label-warning">Missing device</span>
                                            @endif
                                        </td>

                                        <td>
                                            <code>{{ $link['filename'] }}</code>
                                            <br>
                                            <span class="label label-danger">Missing file</span>
                                        </td>

                                        <td>
                                            @if ($can_delete)
                                                <form method="post" action="{{ url('plugin/device-photo-package/action') }}" data-device-photo-confirm="Remove this broken photo link? The original photo file is already missing.">
                                                    @csrf
                                                    <input type="hidden" name="action" value="remove_broken_link">
                                                    <input type="hidden" name="return_to" value="overview">
                                                    <input type="hidden" name="target_device_id" value="{{ $link['target_device_id'] }}">
                                                    <input type="hidden" name="owner_device_id" value="{{ $link['owner_device_id'] }}">
                                                    <input type="hidden" name="filename" value="{{ $link['filename'] }}">

                                                    <button type="submit" class="btn btn-warning btn-xs">
                                                        <i class="fa fa-unlink"></i> Remove broken link
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted">No permission</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <script>
            (function () {
                var input = document.getElementById('device-photo-overview-filter');
                var pageSizeSelect = document.getElementById('device-photo-overview-page-size');
                var prevButton = document.getElementById('device-photo-overview-prev');
                var nextButton = document.getElementById('device-photo-overview-next');
                var pageInfo = document.getElementById('device-photo-overview-page-info');
                var pagination = document.getElementById('device-photo-overview-pagination');

                var currentPage = 1;
                var openLinkRows = {};
                var pageSizeStorageKey = 'devicePhotoOverviewPageSize';
                var sortState = {
                    key: 'device',
                    direction: 'asc'
                };

                function loadSavedPageSize() {
                    if (!pageSizeSelect) {
                        return;
                    }

                    try {
                        var saved = window.localStorage.getItem(pageSizeStorageKey);

                        if (!saved) {
                            return;
                        }

                        var option = pageSizeSelect.querySelector('option[value="' + saved + '"]');

                        if (option) {
                            pageSizeSelect.value = saved;
                        }
                    } catch (e) {
                        // Ignore localStorage errors.
                    }
                }

                function savePageSize() {
                    if (!pageSizeSelect) {
                        return;
                    }

                    try {
                        window.localStorage.setItem(pageSizeStorageKey, pageSizeSelect.value);
                    } catch (e) {
                        // Ignore localStorage errors.
                    }
                }

                function mainRows() {
                    return Array.prototype.slice.call(document.querySelectorAll('tr[data-device-photo-row]'));
                }

                function getPageSize() {
                    if (!pageSizeSelect || pageSizeSelect.value === 'all') {
                        return 'all';
                    }

                    var size = parseInt(pageSizeSelect.value, 10);

                    return isNaN(size) || size < 1 ? 25 : size;
                }

                function closeLinkRow(id) {
                    var linkRow = document.querySelector('[data-device-photo-links="' + id + '"]');
                    var button = document.querySelector('[data-device-photo-target="' + id + '"]');

                    if (linkRow) {
                        linkRow.style.display = 'none';
                    }

                    if (button) {
                        button.textContent = 'Show links';
                    }

                    delete openLinkRows[id];
                }

                function sortValue(row, key) {
                    if (key === 'owned') {
                        return parseInt(row.getAttribute('data-sort-owned') || '0', 10) || 0;
                    }

                    if (key === 'linked_in') {
                        return parseInt(row.getAttribute('data-sort-linked-in') || '0', 10) || 0;
                    }

                    if (key === 'linked_out') {
                        return parseInt(row.getAttribute('data-sort-linked-out') || '0', 10) || 0;
                    }

                    return (row.getAttribute('data-sort-device') || '').toLowerCase();
                }

                function sortOverviewRows() {
                    var rows = mainRows();
                    var tbody = document.querySelector('#device-photo-overview-table tbody');

                    if (!tbody) {
                        return;
                    }

                    rows.sort(function (a, b) {
                        var av = sortValue(a, sortState.key);
                        var bv = sortValue(b, sortState.key);
                        var result = 0;

                        if (typeof av === 'number' && typeof bv === 'number') {
                            result = av - bv;
                        } else {
                            result = String(av).localeCompare(String(bv), undefined, {
                                numeric: true,
                                sensitivity: 'base'
                            });
                        }

                        return sortState.direction === 'asc' ? result : -result;
                    });

                    rows.forEach(function (row) {
                        var id = row.getAttribute('data-device-photo-row');
                        var linkRow = document.querySelector('[data-device-photo-links="' + id + '"]');

                        tbody.appendChild(row);

                        if (linkRow) {
                            tbody.appendChild(linkRow);
                        }
                    });
                }

                function updateSortHeaders() {
                    document.querySelectorAll('.device-photo-sort-header').forEach(function (header) {
                        var key = header.getAttribute('data-sort-key');
                        var indicator = header.querySelector('.device-photo-sort-indicator');

                        header.classList.toggle('is-active', key === sortState.key);

                        if (!indicator) {
                            return;
                        }

                        if (key === sortState.key) {
                            indicator.textContent = sortState.direction === 'asc' ? '▲' : '▼';
                        } else {
                            indicator.textContent = '';
                        }
                    });
                }

                function applyOverviewState() {
                    sortOverviewRows();
                    updateSortHeaders();
                    if (!input) {
                        return;
                    }

                    var q = input.value.toLowerCase();
                    var rows = mainRows();
                    var matchedRows = [];

                    rows.forEach(function (row) {
                        var haystack = row.getAttribute('data-filter') || '';
                        var matches = haystack.indexOf(q) !== -1;

                        if (matches) {
                            matchedRows.push(row);
                        }
                    });

                    var pageSize = getPageSize();
                    var totalRows = matchedRows.length;
                    var totalPages = pageSize === 'all' ? 1 : Math.max(1, Math.ceil(totalRows / pageSize));

                    if (currentPage > totalPages) {
                        currentPage = totalPages;
                    }

                    if (currentPage < 1) {
                        currentPage = 1;
                    }

                    rows.forEach(function (row) {
                        var id = row.getAttribute('data-device-photo-row');
                        row.style.display = 'none';
                        closeLinkRow(id);
                    });

                    matchedRows.forEach(function (row, index) {
                        var visible = true;

                        if (pageSize !== 'all') {
                            var start = (currentPage - 1) * pageSize;
                            var end = start + pageSize;
                            visible = index >= start && index < end;
                        }

                        if (!visible) {
                            return;
                        }

                        row.style.display = '';

                        var id = row.getAttribute('data-device-photo-row');
                        var linkRow = document.querySelector('[data-device-photo-links="' + id + '"]');
                        var button = document.querySelector('[data-device-photo-target="' + id + '"]');

                        if (linkRow && openLinkRows[id]) {
                            linkRow.style.display = 'table-row';
                        }

                        if (button) {
                            button.textContent = openLinkRows[id] ? 'Hide links' : 'Show links';
                        }
                    });

                    if (pageInfo) {
                        if (totalRows === 0) {
                            pageInfo.textContent = 'No matching devices';
                        } else if (pageSize === 'all') {
                            pageInfo.textContent = 'Showing all ' + totalRows + ' device' + (totalRows === 1 ? '' : 's');
                        } else {
                            var startNumber = ((currentPage - 1) * pageSize) + 1;
                            var endNumber = Math.min(currentPage * pageSize, totalRows);
                            pageInfo.textContent = 'Showing ' + startNumber + '-' + endNumber + ' of ' + totalRows + ' · Page ' + currentPage + ' of ' + totalPages;
                        }
                    }

                    if (pagination) {
                        pagination.style.display = totalRows === 0 ? 'none' : 'flex';
                    }

                    if (prevButton) {
                        prevButton.disabled = pageSize === 'all' || currentPage <= 1;
                    }

                    if (nextButton) {
                        nextButton.disabled = pageSize === 'all' || currentPage >= totalPages;
                    }
                }

                document.querySelectorAll('.device-photo-toggle-links').forEach(function (button) {
                    button.addEventListener('click', function () {
                        var id = button.getAttribute('data-device-photo-target');
                        var row = document.querySelector('[data-device-photo-links="' + id + '"]');

                        if (!row) {
                            return;
                        }

                        var isOpen = row.style.display === 'table-row';

                        if (isOpen) {
                            closeLinkRow(id);
                        } else {
                            openLinkRows[id] = true;
                            row.style.display = 'table-row';
                            button.textContent = 'Hide links';
                        }
                    });
                });

                if (input) {
                    input.addEventListener('input', function () {
                        currentPage = 1;
                        openLinkRows = {};
                        applyOverviewState();
                    });
                }

                document.querySelectorAll('.device-photo-sort-header').forEach(function (header) {
                    header.addEventListener('click', function () {
                        var key = header.getAttribute('data-sort-key');

                        if (!key) {
                            return;
                        }

                        if (sortState.key === key) {
                            sortState.direction = sortState.direction === 'asc' ? 'desc' : 'asc';
                        } else {
                            sortState.key = key;
                            sortState.direction = key === 'device' ? 'asc' : 'desc';
                        }

                        currentPage = 1;
                        openLinkRows = {};
                        applyOverviewState();
                    });
                });

                if (pageSizeSelect) {
                    pageSizeSelect.addEventListener('change', function () {
                        savePageSize();
                        currentPage = 1;
                        openLinkRows = {};
                        applyOverviewState();
                    });
                }

                if (prevButton) {
                    prevButton.addEventListener('click', function () {
                        currentPage--;
                        openLinkRows = {};
                        applyOverviewState();
                    });
                }

                if (nextButton) {
                    nextButton.addEventListener('click', function () {
                        currentPage++;
                        openLinkRows = {};
                        applyOverviewState();
                    });
                }

                loadSavedPageSize();
                applyOverviewState();
            })();
        </script>
    @else

    <style>
        .device-photo-confirm-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            z-index: 20000;
            align-items: center;
            justify-content: center;
        }

        .device-photo-confirm-box {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.35);
            width: 420px;
            max-width: calc(100vw - 40px);
            padding: 18px 20px;
            border-top: 5px solid #337ab7;
        }

        .device-photo-confirm-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .device-photo-confirm-message {
            font-size: 14px;
            line-height: 1.45;
            margin-bottom: 18px;
            color: #333;
        }

        .device-photo-confirm-actions {
            text-align: right;
        }

        .device-photo-confirm-actions .btn {
            margin-left: 6px;
        }
    </style>

    <div id="device-photo-confirm-backdrop" class="device-photo-confirm-backdrop">
        <div class="device-photo-confirm-box">
            <div class="device-photo-confirm-title">
                <i class="fa fa-exclamation-circle"></i> Confirm action
            </div>

            <div id="device-photo-confirm-message" class="device-photo-confirm-message"></div>

            <div class="device-photo-confirm-actions">
                <button type="button" class="btn btn-default" id="device-photo-confirm-cancel">
                    Cancel
                </button>
                <button type="button" class="btn btn-primary" id="device-photo-confirm-ok">
                    Continue
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var pendingForm = null;
            var backdrop = document.getElementById('device-photo-confirm-backdrop');
            var message = document.getElementById('device-photo-confirm-message');
            var ok = document.getElementById('device-photo-confirm-ok');
            var cancel = document.getElementById('device-photo-confirm-cancel');

            if (!backdrop || !message || !ok || !cancel) {
                return;
            }

            document.querySelectorAll('form[data-device-photo-confirm]').forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    if (form.getAttribute('data-device-photo-confirmed') === '1') {
                        return;
                    }

                    e.preventDefault();

                    pendingForm = form;
                    message.textContent = form.getAttribute('data-device-photo-confirm') || 'Continue?';
                    backdrop.style.display = 'flex';
                });
            });

            ok.addEventListener('click', function () {
                if (!pendingForm) {
                    backdrop.style.display = 'none';
                    return;
                }

                pendingForm.setAttribute('data-device-photo-confirmed', '1');
                backdrop.style.display = 'none';
                pendingForm.submit();
            });

            cancel.addEventListener('click', function () {
                pendingForm = null;
                backdrop.style.display = 'none';
            });

            backdrop.addEventListener('click', function (e) {
                if (e.target === backdrop) {
                    pendingForm = null;
                    backdrop.style.display = 'none';
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    pendingForm = null;
                    backdrop.style.display = 'none';
                }
            });
        });
    </script>

    @if (!$device)
        <div class="alert alert-warning">
            No device selected.
        </div>
    @else
        @if (!($can_manage ?? false))
            <div class="alert alert-danger">
                You do not have permission to manage photos for this device.
            </div>
        @else
        <style>
            .device-photo-manager-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(170px, 230px));
                gap: 14px;
            }

            .device-photo-manager-card {
                background: #f3f3f3;
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 10px;
                cursor: grab;
            }

            .device-photo-manager-card.dragging {
                opacity: 0.45;
                cursor: grabbing;
            }

            .device-photo-manager-card.drop-before {
                box-shadow: inset 10px 0 0 #337ab7;
            }

            .device-photo-manager-card.drop-after {
                box-shadow: inset -10px 0 0 #337ab7;
            }

            .device-photo-manager-card img {
                width: 100%;
                max-height: 180px;
                object-fit: contain;
                background: #fff;
                border-radius: 5px;
                margin-bottom: 10px;
                pointer-events: auto;
                cursor: zoom-in;
            }

            .device-photo-drag-hint {
                color: #777;
                margin-bottom: 12px;
                font-size: 13px;
            }
        </style>

        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>{{ $device->display ?? $device->hostname }}</strong>

                <div class="text-muted" style="margin-top: 4px; font-size: 12px;">
                    Device ID:
                    <code>{{ $device->device_id }}</code>

                    @if (!empty($device->sysName))
                        &nbsp;|&nbsp; SysName:
                        <code>{{ $device->sysName }}</code>
                    @endif

                    @if (!empty($device->hostname))
                        &nbsp;|&nbsp; Hostname:
                        <code>{{ $device->hostname }}</code>
                    @endif
                </div>
            </div>

            <div class="panel-body">
@if ($can_upload)
                <form method="post" action="{{ url('plugin/device-photo-package/action') }}" enctype="multipart/form-data" style="margin-bottom: 28px;" id="device-photo-upload-form">
                    @csrf
                    <input type="hidden" name="action" value="upload">
                    <input type="hidden" name="device_id" value="{{ $device->device_id }}">

                    <label style="display: block; margin-bottom: 8px;">Upload photos</label>

                    <style>
                        .device-photo-dropzone {
                            border: 2px dashed #b8b8b8;
                            border-radius: 10px;
                            background: #f7f7f7;
                            padding: 34px 20px;
                            text-align: center;
                            color: #555;
                            cursor: pointer;
                            transition: background 0.15s ease, border-color 0.15s ease;
                        }

                        .device-photo-dropzone.drag-active {
                            background: #eaf4ff;
                            border-color: #337ab7;
                        }

                        .device-photo-dropzone .main-text {
                            font-size: 16px;
                            font-weight: bold;
                            margin-bottom: 6px;
                        }

                        .device-photo-dropzone .sub-text {
                            font-size: 13px;
                            color: #777;
                        }

                        .device-photo-selected-files {
                            margin-top: 10px;
                            color: #555;
                            font-size: 13px;
                        }

                        .device-photo-file-list {
                            margin-top: 10px;
                            padding: 10px 12px;
                            background: #fff;
                            border: 1px solid #ddd;
                            border-radius: 6px;
                            display: none;
                        }

                        .device-photo-file-list ul {
                            margin: 6px 0 0 18px;
                            padding: 0;
                        }

                        .device-photo-file-list li {
                            margin-bottom: 3px;
                        }
                    </style>

                    <div id="device-photo-dropzone" class="device-photo-dropzone">
                        <div class="main-text">
                            <i class="fa fa-cloud-upload"></i> Drag and drop photos here
                        </div>
                        <div class="sub-text">
                            or click to browse for photos
                        </div>

                        <input
                            id="device-photo-file"
                            type="file"
                            name="photos[]"
                            multiple
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            style="display: none;"
                        >
                    </div>

                    <div id="device-photo-selected" class="device-photo-selected-files">
                        No photos selected
                    </div>

                    <div id="device-photo-file-list" class="device-photo-file-list">
                        <strong>Ready to upload:</strong>
                        <ul id="device-photo-file-list-items"></ul>
                    </div>

                    <div style="margin-top: 12px;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-upload"></i> Upload photos
                        </button>
                    </div>

                    <div class="text-muted" style="margin-top: 8px; font-size: 12px;">
                        Allowed file types: jpg, jpeg, png, webp, heic, heif. Max file size: 10 MB per file.
                    </div>

                    <div class="alert alert-info" style="margin-top: 12px; margin-bottom: 0; font-size: 12px; padding: 8px 10px;">
                        <div style="display: flex; flex-wrap: wrap; gap: 6px 8px; align-items: center;">
                            <strong style="margin-right: 4px;">Upload status:</strong>

                            <span class="label label-info" title="PHP upload_max_filesize">
                                upload_max_filesize: {{ $php_upload_max_filesize ?? 'unknown' }}
                            </span>

                            <span class="label label-info" title="PHP post_max_size">
                                post_max_size: {{ $php_post_max_size ?? 'unknown' }}
                            </span>

                            <span class="label {{ !empty($php_file_uploads) ? 'label-success' : 'label-danger' }}" title="PHP file_uploads">
                                file_uploads: {{ !empty($php_file_uploads) ? 'Enabled' : 'Disabled' }}
                            </span>

                            <span class="label {{ !empty($heic_conversion_available) ? 'label-success' : 'label-warning' }}" title="HEIC/HEIF files are converted to JPG during upload when available.">
                                HEIC/HEIF: {{ !empty($heic_conversion_available) ? 'Available' : 'Not available' }}
                            </span>

                            <span class="label {{ !empty($exiftool_available) ? 'label-success' : 'label-warning' }}" title="ExifTool is used to write Photo taken metadata back to JPG/JPEG files.">
                                ExifTool: {{ !empty($exiftool_available) ? 'Available' : 'Not available' }}
                            </span>
                        </div>

                        <div style="margin-top: 8px; color: #31708f;">
                            Webserver upload limit must also be high enough.
                            Examples: Nginx <code>client_max_body_size</code>, Apache <code>LimitRequestBody</code>.
                        </div>
                    </div>
                </form>
                @else
                    <div class="alert alert-warning">
                        You do not have permission to upload photos.
                    </div>
                @endif

                @if ($can_upload)
                <script>
                    (function () {
                        var dropzone = document.getElementById('device-photo-dropzone');
                        var fileInput = document.getElementById('device-photo-file');
                        var selected = document.getElementById('device-photo-selected');

                        function updateSelectedText() {
                            var fileListBox = document.getElementById('device-photo-file-list');
                            var fileListItems = document.getElementById('device-photo-file-list-items');

                            fileListItems.innerHTML = '';

                            if (!fileInput.files || fileInput.files.length === 0) {
                                selected.textContent = 'No photos selected';
                                fileListBox.style.display = 'none';
                                return;
                            }

                            if (fileInput.files.length === 1) {
                                selected.textContent = '1 photo selected';
                            } else {
                                selected.textContent = fileInput.files.length + ' photos selected';
                            }

                            Array.prototype.forEach.call(fileInput.files, function (file) {
                                var li = document.createElement('li');
                                var sizeMb = file.size / 1024 / 1024;
                                li.textContent = file.name + ' (' + sizeMb.toFixed(2) + ' MB)';
                                fileListItems.appendChild(li);
                            });

                            fileListBox.style.display = 'block';
                        }

                        dropzone.addEventListener('click', function () {
                            fileInput.click();
                        });

                        fileInput.addEventListener('change', updateSelectedText);

                        dropzone.addEventListener('dragover', function (e) {
                            e.preventDefault();
                            dropzone.classList.add('drag-active');
                        });

                        dropzone.addEventListener('dragleave', function () {
                            dropzone.classList.remove('drag-active');
                        });

                        dropzone.addEventListener('drop', function (e) {
                            e.preventDefault();
                            dropzone.classList.remove('drag-active');

                            if (!e.dataTransfer || !e.dataTransfer.files || e.dataTransfer.files.length === 0) {
                                return;
                            }

                            var dt = new DataTransfer();

                            /*
                             * Keep files already selected, then append newly dropped files.
                             * This makes multiple drag/drop rounds work as expected.
                             */
                            Array.prototype.forEach.call(fileInput.files || [], function (file) {
                                dt.items.add(file);
                            });

                            Array.prototype.forEach.call(e.dataTransfer.files, function (file) {
                                dt.items.add(file);
                            });

                            fileInput.files = dt.files;
                            updateSelectedText();
                        });

                        updateSelectedText();
                    })();
                </script>
                @endif

                <hr>

                <style>
                    .device-photo-target-suggestions {
                        display: none;
                        position: absolute;
                        z-index: 9999;
                        left: 0;
                        bottom: 100%;
                        margin-bottom: 4px;
                        background: #fff;
                        border: 1px solid #ccc;
                        border-radius: 4px;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                        max-height: 320px;
                        overflow-y: auto;
                        min-width: 260px;
                        width: 100%;
                        font-size: 12px;
                    }

                    .device-photo-target-suggestion {
                        padding: 6px 8px;
                        cursor: pointer;
                        border-bottom: 1px solid #eee;
                    }

                    .device-photo-target-suggestion:hover {
                        background: #f3f7fb;
                    }

                    .device-photo-target-suggestion .device-id {
                        font-family: monospace;
                        color: #b00040;
                    }

                    .device-photo-target-suggestion .device-name {
                        margin-left: 6px;
                    }
                </style>

                <script>
                    window.DevicePhotoTargetDevices = @json(
                        collect($link_target_devices ?? [])
                            ->filter(fn ($targetDevice) => $device && (int) $targetDevice['device_id'] !== (int) $device->device_id)
                            ->values()
                    );

                    window.DevicePhotoOwnerDevices = @json(
                        collect($link_owner_devices ?? [])
                            ->filter(fn ($ownerDevice) => $device && (int) $ownerDevice['device_id'] !== (int) $device->device_id)
                            ->values()
                    );

                    document.addEventListener('DOMContentLoaded', function () {
                        var maxResults = 20;
                        var devices = window.DevicePhotoTargetDevices || [];

                        function normalize(value) {
                            return String(value || '').toLowerCase();
                        }

                        function findMatches(query, devices) {
                            query = String(query || '').trim();

                            if (query === '') {
                                return [];
                            }

                            var q = normalize(query);
                            var exactId = [];
                            var startsWithId = [];
                            var startsWithName = [];
                            var containsName = [];

                            devices.forEach(function (device) {
                                var id = String(device.device_id);
                                var label = String(device.label || '');
                                var labelLower = normalize(label);

                                if (id === query) {
                                    exactId.push(device);
                                } else if (id.indexOf(query) === 0) {
                                    startsWithId.push(device);
                                } else if (labelLower.indexOf(q) === 0) {
                                    startsWithName.push(device);
                                } else if (labelLower.indexOf(q) !== -1) {
                                    containsName.push(device);
                                }
                            });

                            return exactId
                                .concat(startsWithId)
                                .concat(startsWithName)
                                .concat(containsName)
                                .slice(0, maxResults);
                        }

                        function closeAllSuggestions() {
                            document.querySelectorAll('.device-photo-target-suggestions').forEach(function (box) {
                                box.style.display = 'none';
                            });
                        }

                        function ensureSuggestionBox(input) {
                            var wrapper = input.closest('.input-group');
                            var box = wrapper.parentNode.querySelector('.device-photo-target-suggestions');

                            if (!box) {
                                box = document.createElement('div');
                                box.className = 'device-photo-target-suggestions';
                                wrapper.parentNode.appendChild(box);
                            }

                            return box;
                        }

                        function renderSuggestions(input) {
                            var box = ensureSuggestionBox(input);
                            var devices = input.classList.contains('device-photo-owner-input') ? ownerDevices : targetDevices;
                            var matches = findMatches(input.value, devices);

                            box.innerHTML = '';

                            if (matches.length === 0) {
                                box.style.display = 'none';
                                return;
                            }

                            matches.forEach(function (device) {
                                var item = document.createElement('div');
                                item.className = 'device-photo-target-suggestion';

                                var id = document.createElement('span');
                                id.className = 'device-id';
                                id.textContent = device.device_id;

                                var name = document.createElement('span');
                                name.className = 'device-name';
                                name.textContent = device.label || '';

                                item.appendChild(id);
                                item.appendChild(document.createTextNode(' - '));
                                item.appendChild(name);

                                item.addEventListener('mousedown', function (e) {
                                    e.preventDefault();
                                    input.value = device.device_id + ' - ' + (device.label || '');
                                    box.style.display = 'none';
                                });

                                box.appendChild(item);
                            });

                            box.style.display = 'block';
                        }

                        document.querySelectorAll('.device-photo-target-input, .device-photo-owner-input').forEach(function (input) {
                            input.addEventListener('input', function () {
                                renderSuggestions(input);
                            });

                            input.addEventListener('focus', function () {
                                renderSuggestions(input);
                            });

                            input.addEventListener('keydown', function (e) {
                                if (e.key === 'Escape') {
                                    closeAllSuggestions();
                                }
                            });
                        });

                        document.addEventListener('click', function (e) {
                            if (!e.target.closest('.device-photo-target-suggestions') && !e.target.closest('.device-photo-target-input') && !e.target.closest('.device-photo-owner-input')) {
                                closeAllSuggestions();
                            }
                        });
                    });
                </script>

                <h4 style="margin-bottom: 8px;">Photos owned by this device</h4>

                @if (count($photos) === 0)
                    <div class="alert alert-info">No device photo found</div>
                @else
                    @if ($can_reorder)
                    <div class="device-photo-drag-hint">
                        Drag and drop photos to change the order. The order is saved automatically.
                    </div>

                    <form method="post" action="{{ url('plugin/device-photo-package/action') }}" id="device-photo-order-form" style="margin-bottom: 14px;">
                        @csrf
                        <input type="hidden" name="action" value="save_order">
                        <input type="hidden" name="device_id" value="{{ $device->device_id }}">
                        <input type="hidden" name="order_json" id="device-photo-order-json" value="[]">

                        <button type="submit" class="btn btn-success btn-sm" style="display: none;">
                            <i class="fa fa-save"></i> Save order
                        </button>
                    </form>

                    @else
                    <div class="device-photo-drag-hint">
                        You do not have permission to reorder photos.
                    </div>
                    @endif

                    <div class="device-photo-manager-grid" id="device-photo-manager-grid">
                        @foreach ($photos as $photo)
                            <div class="device-photo-manager-card" draggable="{{ $can_reorder ? 'true' : 'false' }}" data-filename="{{ $photo['filename'] }}">
                                <img
                                    data-device-photo-preview-src="{{ $photo['url'] }}"
                                    data-device-photo-taken="{{ $photo['photo_taken_iso'] ?? '' }}"
                                    data-device-photo-file-date="{{ $photo['file_date_iso'] ?? '' }}"
                                    src="{{ $photo['thumb_url'] ?? $photo['url'] }}"
                                    draggable="false"
                                >

                                <div class="text-muted" style="font-size: 12px; margin: 6px 0 8px 0; line-height: 1.35; word-break: break-all;">
                                    @if (!empty($photo['photo_taken_display']))
                                        <div>
                                            <strong>Photo taken:</strong>
                                            <span class="device-photo-local-date" data-device-photo-format="date" data-device-photo-date="{{ $photo['photo_taken_iso'] ?? '' }}">{{ $photo['photo_taken_display'] }}</span>
                                        </div>
                                    @endif

                                    @if (!empty($photo['file_date_display']))
                                        <div title="File timestamp on the LibreNMS server. This may change if files are copied, restored or modified.">
                                            <strong>File date:</strong>
                                            <span class="device-photo-local-date" data-device-photo-format="date" data-device-photo-date="{{ $photo['file_date_iso'] ?? '' }}">{{ $photo['file_date_display'] }}</span>
                                        </div>
                                    @endif

                                    <div title="Stored filename on the LibreNMS server.">
                                        <strong>Filename:</strong>
                                        <span>{{ $photo['filename'] }}</span>
                                    </div>
                                </div>

                                @if (!empty($photo['linked_to']))
                                    <div class="alert alert-warning" style="font-size: 12px; padding: 6px 8px; margin-bottom: 8px;">
                                        <strong>
                                            <i class="fa fa-link"></i>
                                            Linked to {{ count($photo['linked_to']) }} device{{ count($photo['linked_to']) === 1 ? '' : 's' }}
                                        </strong>

                                        <div style="margin-top: 8px;">
                                            @foreach ($photo['linked_to'] as $linkedDevice)
                                                <div style="margin-bottom: 8px; padding-bottom: 8px; border-bottom: 1px solid #eadfbf;">
                                                    <div>
                                                        <a href="{{ url('device/' . $linkedDevice['device_id']) }}">
                                                            <code>Device ID: {{ $linkedDevice['device_id'] }}</code>
                                                        </a>
                                                    </div>

                                                    @if (!empty($linkedDevice['name']))
                                                        <div style="margin-top: 2px; word-break: break-word;">
                                                            <a href="{{ url('device/' . $linkedDevice['device_id']) }}">
                                                                {{ $linkedDevice['name'] }}
                                                            </a>
                                                        </div>
                                                    @endif

                                                    @if ($can_delete)
                                                        <form method="post" action="{{ url('plugin/device-photo-package/action') }}" style="margin-top: 6px;" data-device-photo-confirm="Remove this link? The original photo will not be deleted.">
                                                            @csrf
                                                            <input type="hidden" name="action" value="remove_outgoing_link">
                                                            <input type="hidden" name="device_id" value="{{ $device->device_id }}">
                                                            <input type="hidden" name="target_device_id" value="{{ $linkedDevice['device_id'] }}">
                                                            <input type="hidden" name="filename" value="{{ $photo['filename'] }}">

                                                            <button type="submit" class="btn btn-warning btn-xs">
                                                                <i class="fa fa-unlink"></i> Remove link
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <a href="{{ $photo['url'] }}" download="{{ $photo['filename'] }}" class="btn btn-default btn-sm btn-block" style="margin-bottom: 8px;">
                                    <i class="fa fa-download"></i> Download
                                </a>

                                @php
                                    $devicePhotoCanWriteExif = !empty($exiftool_available) && preg_match('/\\.(jpe?g)$/i', $photo['filename']);
                                @endphp

                                @if ($can_upload && $devicePhotoCanWriteExif)
                                    <button
                                        type="button"
                                        class="btn btn-default btn-sm btn-block device-photo-set-taken-button"
                                        style="margin-bottom: 8px;"
                                        data-filename="{{ $photo['filename'] }}"
                                        data-photo-taken="{{ !empty($photo['photo_taken_iso']) ? substr($photo['photo_taken_iso'], 0, 16) : '' }}"
                                        data-device-id="{{ $device->device_id }}"
                                        title="Write Photo taken to EXIF metadata"
                                    >
                                        <i class="fa fa-clock-o"></i> Set photo taken
                                    </button>
                                @elseif ($can_upload && empty($exiftool_available) && preg_match('/\\.(jpe?g)$/i', $photo['filename']))
                                    <div class="text-muted" style="font-size: 12px; margin-bottom: 8px;">
                                        <i class="fa fa-clock-o"></i> Photo taken editing requires ExifTool.
                                    </div>
                                @endif

                                @if ($can_upload)
                                <div class="text-muted" style="font-size: 12px; margin-bottom: 4px;">
                                    <i class="fa fa-link"></i> Link this photo to another device
                                </div>

                                <form method="post" action="{{ url('plugin/device-photo-package/action') }}" style="margin-bottom: 8px; position: relative;">
                                    @csrf
                                    <input type="hidden" name="action" value="add_link">
                                    <input type="hidden" name="device_id" value="{{ $device->device_id }}">
                                    <input type="hidden" name="filename" value="{{ $photo['filename'] }}">

                                    <div class="input-group input-group-sm">
                                        <input
                                            type="text"
                                            name="target_device_query"
                                            class="form-control device-photo-target-input"
                                            placeholder="Search device ID or name"
                                            autocomplete="off"
                                            required
                                        >
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn btn-default" title="Add link">
                                                <i class="fa fa-link"></i>
                                            </button>
                                        </span>
                                    </div>
                                </form>
                                @endif

                                @if ($can_delete)
                                @php
                                    $linkedCount = count($photo['linked_to'] ?? []);

                                    if ($linkedCount > 0) {
                                        $deleteConfirm = 'This photo is linked to ' . $linkedCount . ' other device(s). Deleting it will move the photo to the deleted folder and remove all links to it. Continue?';
                                    } else {
                                        $deleteConfirm = 'Delete this photo? It will be moved to the deleted folder.';
                                    }
                                @endphp

                                <form method="post" action="{{ url('plugin/device-photo-package/action') }}" class="device-photo-delete-form" data-device-photo-confirm="{{ $deleteConfirm }}">
                                    @csrf
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="device_id" value="{{ $device->device_id }}">
                                    <input type="hidden" name="filename" value="{{ $photo['filename'] }}">

                                    <button type="submit" class="btn btn-danger btn-sm btn-block">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </form>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <script>
                        (function () {
                            function formatDevicePhotoDates() {
                                document.querySelectorAll('.device-photo-local-date[data-device-photo-date]').forEach(function (el) {
                                    var value = el.getAttribute('data-device-photo-date');

                                    if (!value) {
                                        return;
                                    }

                                    var date = new Date(value);

                                    if (isNaN(date.getTime())) {
                                        return;
                                    }

                                    var format = el.getAttribute('data-device-photo-format') || 'datetime';

                                    if (format === 'date') {
                                        el.textContent = date.toLocaleDateString(undefined, {
                                            year: 'numeric',
                                            month: '2-digit',
                                            day: '2-digit'
                                        });
                                    } else {
                                        el.textContent = date.toLocaleString(undefined, {
                                            year: 'numeric',
                                            month: '2-digit',
                                            day: '2-digit',
                                            hour: '2-digit',
                                            minute: '2-digit'
                                        });
                                    }
                                });
                            }

                            if (document.readyState === 'loading') {
                                document.addEventListener('DOMContentLoaded', formatDevicePhotoDates);
                            } else {
                                formatDevicePhotoDates();
                            }
                        })();
                    </script>

                    @if ($can_reorder)
                    <script>
                        (function () {
                            var grid = document.getElementById('device-photo-manager-grid');
                            var orderInput = document.getElementById('device-photo-order-json');
                            var dragged = null;

                            function cards() {
                                return Array.prototype.slice.call(grid.querySelectorAll('.device-photo-manager-card'));
                            }

                            function updateOrderJson() {
                                var order = cards().map(function (card) {
                                    return card.getAttribute('data-filename');
                                });

                                orderInput.value = JSON.stringify(order);
                            }

                            function clearDropClasses() {
                                cards().forEach(function (card) {
                                    card.classList.remove('drop-before');
                                    card.classList.remove('drop-after');
                                });
                            }

                            grid.addEventListener('dragstart', function (e) {
                                var card = e.target.closest('.device-photo-manager-card');

                                if (!card) {
                                    return;
                                }

                                dragged = card;
                                card.classList.add('dragging');
                                e.dataTransfer.effectAllowed = 'move';
                                e.dataTransfer.setData('text/plain', card.getAttribute('data-filename'));
                            });

                            grid.addEventListener('dragend', function () {
                                if (dragged) {
                                    dragged.classList.remove('dragging');
                                }

                                clearDropClasses();
                                dragged = null;
                                updateOrderJson();
                            });

                            grid.addEventListener('dragover', function (e) {
                                e.preventDefault();

                                if (!dragged) {
                                    return;
                                }

                                var target = e.target.closest('.device-photo-manager-card');

                                clearDropClasses();

                                if (!target || target === dragged) {
                                    return;
                                }

                                var box = target.getBoundingClientRect();
                                var isAfter = e.clientX > box.left + box.width / 2;

                                if (isAfter) {
                                    target.classList.add('drop-after');
                                } else {
                                    target.classList.add('drop-before');
                                }

                                e.dataTransfer.dropEffect = 'move';
                            });

                            grid.addEventListener('drop', function (e) {
                                e.preventDefault();

                                if (!dragged) {
                                    return;
                                }

                                var target = e.target.closest('.device-photo-manager-card');

                                if (!target || target === dragged) {
                                    clearDropClasses();
                                    updateOrderJson();
                                    return;
                                }

                                var box = target.getBoundingClientRect();
                                var isAfter = e.clientX > box.left + box.width / 2;

                                if (isAfter) {
                                    target.parentNode.insertBefore(dragged, target.nextSibling);
                                } else {
                                    target.parentNode.insertBefore(dragged, target);
                                }

                                clearDropClasses();
                                updateOrderJson();

                                /*
                                 * Auto-save order after drag and drop.
                                 */
                                setTimeout(function () {
                                    document.getElementById('device-photo-order-form').submit();
                                }, 150);
                            });

                            document.getElementById('device-photo-order-form').addEventListener('submit', function () {
                                updateOrderJson();
                            });

                            updateOrderJson();
                        })();
                    </script>
                    @endif
                @endif

                @if ($can_upload)
                    <hr>

                    <h4 id="device-photo-incoming-link" style="margin-bottom: 8px;">Add linked photo from another device</h4>

                    <div class="device-photo-drag-hint">
                        Search for a device that owns the photo, then link one of its photos to this device.
                    </div>

                    <form method="get" action="{{ url('plugin/device-photo') }}" style="margin-bottom: 14px; position: relative;">
                        <input type="hidden" name="device_id" value="{{ $device->device_id }}">

                        <div class="input-group input-group-sm" style="max-width: 520px;">
                            <input
                                type="text"
                                name="owner_device_query"
                                class="form-control device-photo-owner-input"
                                placeholder="Owner device ID or name"
                                value="{{ $incoming_owner_query ?? '' }}"
                                autocomplete="off"
                            >
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-default">
                                    <i class="fa fa-search"></i> Search photos
                                </button>
                            </span>
                        </div>
                    </form>

                    @if (!empty($incoming_owner_query) && empty($incoming_owner_device))
                        <div class="alert alert-warning" style="font-size: 12px;">
                            No unique owner device found. Try using the exact Device ID.
                        </div>
                    @endif

                    @if (!empty($incoming_owner_device))
                        <div class="alert alert-info" style="font-size: 12px; max-width: 720px;">
                            Showing photos from:
                            <a href="{{ url('device/' . $incoming_owner_device->device_id) }}">
                                <code>Device ID: {{ $incoming_owner_device->device_id }}</code>
                            </a>
                            -
                            {{ method_exists($incoming_owner_device, 'getAttribute') ? (($incoming_owner_device->sysName ?? $incoming_owner_device->display ?? $incoming_owner_device->hostname) ?? '') : '' }}
                        </div>

                        @if (empty($incoming_owner_photos) || count($incoming_owner_photos) < 1)
                            <div class="alert alert-info" style="font-size: 12px;">
                                This owner device has no photos.
                            </div>
                        @else
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 230px)); gap: 14px; margin-bottom: 18px;">
                                @foreach ($incoming_owner_photos as $ownerPhoto)
                                    <div class="device-photo-incoming-owner-card" style="background: #f3f3f3; border: 1px solid #ddd; border-radius: 8px; padding: 10px;">
                                        <img
                                            data-device-photo-preview-src="{{ $ownerPhoto['url'] }}"
                                    data-device-photo-taken="{{ $ownerPhoto['photo_taken_iso'] ?? '' }}"
                                    data-device-photo-file-date="{{ $ownerPhoto['file_date_iso'] ?? '' }}"
                                            src="{{ $ownerPhoto['thumb_url'] ?? $ownerPhoto['url'] }}"
                                            style="width: 100%; max-height: 180px; object-fit: contain; background: #fff; border-radius: 5px; margin-bottom: 10px;"
                                        >

                                        <form method="post" action="{{ url('plugin/device-photo-package/action') }}">
                                            @csrf
                                            <input type="hidden" name="action" value="add_incoming_link">
                                            <input type="hidden" name="device_id" value="{{ $device->device_id }}">
                                            <input type="hidden" name="owner_device_id" value="{{ $incoming_owner_device->device_id }}">
                                            <input type="hidden" name="filename" value="{{ $ownerPhoto['filename'] }}">
                                            <input type="hidden" name="owner_device_query" value="{{ $incoming_owner_query ?? '' }}">

                                            <button type="submit" class="btn btn-default btn-sm btn-block">
                                                <i class="fa fa-link"></i> Link this photo
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                @endif

                @if (!empty($linked_photos) && count($linked_photos) > 0)
                    <hr>

                    <h4 style="margin-bottom: 8px;">Linked photos</h4>

                    <div class="device-photo-drag-hint">
                        These photos are owned by other devices, but are linked to this device.
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 230px)); gap: 14px;">
                        @foreach ($linked_photos as $photo)
                            <div class="device-photo-linked-photo-card" style="background: #f3f3f3; border: 1px solid #ddd; border-radius: 8px; padding: 10px;">
                                <img
                                    data-device-photo-preview-src="{{ $photo['url'] }}"
                                    data-device-photo-taken="{{ $photo['photo_taken_iso'] ?? '' }}"
                                    data-device-photo-file-date="{{ $photo['file_date_iso'] ?? '' }}"
                                    src="{{ $photo['thumb_url'] ?? $photo['url'] }}"
                                    style="width: 100%; max-height: 180px; object-fit: contain; background: #fff; border-radius: 5px; margin-bottom: 10px;"
                                >

                                <div class="alert alert-info device-photo-linked-owner-box" style="font-size: 12px; padding: 6px 8px; margin-bottom: 8px;">
                                    <strong>
                                        <i class="fa fa-link"></i>
                                        Linked from
                                    </strong>

                                    <div style="margin-top: 8px;">
                                        <div>
                                            <a href="{{ url('device/' . $photo['owner_device_id']) }}">
                                                <code>Device ID: {{ $photo['owner_device_id'] }}</code>
                                            </a>
                                        </div>

                                        @if (!empty($photo['owner_name']))
                                            <div style="margin-top: 2px; word-break: break-word;">
                                                <a href="{{ url('device/' . $photo['owner_device_id']) }}">
                                                    {{ $photo['owner_name'] }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <a href="{{ $photo['url'] }}" download="{{ $photo['filename'] }}" class="btn btn-default btn-sm btn-block" style="margin-bottom: 8px;">
                                    <i class="fa fa-download"></i> Download
                                </a>

                                @if ($can_delete)
                                <form method="post" action="{{ url('plugin/device-photo-package/action') }}" data-device-photo-confirm="Remove this linked photo from this device? The original photo will not be deleted.">
                                    @csrf
                                    <input type="hidden" name="action" value="remove_link">
                                    <input type="hidden" name="device_id" value="{{ $device->device_id }}">
                                    <input type="hidden" name="owner_device_id" value="{{ $photo['owner_device_id'] }}">
                                    <input type="hidden" name="filename" value="{{ $photo['filename'] }}">

                                    <button type="submit" class="btn btn-warning btn-sm btn-block">
                                        <i class="fa fa-unlink"></i> Remove link
                                    </button>
                                </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        @endif
    @endif
    @endif

    <div id="device-photo-set-taken-modal" class="device-photo-confirm-backdrop">
        <div class="device-photo-confirm-box" style="max-width: 460px; padding-bottom: 18px;">
            <h4 style="margin-top: 0;">
                <i class="fa fa-clock-o"></i> Set photo taken
            </h4>

            <div class="alert alert-warning" style="font-size: 12px; padding: 8px 10px; margin-bottom: 12px;">
                <strong>Warning:</strong>
                This writes the selected date/time back to the JPG/JPEG EXIF metadata in the original photo file.
            </div>

            <form method="post" action="{{ url('plugin/device-photo-package/action') }}" id="device-photo-set-taken-form">
                @csrf
                <input type="hidden" name="action" value="set_photo_taken">
                <input type="hidden" name="device_id" id="device-photo-set-taken-device-id" value="{{ $device ? $device->device_id : 0 }}">
                <input type="hidden" name="filename" id="device-photo-set-taken-filename" value="">

                <div style="display: flex; gap: 8px; justify-content: flex-end; margin-bottom: 12px;">
                    <button type="button" class="btn btn-default btn-sm" id="device-photo-set-taken-cancel">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa fa-save"></i> Save photo date
                    </button>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="device-photo-set-taken-input" style="font-size: 12px;">Photo taken</label>
                    <input
                        type="datetime-local"
                        name="photo_taken"
                        id="device-photo-set-taken-input"
                        class="form-control input-sm"
                        required
                    >
                </div>
            </form>
        </div>
    </div>

    <script>
        (function () {
            var modal = document.getElementById('device-photo-set-taken-modal');
            var form = document.getElementById('device-photo-set-taken-form');
            var filenameInput = document.getElementById('device-photo-set-taken-filename');
            var deviceInput = document.getElementById('device-photo-set-taken-device-id');
            var dateInput = document.getElementById('device-photo-set-taken-input');
            var cancelButton = document.getElementById('device-photo-set-taken-cancel');

            if (!modal || !form || !filenameInput || !dateInput) {
                return;
            }

            function closeModal() {
                modal.style.display = 'none';
                filenameInput.value = '';
                dateInput.value = '';
            }

            document.querySelectorAll('.device-photo-set-taken-button').forEach(function (button) {
                button.addEventListener('click', function () {
                    filenameInput.value = button.getAttribute('data-filename') || '';
                    dateInput.value = button.getAttribute('data-photo-taken') || '';

                    if (deviceInput) {
                        deviceInput.value = button.getAttribute('data-device-id') || deviceInput.value;
                    }

                    modal.style.display = 'flex';

                    setTimeout(function () {
                        dateInput.focus();
                    }, 50);
                });
            });
            if (dateInput && typeof dateInput.showPicker === 'function') {
                dateInput.addEventListener('click', function () {
                    dateInput.showPicker();
                });
            }

            if (cancelButton) {
                cancelButton.addEventListener('click', closeModal);
            }

            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && modal.style.display === 'flex') {
                    closeModal();
                }
            });
        })();
    </script>

    <hr>

    @include('device-photo::partials.footer')
</div>
