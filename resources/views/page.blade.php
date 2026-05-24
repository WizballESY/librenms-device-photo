
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

    /*
     * Dark mode warning buttons.
     * Keep warning actions orange, but slightly toned down.
     */
    html.dark .device-photo-plugin .btn-warning {
        background-color: #b8751a !important;
        border-color: #c78422 !important;
        color: #ffffff !important;
    }

    html.dark .device-photo-plugin .btn-warning:hover,
    html.dark .device-photo-plugin .btn-warning:focus {
        background-color: #c78422 !important;
        border-color: #d8942a !important;
        color: #ffffff !important;
    }

    /*
     * Dark mode polish for manager photo cards.
     */
    html.dark .device-photo-plugin .device-photo-manager-card .label-primary {
        background-color: #337ab7 !important;
        color: #ffffff !important;
    }

    html.dark .device-photo-plugin .device-photo-manager-card .label-success {
        background-color: #3c9b5f !important;
        color: #ffffff !important;
    }

    html.dark .device-photo-plugin .device-photo-manager-card .device-photo-card-action.btn-default,
    html.dark .device-photo-plugin .device-photo-manager-card .device-photo-card-action .btn-default {
        background: #3a4652 !important;
        border-color: #5b6875 !important;
        color: #f3f4f6 !important;
    }

    html.dark .device-photo-plugin .device-photo-manager-card .device-photo-card-action.btn-default:hover,
    html.dark .device-photo-plugin .device-photo-manager-card .device-photo-card-action .btn-default:hover {
        background: #465564 !important;
        border-color: #718193 !important;
        color: #ffffff !important;
    }

    html.dark .device-photo-plugin .device-photo-owned-photo-card .device-photo-card-link-box.alert-warning,
    html.dark .device-photo-plugin .device-photo-owned-photo-card [data-device-photo-linked-to-box].alert-warning {
        background: #4a3518 !important;
        border-color: #7a5520 !important;
        color: #f4d6a0 !important;
    }

    html.dark .device-photo-plugin .device-photo-owned-photo-card .device-photo-card-link-box.alert-warning a,
    html.dark .device-photo-plugin .device-photo-owned-photo-card [data-device-photo-linked-to-box].alert-warning a {
        color: #ffd28a !important;
    }

    html.dark .device-photo-plugin .device-photo-owned-photo-card .device-photo-card-link-box.alert-warning .text-muted,
    html.dark .device-photo-plugin .device-photo-owned-photo-card [data-device-photo-linked-to-box].alert-warning .text-muted {
        color: #d0b98f !important;
    }

    html.dark .device-photo-plugin .device-photo-owned-photo-card .device-photo-card-link-box.alert-warning [data-device-photo-ajax-row],
    html.dark .device-photo-plugin .device-photo-owned-photo-card [data-device-photo-linked-to-box].alert-warning [data-device-photo-ajax-row] {
        border-bottom-color: #7a5520 !important;
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

    /*
     * LibreNMS dark mode fix for confirmation modals.
     */
    html.dark .device-photo-plugin .device-photo-confirm-modal {
        background: #2f3842 !important;
        border: 1px solid #4b5563 !important;
        color: #d8dee9 !important;
    }

    html.dark .device-photo-plugin .device-photo-confirm-modal h4,
    html.dark .device-photo-plugin .device-photo-confirm-modal strong,
    html.dark .device-photo-plugin .device-photo-confirm-modal label {
        color: #f3f4f6 !important;
    }

    html.dark .device-photo-plugin .device-photo-confirm-modal .text-muted,
    html.dark .device-photo-plugin .device-photo-confirm-modal p {
        color: #aeb8c2 !important;
    }

    html.dark .device-photo-plugin .device-photo-confirm-modal code {
        background: #1f252c !important;
        border: 1px solid #4b5563 !important;
        color: #ffb4c8 !important;
    }

    html.dark .device-photo-plugin .device-photo-confirm-modal .form-control {
        background: #1f252c !important;
        border-color: #4b5563 !important;
        color: #f3f4f6 !important;
    }

    html.dark .device-photo-plugin .device-photo-confirm-modal .form-control::placeholder {
        color: #8793a0 !important;
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
    html.dark .device-photo-plugin .device-photo-orphan-suggestions,
    html.dark .device-photo-plugin .device-photo-restore-suggestions {
        background: #26303a !important;
        border-color: #4b5563 !important;
        color: #d8dee9 !important;
        box-shadow: 0 8px 24px rgba(0,0,0,0.45) !important;
    }

    html.dark .device-photo-plugin .device-photo-target-suggestion,
    html.dark .device-photo-plugin .device-photo-orphan-suggestion,
    html.dark .device-photo-plugin .device-photo-restore-suggestion {
        background: #26303a !important;
        border-color: #3f4a56 !important;
        color: #d8dee9 !important;
    }

    html.dark .device-photo-plugin .device-photo-target-suggestion:hover,
    html.dark .device-photo-plugin .device-photo-orphan-suggestion:hover,
    html.dark .device-photo-plugin .device-photo-restore-suggestion:hover {
        background: #35414d !important;
        color: #ffffff !important;
    }

    html.dark .device-photo-plugin .device-photo-target-suggestion .device-id,
    html.dark .device-photo-plugin .device-photo-orphan-suggestion .device-id,
    html.dark .device-photo-plugin .device-photo-restore-suggestion .device-id {
        color: #ff7aa8 !important;
    }

    html.dark .device-photo-plugin .device-photo-target-suggestion .device-name,
    html.dark .device-photo-plugin .device-photo-orphan-suggestion .device-name,
    html.dark .device-photo-plugin .device-photo-restore-suggestion .device-name {
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
    html.dark .device-photo-plugin .device-photo-owned-photo-card {
        background: #2f3842 !important;
        border-color: #4b5563 !important;
        color: #d8dee9 !important;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.38) !important;
    }

    html.dark .device-photo-plugin .device-photo-owned-photo-card .device-photo-card-image,
    html.dark .device-photo-plugin .device-photo-card-image {
        background: #1f252c !important;
        border-color: #4b5563 !important;
    }

    html.dark .device-photo-plugin .device-photo-card-meta {
        color: #cfd7e3 !important;
    }

    html.dark .device-photo-plugin .device-photo-linked-photo-card {
        background: #2f3842 !important;
        border-color: #4b5563 !important;
        color: #d8dee9 !important;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.38) !important;
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

    html.dark .device-photo-plugin .device-photo-header-action.btn-default {
        background: #3a4652 !important;
        border-color: #5b6875 !important;
        color: #f3f4f6 !important;
    }

    html.dark .device-photo-plugin .device-photo-header-action.btn-default:hover,
    html.dark .device-photo-plugin .device-photo-header-action.btn-default:focus {
        background: #465564 !important;
        border-color: #718193 !important;
        color: #ffffff !important;
    }
</style>

<div class="container-fluid device-photo-plugin">
    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 14px;">
        <h2 style="margin: 0;">
            {{ ($global_overview ?? false) ? 'Device Photos Overview' : 'Manage Device Photos' }}
            @include('device-photo::partials.version-badge')
        </h2>

        @if ($global_overview ?? false)
            <a href="{{ url('plugin/settings/device-photo') }}"
               class="btn btn-default btn-sm device-photo-header-action"
               title="Plugin settings">
                <i class="fa fa-cog"></i> Plugin settings
            </a>
        @else
            <a href="{{ url('plugin/device-photo') }}"
               class="btn btn-primary btn-sm"
               title="Device Photos overview">
                <i class="fa fa-arrow-left"></i> Device Photos Overview
            </a>
        @endif
    </div>

    @if ($device)
        <div style="margin-top: 10px; margin-bottom: 18px;">
            <a href="{{ url('device/' . $device->device_id) }}" class="btn btn-primary btn-sm">
                <i class="fa fa-arrow-left"></i> Back to device
            </a>

        </div>
    @endif

    <div class="alert alert-warning" style="font-size: 12px;">
        <strong>Notice:</strong>
        This plugin is currently an alpha release. Use at your own risk.
        Make sure you have tested it before using it in production.<br>
        Feedback and bug reports are welcome on
        <a href="https://github.com/WizballESY/librenms-device-photo/issues" target="_blank" rel="noopener noreferrer">GitHub</a>.
    </div>

    @if ($message)
        <div id="device-photo-toast-stack"
             style="
                position: fixed;
                top: 22px;
                left: 50%;
                transform: translateX(-50%);
                z-index: 26000;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 10px;
                width: min(520px, calc(100vw - 32px));
                pointer-events: none;
             ">
            <div class="device-photo-toast"
                 data-device-photo-toast-auto="1"
                 style="
                    width: 100%;
                    padding: 12px 16px;
                    border-radius: 10px;
                    box-shadow: 0 10px 28px rgba(0,0,0,0.26);
                    font-size: 13px;
                    line-height: 1.35;
                    text-align: center;
                    background: #f5fbf2;
                    border: 1px solid #b9dfad;
                    border-left: 5px solid #5cb85c;
                    color: #2f6f32;
                    opacity: 0;
                    transform: translateY(-24px);
                    transition: opacity 0.35s ease, transform 0.35s ease;
                    pointer-events: auto;
                 ">
                {{ $message }}
            </div>
        </div>

        <script>
            (function () {
                function ensureToastStack() {
                    var stack = document.getElementById('device-photo-toast-stack');

                    if (!stack) {
                        stack = document.createElement('div');
                        stack.id = 'device-photo-toast-stack';
                        stack.style.position = 'fixed';
                        stack.style.top = '22px';
                        stack.style.left = '50%';
                        stack.style.transform = 'translateX(-50%)';
                        stack.style.zIndex = '26000';
                        stack.style.display = 'flex';
                        stack.style.flexDirection = 'column';
                        stack.style.alignItems = 'center';
                        stack.style.gap = '10px';
                        stack.style.width = 'min(520px, calc(100vw - 32px))';
                        stack.style.pointerEvents = 'none';
                        document.body.appendChild(stack);
                    }

                    return stack;
                }

                window.devicePhotoToast = function (message) {
                    var stack = ensureToastStack();
                    var toast = document.createElement('div');

                    toast.className = 'device-photo-toast';
                    toast.textContent = message || '';
                    toast.style.width = '100%';
                    toast.style.padding = '12px 16px';
                    toast.style.borderRadius = '10px';
                    toast.style.boxShadow = '0 10px 28px rgba(0,0,0,0.26)';
                    toast.style.fontSize = '13px';
                    toast.style.lineHeight = '1.35';
                    toast.style.textAlign = 'center';
                    toast.style.background = '#f5fbf2';
                    toast.style.border = '1px solid #b9dfad';
                    toast.style.borderLeft = '5px solid #5cb85c';
                    toast.style.color = '#2f6f32';
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(-24px)';
                    toast.style.transition = 'opacity 0.35s ease, transform 0.35s ease';
                    toast.style.pointerEvents = 'auto';

                    stack.appendChild(toast);

                    window.requestAnimationFrame(function () {
                        toast.style.opacity = '1';
                        toast.style.transform = 'translateY(0)';
                    });

                    window.setTimeout(function () {
                        toast.style.opacity = '0';
                        toast.style.transform = 'translateY(-12px)';

                        window.setTimeout(function () {
                            if (toast && toast.parentNode) {
                                toast.parentNode.removeChild(toast);
                            }
                        }, 400);
                    }, 5000);
                };

                document.querySelectorAll('.device-photo-toast[data-device-photo-toast-auto="1"]').forEach(function (toast) {
                    window.requestAnimationFrame(function () {
                        toast.style.opacity = '1';
                        toast.style.transform = 'translateY(0)';
                    });

                    window.setTimeout(function () {
                        toast.style.opacity = '0';
                        toast.style.transform = 'translateY(-12px)';

                        window.setTimeout(function () {
                            if (toast && toast.parentNode) {
                                toast.parentNode.removeChild(toast);
                            }
                        }, 400);
                    }, 5000);
                });
            })();
        </script>
    @endif

    @if ($error)
        <div class="alert alert-danger">{{ $error }}</div>
    @endif

    @include('device-photo::partials.photo-modal')

    <script id="device-photo-ajax-helper">
        window.DevicePhotoAjax = window.DevicePhotoAjax || {};

        window.DevicePhotoAjax.toast = function (message) {
            if (typeof window.devicePhotoToast === 'function') {
                window.devicePhotoToast(message);
                return;
            }

            var stack = document.getElementById('device-photo-ajax-toast-stack');

            if (!stack) {
                stack = document.createElement('div');
                stack.id = 'device-photo-ajax-toast-stack';
                stack.style.position = 'fixed';
                stack.style.top = '18px';
                stack.style.left = '50%';
                stack.style.transform = 'translateX(-50%)';
                stack.style.zIndex = '30000';
                stack.style.width = 'min(520px, calc(100vw - 32px))';
                stack.style.display = 'flex';
                stack.style.flexDirection = 'column';
                stack.style.gap = '8px';
                stack.style.pointerEvents = 'none';
                document.body.appendChild(stack);
            }

            var toast = document.createElement('div');
            toast.textContent = message || '';
            toast.style.width = '100%';
            toast.style.padding = '12px 16px';
            toast.style.borderRadius = '10px';
            toast.style.boxShadow = '0 10px 28px rgba(0,0,0,0.26)';
            toast.style.fontSize = '13px';
            toast.style.lineHeight = '1.35';
            toast.style.textAlign = 'center';
            toast.style.background = '#f5fbf2';
            toast.style.border = '1px solid #b9dfad';
            toast.style.borderLeft = '5px solid #5cb85c';
            toast.style.color = '#2f6f32';
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-24px)';
            toast.style.transition = 'opacity 0.35s ease, transform 0.35s ease';
            toast.style.pointerEvents = 'auto';

            stack.appendChild(toast);

            window.requestAnimationFrame(function () {
                toast.style.opacity = '1';
                toast.style.transform = 'translateY(0)';
            });

            window.setTimeout(function () {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-12px)';

                window.setTimeout(function () {
                    if (toast && toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 400);
            }, 5000);
        };

        window.DevicePhotoAjax.submitForm = function (form) {
            var formData = new FormData(form);

            formData.set('ajax', '1');

            return fetch(form.getAttribute('action'), {
                method: (form.method || 'POST').toUpperCase(),
                body: formData,
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(function (response) {
                if (!response.ok) {
                    throw new Error('HTTP ' + response.status);
                }

                return response.json();
            }).then(function (data) {
                if (!data || data.ok !== true) {
                    throw new Error((data && data.status) ? data.status : 'ajax_failed');
                }

                return {
                    data: data,
                    formData: formData
                };
            });
        };
    </script>

    <script id="device-photo-link-ui-helper">
        window.DevicePhotoLinkUi = window.DevicePhotoLinkUi || {};

        window.DevicePhotoLinkUi.escapeHtml = function (value) {
            return String(value || '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        };

        window.DevicePhotoLinkUi.findIncomingOwnerCard = function (ownerDeviceId, filename) {
            var cards = document.querySelectorAll('.device-photo-incoming-owner-card');

            for (var i = 0; i < cards.length; i++) {
                if (
                    cards[i].getAttribute('data-device-photo-incoming-owner-id') === String(ownerDeviceId || '') &&
                    cards[i].getAttribute('data-device-photo-incoming-filename') === String(filename || '')
                ) {
                    return cards[i];
                }
            }

            return null;
        };

        window.DevicePhotoLinkUi.restoreIncomingLinkButtonAfterRemove = function (form) {
            var actionInput = form.querySelector('input[name="action"]');

            if (!actionInput || actionInput.value !== 'remove_link') {
                return;
            }

            var ownerInput = form.querySelector('input[name="owner_device_id"]');
            var filenameInput = form.querySelector('input[name="filename"]');
            var deviceInput = form.querySelector('input[name="device_id"]');
            var tokenInput = form.querySelector('input[name="_token"]');

            if (!ownerInput || !filenameInput || !deviceInput || !tokenInput) {
                return;
            }

            var card = window.DevicePhotoLinkUi.findIncomingOwnerCard(ownerInput.value, filenameInput.value);

            if (!card) {
                return;
            }

            var existingButton = card.querySelector('button.btn-success[disabled]');

            if (!existingButton) {
                return;
            }

            var ownerQuery = card.getAttribute('data-device-photo-owner-query') || '';
            var escapeHtml = window.DevicePhotoLinkUi.escapeHtml;
            var linkForm = document.createElement('form');

            linkForm.method = 'post';
            linkForm.action = form.getAttribute('action');
            linkForm.setAttribute('data-device-photo-ajax-add-incoming-link', '1');
            linkForm.setAttribute('data-device-photo-ajax-success', 'Photo linked.');

            linkForm.innerHTML =
                '<input type="hidden" name="_token" value="' + escapeHtml(tokenInput.value) + '">' +
                '<input type="hidden" name="action" value="add_incoming_link">' +
                '<input type="hidden" name="device_id" value="' + escapeHtml(deviceInput.value) + '">' +
                '<input type="hidden" name="owner_device_id" value="' + escapeHtml(ownerInput.value) + '">' +
                '<input type="hidden" name="filename" value="' + escapeHtml(filenameInput.value) + '">' +
                '<input type="hidden" name="owner_device_query" value="' + escapeHtml(ownerQuery) + '">' +
                '<button type="submit" class="btn btn-default btn-sm btn-block">' +
                    '<i class="fa fa-link"></i> Link this photo' +
                '</button>';

            existingButton.replaceWith(linkForm);
        };
    </script>

    <script id="device-photo-link-ajax-helper">
        document.addEventListener('DOMContentLoaded', function () {
            function submitPhotoLinkAjax(form) {
                var button = form.querySelector('button[type="submit"]');
                var targetInput = form.querySelector('input[name="target_device_query"]');
                var isIncoming = form.getAttribute('data-device-photo-ajax-add-incoming-link') === '1';

                if (button) {
                    button.disabled = true;
                }

                window.DevicePhotoAjax.submitForm(form).then(function (result) {
                    var data = result.data;
                    var formData = result.formData;

                    function escapeHtml(value) {
                        return String(value || '')
                            .replace(/&/g, '&amp;')
                            .replace(/</g, '&lt;')
                            .replace(/>/g, '&gt;')
                            .replace(/"/g, '&quot;')
                            .replace(/'/g, '&#039;');
                    }

                    function findOwnedPhotoCard(filename) {
                        var cards = document.querySelectorAll('.device-photo-manager-card[data-filename]');

                        for (var i = 0; i < cards.length; i++) {
                            if (cards[i].getAttribute('data-filename') === filename) {
                                return cards[i];
                            }
                        }

                        return null;
                    }

                    function updateOwnedLinkedToBox(form, data) {
                        if (!data || !data.filename || !data.target_device_id) {
                            return;
                        }

                        var card = findOwnedPhotoCard(data.filename);

                        if (!card) {
                            return;
                        }

                        var box = card.querySelector('[data-device-photo-linked-to-box]');
                        var list;
                        var count;

                        if (!box) {
                            box = document.createElement('div');
                            box.className = 'alert alert-warning device-photo-card-link-box';
                            box.setAttribute('data-device-photo-linked-to-box', '1');
                            box.style.fontSize = '12px';
                            box.style.padding = '6px 8px';
                            box.style.marginBottom = '8px';

                            box.innerHTML =
                                '<strong>' +
                                    '<i class="fa fa-link"></i> ' +
                                    'Linked to <span data-device-photo-linked-to-count>0</span> devices' +
                                '</strong>' +
                                '<div style="margin-top: 8px;" data-device-photo-linked-to-list></div>';

                            var downloadButton = card.querySelector('a[download]');

                            if (downloadButton && downloadButton.parentNode) {
                                downloadButton.parentNode.insertBefore(box, downloadButton);
                            } else {
                                card.appendChild(box);
                            }
                        }

                        list = box.querySelector('[data-device-photo-linked-to-list]');

                        if (!list) {
                            list = box.querySelector('strong + div');

                            if (list) {
                                list.setAttribute('data-device-photo-linked-to-list', '1');
                            }
                        }

                        if (!list) {
                            return;
                        }

                        if (list.querySelector('[data-device-photo-target-device-id="' + String(data.target_device_id) + '"]')) {
                            return;
                        }

                        var row = document.createElement('div');
                        row.setAttribute('data-device-photo-ajax-row', 'outgoing-link');
                        row.setAttribute('data-device-photo-target-device-id', String(data.target_device_id));
                        row.style.marginBottom = '8px';
                        row.style.paddingBottom = '8px';
                        row.style.borderBottom = '1px solid #eadfbf';

                        row.innerHTML =
                            '<div style="word-break: break-word;">' +
                                '<a href="{{ url('plugin/device-photo') }}?device_id=' + encodeURIComponent(String(data.target_device_id)) + '">' +
                                    escapeHtml(data.target_device_name || ('device-' + data.target_device_id)) +
                                    ' <span class="text-muted">(Device ID ' + escapeHtml(data.target_device_id) + ')</span>' +
                                '</a>' +
                            '</div>';

                        if (data.can_delete === true) {
                            row.innerHTML +=
                                '<form method="post" action="{{ url('plugin/device-photo-package/action') }}" style="margin-top: 6px;"' +
                                      ' data-device-photo-ajax="1"' +
                                      ' data-device-photo-ajax-success="Shared photo link removed."' +
                                      ' data-device-photo-confirm-title="Remove shared link?"' +
                                      ' data-device-photo-confirm-ok-text="Remove link"' +
                                      ' data-device-photo-confirm-ok-class="btn-warning"' +
                                      ' data-device-photo-confirm-ok-icon="fa-unlink"' +
                                      ' data-device-photo-confirm="Remove this shared photo link? The original photo will not be deleted.">' +
                                    '<input type="hidden" name="_token" value="' + escapeHtml(formData.get('_token') || '') + '">' +
                                    '<input type="hidden" name="action" value="remove_outgoing_link">' +
                                    '<input type="hidden" name="device_id" value="' + escapeHtml(formData.get('device_id') || '') + '">' +
                                    '<input type="hidden" name="target_device_id" value="' + escapeHtml(data.target_device_id) + '">' +
                                    '<input type="hidden" name="filename" value="' + escapeHtml(data.filename) + '">' +
                                    '<input type="hidden" name="return_anchor" value="' + escapeHtml(formData.get('return_anchor') || '') + '">' +
                                    '<button type="submit" class="btn btn-warning btn-xs">' +
                                        '<i class="fa fa-unlink"></i> Remove link' +
                                    '</button>' +
                                '</form>';
                        }

                        list.appendChild(row);

                        count = box.querySelector('[data-device-photo-linked-to-count]');

                        if (count) {
                            count.textContent = String(list.querySelectorAll('[data-device-photo-ajax-row="outgoing-link"]').length);
                        }
                    }

                    if (targetInput) {
                        targetInput.value = '';
                    }

                    if (!isIncoming) {
                        updateOwnedLinkedToBox(form, data);
                    }

                    if (isIncoming && data.html) {
                        var grid = document.getElementById('device-photo-manager-grid');
                        var template = document.createElement('template');

                        template.innerHTML = String(data.html).trim();

                        var card = template.content.firstElementChild;

                        if (grid && card) {
                            /*
                             * Server-rendered linked cards include CSS order for initial page load.
                             * The reorder script normalizes that on DOMContentLoaded, but AJAX-added
                             * cards are inserted after that has already happened. Clear the inline
                             * order here so drag/drop visual order follows DOM order immediately.
                             */
                            card.style.order = '';
                            grid.appendChild(card);

                            card.querySelectorAll('.device-photo-local-date[data-device-photo-date]').forEach(function (el) {
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
                    }

                    if (isIncoming && button) {
                        button.className = 'btn btn-success btn-sm btn-block';

                        if ((data && data.already_linked === true) || (data && data.status === 'already_linked')) {
                            button.innerHTML = '<i class="fa fa-check"></i> Already linked';
                        } else {
                            button.innerHTML = '<i class="fa fa-check"></i> Linked';
                        }
                    }

                    if (window.DevicePhotoAjax && typeof window.DevicePhotoAjax.toast === 'function') {
                        window.DevicePhotoAjax.toast((data && data.message) || form.getAttribute('data-device-photo-ajax-success') || 'Photo linked.');
                    }
                }).catch(function (error) {
                    console.error('DevicePhoto AJAX link failed:', error);

                    /*
                     * Normal POST fallback if AJAX fails.
                     */
                    form.removeAttribute('data-device-photo-ajax-add-link');
                    form.removeAttribute('data-device-photo-ajax-add-incoming-link');
                    form.submit();
                }).finally(function () {
                    if (button && !isIncoming) {
                        button.disabled = false;
                    }
                });
            }

            document.addEventListener('submit', function (e) {
                var form = e.target.closest('form[data-device-photo-ajax-add-link="1"], form[data-device-photo-ajax-add-incoming-link="1"]');

                if (!form) {
                    return;
                }

                e.preventDefault();
                e.stopImmediatePropagation();

                submitPhotoLinkAjax(form);
            }, true);
        });
    </script>





    @if (($global_overview ?? false) && request()->query('view') === 'restore-deleted')
        @php
            $overview = $global_photo_overview ?? ['rows' => [], 'orphaned_photos' => [], 'deleted_photos' => [], 'broken_links' => []];
        @endphp

        <div class="panel panel-default">
            <div class="panel-heading">
                <strong><i class="fa fa-trash"></i> Manage deleted photos</strong>
            </div>

            <div class="panel-body">
                <p class="text-muted">
                    This page shows photos that have been moved to the deleted folder.
                    Choose a target device to restore a photo. The restored file will be renamed to match the selected device.
                </p>

                <div class="alert alert-info" style="font-size: 12px; padding: 8px 10px; margin-bottom: 14px;">
                    Deleted photos: <strong data-device-photo-deleted-count>{{ $overview['deleted_photo_count'] ?? 0 }}</strong><br>
                    Total deleted size: <strong data-device-photo-deleted-total-size>{{ $overview['deleted_total_mb'] ?? 0 }} MB</strong>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; gap: 10px; flex-wrap: wrap; margin-bottom: 16px;">
                    <a href="{{ url('plugin/device-photo') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-arrow-left"></i> Back to overview
                    </a>

                    @if ($can_delete && (($overview['deleted_photo_count'] ?? 0) > 0 || ($overview['deleted_thumbnail_count'] ?? 0) > 0))
                        <button type="button"
                                class="btn btn-danger btn-sm"
                                id="device-photo-empty-deleted-open-manage"
                                title="Permanently remove all files from the deleted folder">
                            <i class="fa fa-trash" style="color: #fff;"></i> Empty deleted photos
                        </button>
                    @endif
                </div>

                @if ($can_delete && (($overview['deleted_photo_count'] ?? 0) > 0 || ($overview['deleted_thumbnail_count'] ?? 0) > 0))

                    <div class="device-photo-confirm-backdrop"
                         id="device-photo-empty-deleted-backdrop-manage"
                         style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 20000; align-items: center; justify-content: center;">
                        <div class="device-photo-confirm-modal" style="background: #fff; border-radius: 8px; padding: 18px; max-width: 520px; width: calc(100% - 32px); box-shadow: 0 8px 28px rgba(0,0,0,0.35);">
                            <h4 style="margin-top: 0;">Permanently delete deleted photos?</h4>

                            <p>
                                This will permanently remove all photos and thumbnails from the deleted folder.
                                This cannot be undone.
                            </p>

                            <div class="alert alert-warning" style="font-size: 12px; padding: 8px 10px;">
                                Deleted originals: <strong data-device-photo-deleted-modal-count>{{ $overview['deleted_photo_count'] ?? 0 }}</strong><br>
                                Deleted thumbnails: <strong data-device-photo-deleted-modal-thumbnail-count>{{ $overview['deleted_thumbnail_count'] ?? 0 }}</strong><br>
                                Total size: <strong data-device-photo-deleted-modal-total-size>{{ $overview['deleted_total_mb'] ?? 0 }} MB</strong>
                            </div>

                            <form method="post" action="{{ url('plugin/device-photo-package/action') }}" id="device-photo-empty-deleted-form-manage">
                                @csrf
                                <input type="hidden" name="action" value="empty_deleted_photos">
                                <input type="hidden" name="device_id" value="0">
                                <input type="hidden" name="confirm_code" id="device-photo-empty-deleted-code-value-manage" value="">

                                <div class="alert alert-danger" style="font-size: 12px; padding: 8px 10px;">
                                    Type this code to confirm:
                                    <strong id="device-photo-empty-deleted-code-manage" style="font-size: 16px; letter-spacing: 2px;"></strong>
                                </div>

                                <input type="text"
                                       name="confirm_input"
                                       id="device-photo-empty-deleted-input-manage"
                                       class="form-control input-sm"
                                       maxlength="4"
                                       inputmode="numeric"
                                       autocomplete="off"
                                       placeholder="Enter 4-digit code"
                                       style="max-width: 180px; margin-bottom: 12px;">

                                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                    <button type="button" class="btn btn-default btn-sm" id="device-photo-empty-deleted-cancel-manage">
                                        Cancel
                                    </button>

                                    <button type="submit" class="btn btn-danger btn-sm" id="device-photo-empty-deleted-submit-manage" disabled>
                                        <i class="fa fa-trash"></i> Empty deleted photos
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <script>
                        (function () {
                            var openButton = document.getElementById('device-photo-empty-deleted-open-manage');
                            var backdrop = document.getElementById('device-photo-empty-deleted-backdrop-manage');
                            var cancelButton = document.getElementById('device-photo-empty-deleted-cancel-manage');
                            var codeText = document.getElementById('device-photo-empty-deleted-code-manage');
                            var codeValue = document.getElementById('device-photo-empty-deleted-code-value-manage');
                            var input = document.getElementById('device-photo-empty-deleted-input-manage');
                            var submitButton = document.getElementById('device-photo-empty-deleted-submit-manage');

                            function generateCode() {
                                return String(Math.floor(1000 + Math.random() * 9000));
                            }

                            function closeModal() {
                                backdrop.style.display = 'none';
                                input.value = '';
                                submitButton.disabled = true;
                            }

                            if (openButton && backdrop && codeText && codeValue && input && submitButton) {
                                openButton.addEventListener('click', function () {
                                    var code = generateCode();

                                    codeText.textContent = code;
                                    codeValue.value = code;
                                    input.value = '';
                                    submitButton.disabled = true;
                                    backdrop.style.display = 'flex';
                                    input.focus();
                                });

                                cancelButton.addEventListener('click', closeModal);

                                backdrop.addEventListener('click', function (e) {
                                    if (e.target === backdrop) {
                                        closeModal();
                                    }
                                });

                                input.addEventListener('input', function () {
                                    submitButton.disabled = input.value !== codeValue.value;
                                });
                            }
                        })();
                    </script>
                @endif


                <div id="device-photo-confirm-backdrop"
                     class="device-photo-confirm-backdrop"
                     style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 20000; align-items: center; justify-content: center;">
                    <div class="device-photo-confirm-box" style="background: #fff; border-radius: 10px; padding: 16px; max-width: 460px; width: calc(100% - 32px); box-shadow: 0 8px 28px rgba(0,0,0,0.35);">
                        <h4 style="margin-top: 0;">
                            <i class="fa fa-exclamation-triangle"></i>
                            <span id="device-photo-confirm-title">Confirm action</span>
                        </h4>

                        <p id="device-photo-confirm-message" style="margin-bottom: 16px;"></p>

                        <div style="display: flex; gap: 8px; justify-content: flex-end;">
                            <button type="button" class="btn btn-default btn-sm" id="device-photo-confirm-cancel">
                                Cancel
                            </button>

                            <button type="button" class="btn btn-primary btn-sm" id="device-photo-confirm-ok">
                                <i class="fa fa-check" id="device-photo-confirm-ok-icon"></i>
                                <span id="device-photo-confirm-ok-text">Confirm</span>
                            </button>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var backdrop = document.getElementById('device-photo-confirm-backdrop');
                        var title = document.getElementById('device-photo-confirm-title');
                        var message = document.getElementById('device-photo-confirm-message');
                        var cancelButton = document.getElementById('device-photo-confirm-cancel');
                        var okButton = document.getElementById('device-photo-confirm-ok');
                        var okIcon = document.getElementById('device-photo-confirm-ok-icon');
                        var okText = document.getElementById('device-photo-confirm-ok-text');
                        var pendingForm = null;

                        if (!backdrop || !title || !message || !cancelButton || !okButton || !okIcon || !okText) {
                            return;
                        }

                        function resetConfirm() {
                            title.textContent = 'Confirm action';
                            message.textContent = '';
                            okText.textContent = 'Confirm';
                            okIcon.className = 'fa fa-check';
                            okButton.className = 'btn btn-primary btn-sm';
                        }

                        function closeConfirm() {
                            backdrop.style.display = 'none';
                            pendingForm = null;
                            resetConfirm();
                        }

                        document.addEventListener('submit', function (e) {
                            var form = e.target.closest('form[data-device-photo-confirm]');

                            if (!form) {
                                return;
                            }

                            if (form.getAttribute('data-device-photo-confirmed') === '1') {
                                return;
                            }

                            e.preventDefault();

                            pendingForm = form;

                            title.textContent = form.getAttribute('data-device-photo-confirm-title') || 'Confirm action';
                            message.textContent = form.getAttribute('data-device-photo-confirm') || 'Continue?';
                            okText.textContent = form.getAttribute('data-device-photo-confirm-ok-text') || 'Confirm';

                            okButton.className = 'btn btn-sm ' + (form.getAttribute('data-device-photo-confirm-ok-class') || 'btn-primary');
                            okIcon.className = 'fa ' + (form.getAttribute('data-device-photo-confirm-ok-icon') || 'fa-check');

                            backdrop.style.display = 'flex';
                        }, true);

                        cancelButton.addEventListener('click', closeConfirm);

                        backdrop.addEventListener('click', function (e) {
                            if (e.target === backdrop) {
                                closeConfirm();
                            }
                        });

                        document.addEventListener('keydown', function (e) {
                            if (e.key === 'Escape' && backdrop.style.display === 'flex') {
                                closeConfirm();
                            }
                        });

                        function submitNormally(form) {
                            form.removeAttribute('data-device-photo-ajax');
                            form.setAttribute('data-device-photo-confirmed', '1');
                            form.submit();
                        }

                        function updateDeletedPhotosUi(data) {
                            var stats = data && data.deleted_stats ? data.deleted_stats : {};
                            var count = document.querySelector('[data-device-photo-deleted-count]');
                            var totalSize = document.querySelector('[data-device-photo-deleted-total-size]');
                            var modalCount = document.querySelector('[data-device-photo-deleted-modal-count]');
                            var modalThumbnailCount = document.querySelector('[data-device-photo-deleted-modal-thumbnail-count]');
                            var modalTotalSize = document.querySelector('[data-device-photo-deleted-modal-total-size]');
                            var grid = document.querySelector('[data-device-photo-deleted-grid]');
                            var emptyButton = document.getElementById('device-photo-empty-deleted-open-manage');

                            if (typeof stats.photo_count !== 'undefined' && count) {
                                count.textContent = String(stats.photo_count);
                            }

                            if (typeof stats.photo_count !== 'undefined' && modalCount) {
                                modalCount.textContent = String(stats.photo_count);
                            }

                            if (typeof stats.thumbnail_count !== 'undefined' && modalThumbnailCount) {
                                modalThumbnailCount.textContent = String(stats.thumbnail_count);
                            }

                            if (typeof stats.total_mb !== 'undefined' && totalSize) {
                                totalSize.textContent = String(stats.total_mb) + ' MB';
                            }

                            if (typeof stats.total_mb !== 'undefined' && modalTotalSize) {
                                modalTotalSize.textContent = String(stats.total_mb) + ' MB';
                            }

                            if (typeof stats.photo_count !== 'undefined' && Number(stats.photo_count) < 1) {
                                if (emptyButton) {
                                    emptyButton.style.display = 'none';
                                }

                                if (grid) {
                                    grid.style.display = 'none';

                                    if (!document.querySelector('[data-device-photo-deleted-empty-dynamic]')) {
                                        var empty = document.createElement('div');
                                        empty.className = 'alert alert-info';
                                        empty.setAttribute('data-device-photo-deleted-empty-dynamic', '1');
                                        empty.textContent = 'No deleted photos found.';
                                        grid.parentNode.insertBefore(empty, grid);
                                    }
                                }
                            }
                        }

                        function submitAjax(form) {
                            window.DevicePhotoAjax.submitForm(form).then(function (result) {
                                var data = result.data;
                                var row = form.closest('[data-device-photo-ajax-row]');

                                if (row && row.parentNode) {
                                    row.parentNode.removeChild(row);
                                }

                                updateDeletedPhotosUi(data);

                                if (window.DevicePhotoAjax && typeof window.DevicePhotoAjax.toast === 'function') {
                                    window.DevicePhotoAjax.toast((data && data.message) || form.getAttribute('data-device-photo-ajax-success') || 'Action completed.');
                                } else if (typeof window.devicePhotoToast === 'function') {
                                    window.devicePhotoToast(form.getAttribute('data-device-photo-ajax-success') || 'Action completed.');
                                }
                            }).catch(function (error) {
                                console.error('DevicePhoto AJAX failed:', error);
                                submitNormally(form);
                            });
                        }

                        okButton.addEventListener('click', function () {
                            if (!pendingForm) {
                                closeConfirm();
                                return;
                            }

                            var form = pendingForm;

                            pendingForm = null;
                            backdrop.style.display = 'none';

                            if (form.getAttribute('data-device-photo-ajax') === '1') {
                                submitAjax(form);
                                return;
                            }

                            form.setAttribute('data-device-photo-confirmed', '1');
                            form.submit();
                        });
                    });
                </script>

                <style>
                    .device-photo-restore-suggestions {
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

                    .device-photo-restore-suggestion {
                        padding: 6px 8px;
                        cursor: pointer;
                        border-bottom: 1px solid #eee;
                    }

                    .device-photo-restore-suggestion:hover {
                        background: #f3f7fb;
                    }

                    .device-photo-restore-suggestion .device-id {
                        font-family: monospace;
                        color: #b00040;
                    }

                    .device-photo-restore-suggestion .device-name {
                        margin-left: 6px;
                    }
                </style>

                <script id="device-photo-restore-deleted-autocomplete">
                    document.addEventListener('DOMContentLoaded', function () {
                        var restoreDevices = @json(collect($link_target_devices ?? [])->values());
                        var maxResults = 20;

                        function normalize(value) {
                            return String(value || '').toLowerCase();
                        }

                        function findRestoreMatches(query) {
                            query = String(query || '').trim();

                            if (query === '') {
                                return [];
                            }

                            var q = normalize(query);
                            var exactId = [];
                            var startsWithId = [];
                            var startsWithName = [];
                            var containsName = [];

                            restoreDevices.forEach(function (device) {
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

                        function closeRestoreSuggestions() {
                            document.querySelectorAll('.device-photo-restore-suggestions').forEach(function (box) {
                                box.style.display = 'none';
                            });
                        }

                        function ensureRestoreSuggestionBox(input) {
                            var form = input.closest('form');
                            var box = form.querySelector('.device-photo-restore-suggestions');

                            if (!box) {
                                box = document.createElement('div');
                                box.className = 'device-photo-restore-suggestions';
                                form.appendChild(box);
                            }

                            return box;
                        }

                        function renderRestoreSuggestions(input) {
                            var box = ensureRestoreSuggestionBox(input);
                            var matches = findRestoreMatches(input.value);

                            box.innerHTML = '';

                            if (matches.length === 0) {
                                box.style.display = 'none';
                                return;
                            }

                            matches.forEach(function (device) {
                                var item = document.createElement('div');
                                item.className = 'device-photo-restore-suggestion';

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
                                renderRestoreSuggestions(input);
                            });

                            input.addEventListener('focus', function () {
                                renderRestoreSuggestions(input);
                            });

                            input.addEventListener('keydown', function (e) {
                                if (e.key === 'Escape') {
                                    closeRestoreSuggestions();
                                }
                            });
                        });

                        document.addEventListener('click', function (e) {
                            if (!e.target.closest('.device-photo-restore-suggestions') && !e.target.closest('.device-photo-orphan-target-input')) {
                                closeRestoreSuggestions();
                            }
                        });
                    });
                </script>

                @if (! $can_delete)
                    <div class="alert alert-warning">
                        You do not have permission to restore deleted photos.
                    </div>
                @elseif (empty($overview['deleted_photos']))
                    <div class="alert alert-info">
                        No deleted photos found.
                    </div>
                @else
                    <div data-device-photo-deleted-grid style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 240px)); gap: 14px;">
                        @foreach ($overview['deleted_photos'] as $photo)
                            @include('device-photo::partials.restore-deleted-photo-card', [
                                'photo' => $photo,
                                'can_delete' => $can_delete,
                            ])
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @elseif ($global_overview ?? false)
        @php
            $overview = $global_photo_overview ?? ['rows' => [], 'orphaned_photos' => [], 'deleted_photos' => [], 'broken_links' => []];
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
                        (int) ($overview['stale_thumbnail_count'] ?? 0) +
                        (!empty($overview['legacy_deleted_storage_detected']) ? 1 : 0);
                @endphp

                <div class="device-photo-summary-panels">
                    <div class="device-photo-summary-panel">
                        <div class="device-photo-summary-panel-header">
                            <i class="fa fa-camera"></i>
                            Photo library
                        </div>

                        <div class="device-photo-summary-panel-description">
                            Current photo inventory and storage usage. Size totals include thumbnails.
                        </div>

                        <div class="device-photo-summary-panel-items">
                            <span class="device-photo-summary-item" title="Number of devices that currently have owned photos or linked photos.">
                                <span class="number">{{ count($overview['rows'] ?? []) }}</span><span class="label">devices</span>
                            </span>

                            <span class="device-photo-summary-item" title="Photos currently available in the main photo folder.">
                                <span class="number">{{ $overview['active_photo_count'] ?? 0 }}</span><span class="label">active photos</span>
                            </span>

                            <span class="device-photo-summary-item"
                                  data-device-photo-active-size-summary
                                  title="Total size of active photos and thumbnails. Originals: {{ $overview['active_photo_mb'] ?? 0 }} MB, thumbnails: {{ $overview['thumbnail_mb'] ?? 0 }} MB.">
                                <span class="number" data-device-photo-active-size>{{ $overview['active_total_mb'] ?? $overview['active_photo_mb'] ?? 0 }} MB</span><span class="label">size</span>
                            </span>

                            <span class="device-photo-summary-item" title="Photos moved to the deleted folder.">
                                <span class="number">{{ $overview['deleted_photo_count'] ?? 0 }}</span><span class="label">deleted photos</span>
                            </span>

                            <span class="device-photo-summary-item" title="Total size of deleted photos and deleted thumbnails. Originals: {{ $overview['deleted_photo_mb'] ?? 0 }} MB, thumbnails: {{ $overview['deleted_thumbnail_mb'] ?? 0 }} MB.">
                                <span class="number">{{ $overview['deleted_total_mb'] ?? $overview['deleted_photo_mb'] ?? 0 }} MB</span><span class="label">deleted size</span>
                            </span>
                        </div>
                    </div>

                    <div class="device-photo-summary-panel">
                        <div style="display: flex; justify-content: space-between; gap: 16px; align-items: flex-start;">
                            <div style="min-width: 0; flex: 1 1 auto;">
                                <div class="device-photo-summary-panel-header">
                                    <i class="fa fa-wrench"></i>
                                    Maintenance
                                </div>

                        <div class="device-photo-summary-panel-description">
                            Cleanup checks for orphaned photos, broken links and thumbnail cache issues.
                        </div>

                        <div class="device-photo-summary-panel-items">
                            <span class="device-photo-maintenance-ok"
                                  data-device-photo-maintenance-ok
                                  style="{{ $maintenanceIssueCount === 0 ? '' : 'display: none;' }}"
                                  title="No orphaned photos, broken links, missing thumbnails or stale thumbnails were found.">
                                <i class="fa fa-check-circle"></i>
                                No maintenance issues found
                            </span>

                            @if ($maintenanceIssueCount > 0)
                                @if (count($overview['orphaned_photos'] ?? []) > 0)
                                    <span class="device-photo-summary-item is-problem"
                                          data-device-photo-orphaned-summary
                                          title="Photos where the original LibreNMS device ID no longer exists.">
                                        <span class="number" data-device-photo-orphaned-count>{{ count($overview['orphaned_photos'] ?? []) }}</span><span class="label">orphans</span>
                                    </span>
                                @endif

                                @if (count($overview['broken_links'] ?? []) > 0)
                                    <span class="device-photo-summary-item is-problem"
                                          data-device-photo-broken-links-summary
                                          title="Photo links that point to a missing original file.">
                                        <span class="number" data-device-photo-broken-links-count>{{ count($overview['broken_links'] ?? []) }}</span><span class="label">broken links</span>
                                    </span>
                                @endif

                                @if (($overview['missing_thumbnail_count'] ?? 0) > 0)
                                    <span class="device-photo-summary-item is-problem"
                                          data-device-photo-missing-thumbnails-summary
                                          title="Active photos without a generated thumbnail.">
                                        <span class="number" data-device-photo-missing-thumbnails-count>{{ $overview['missing_thumbnail_count'] ?? 0 }}</span><span class="label">missing thumbnails</span>
                                    </span>
                                @endif

                                @if (($overview['stale_thumbnail_count'] ?? 0) > 0)
                                    <span class="device-photo-summary-item is-problem"
                                          data-device-photo-stale-thumbnails-summary
                                          title="Thumbnail files where the original active photo no longer exists.">
                                        <span class="number" data-device-photo-stale-thumbnails-count>{{ $overview['stale_thumbnail_count'] ?? 0 }}</span><span class="label">stale thumbnails</span>
                                    </span>
                                @endif

                                @if (!empty($overview['legacy_deleted_storage_detected']))
                                    <span class="device-photo-summary-item is-problem"
                                          data-device-photo-legacy-deleted-summary
                                          title="Deleted photos were found in the old storage location. Migrate them to the new deleted photo storage.">
                                        <span class="number">{{ (int) (($overview['legacy_deleted_photo_count'] ?? 0) + ($overview['legacy_deleted_thumbnail_count'] ?? 0)) }}</span><span class="label">files need migration</span>
                                    </span>
                                @endif
                            @endif
                        </div>
                            </div>

                            @if (
                                ($can_delete && (($overview['deleted_photo_count'] ?? 0) > 0 || ($overview['deleted_thumbnail_count'] ?? 0) > 0))
                                || (count($overview['orphaned_photos'] ?? []) > 0)
                                || (count($overview['broken_links'] ?? []) > 0)
                                || ($can_delete && !empty($overview['legacy_deleted_storage_detected']))
                                || ($can_upload && !empty($overview['gd_available']) && !empty($overview['thumb_dir_writable']) && (($overview['missing_thumbnail_count'] ?? 0) > 0))
                                || ($can_upload && (($overview['stale_thumbnail_count'] ?? 0) > 0))
                            )
                                <div style="flex: 0 0 auto; display: flex; flex-direction: column; gap: 5px; align-items: flex-end;">
                                    @if ($can_delete && !empty($overview['legacy_deleted_storage_detected']))
                                        <form method="post"
                                              action="{{ url('plugin/device-photo-package/action') }}"
                                              data-device-photo-confirm-title="Migrate deleted photo storage?"
                                              data-device-photo-confirm-ok-text="Migrate storage"
                                              data-device-photo-confirm-ok-class="btn-warning"
                                              data-device-photo-confirm-ok-icon="fa-exchange"
                                              data-device-photo-confirm="Move deleted photos from the old storage location to the new deleted photo storage? Existing files will not be overwritten."
                                              style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="action" value="migrate_deleted_photos_storage">
                                            <input type="hidden" name="device_id" value="0">
                                            <input type="hidden" name="return_to" value="overview">

                                            <button type="submit"
                                                    class="btn btn-warning btn-xs"
                                                    title="Move deleted photos from old storage to the new deleted photo storage">
                                                <i class="fa fa-exchange"></i> Migrate deleted storage
                                            </button>
                                        </form>
                                    @endif

                                    @if ($can_delete && (($overview['deleted_photo_count'] ?? 0) > 0 || ($overview['deleted_thumbnail_count'] ?? 0) > 0))
                                        <a href="{{ url('plugin/device-photo') }}?view=restore-deleted"
                                           class="btn btn-primary btn-xs"
                                           title="Review, restore or permanently delete photos from the deleted folder">
                                            <i class="fa fa-trash"></i> Manage deleted photos
                                        </a>
                                    @endif

                                    @if (count($overview['orphaned_photos'] ?? []) > 0)
                                        <a href="{{ url('plugin/device-photo') }}#device-photo-orphaned-photos"
                                           class="btn btn-warning btn-xs"
                                           data-device-photo-orphaned-manage-button
                                           title="Jump to orphaned photos so they can be assigned or moved to deleted">
                                            <i class="fa fa-exclamation-triangle"></i> Manage orphaned photos
                                        </a>
                                    @endif

                                    @if (count($overview['broken_links'] ?? []) > 0)
                                        <a href="{{ url('plugin/device-photo') }}#device-photo-broken-links"
                                           class="btn btn-warning btn-xs"
                                           data-device-photo-broken-links-manage-button
                                           title="Jump to broken links so invalid link entries can be reviewed">
                                            <i class="fa fa-unlink"></i> Manage broken links
                                        </a>
                                    @endif

                                    @if ($can_upload && !empty($overview['gd_available']) && !empty($overview['thumb_dir_writable']) && (($overview['missing_thumbnail_count'] ?? 0) > 0))
                                        <form method="post"
                                              action="{{ url('plugin/device-photo-package/action') }}"
                                              data-device-photo-maintenance-form="missing-thumbnails"
                                              data-device-photo-ajax="1"
                                              data-device-photo-ajax-success="Thumbnail maintenance completed."
                                              data-device-photo-confirm-title="Generate missing thumbnails?"
                                              data-device-photo-confirm-ok-text="Generate"
                                              data-device-photo-confirm-ok-class="btn-warning"
                                              data-device-photo-confirm-ok-icon="fa-magic"
                                              data-device-photo-confirm="Generate missing thumbnails for active photos? Existing thumbnails will not be overwritten."
                                              style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="action" value="generate_missing_thumbnails">
                                            <input type="hidden" name="device_id" value="0">
                                            <input type="hidden" name="return_to" value="overview">

                                            <button type="submit"
                                                    class="btn btn-warning btn-xs"
                                                    title="Generate thumbnails for active photos that are missing thumbnails">
                                                <i class="fa fa-magic"></i> Generate missing thumbnails
                                            </button>
                                        </form>
                                    @endif

                                    @if ($can_upload && (($overview['stale_thumbnail_count'] ?? 0) > 0))
                                        <form method="post"
                                              action="{{ url('plugin/device-photo-package/action') }}"
                                              data-device-photo-maintenance-form="stale-thumbnails"
                                              data-device-photo-ajax="1"
                                              data-device-photo-ajax-success="Stale thumbnails cleaned."
                                              data-device-photo-confirm-title="Clean stale thumbnails?"
                                              data-device-photo-confirm-ok-text="Clean stale thumbnails"
                                              data-device-photo-confirm-ok-class="btn-warning"
                                              data-device-photo-confirm-ok-icon="fa-trash"
                                              data-device-photo-confirm="Remove stale thumbnails that no longer have a matching original photo?"
                                              style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="action" value="clean_stale_thumbnails">
                                            <input type="hidden" name="device_id" value="0">
                                            <input type="hidden" name="return_to" value="overview">

                                            <button type="submit"
                                                    class="btn btn-warning btn-xs"
                                                    title="Remove thumbnail files where the original active photo no longer exists">
                                                <i class="fa fa-trash"></i> Clean stale thumbnails
                                            </button>
                                        </form>
                                    @endif
                                </div>
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
                        var devices = window.DevicePhotoTargetDevices || [];

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

                        <div class="input-group" style="max-width: 640px; flex: 1 1 auto;">
                            <input
                                type="text"
                                id="device-photo-overview-filter"
                                class="form-control"
                                placeholder="Search by device ID or device name"
                            >
                            <span class="input-group-btn">
                                <button type="button" id="device-photo-overview-filter-clear" class="btn btn-default">
                                    Clear
                                </button>
                            </span>
                        </div>
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
                                        <span class="device-photo-sort-label">Linked from <span class="device-photo-sort-indicator"></span></span>
                                    </th>
                                    <th class="device-photo-sort-header" data-sort-key="linked_out" title="Sort by number of photos owned by this device, but shown on other devices.">
                                        <span class="device-photo-sort-label">Shared to <span class="device-photo-sort-indicator"></span></span>
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
                                                            <img data-device-photo-gallery="overview-device-{{ $row['device_id'] }}" data-device-photo-preview-src="{{ $photo['url'] }}"
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
                                        @include('device-photo::partials.overview-links-row', [
                                            'row' => $row,
                                        ])
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

                <h4 id="device-photo-orphaned-photos">Orphaned photos</h4>

                <p class="text-muted">
                    Orphaned photos are image files where the original LibreNMS Device ID no longer exists.
                    This can happen if a device was deleted from LibreNMS, restored with a different ID,
                    or if files were copied manually.
                    You can assign the photo to an existing device, download it, or delete it. Deleted photos are moved to the deleted folder and are not permanently removed.
                </p>

                @if (empty($overview['orphaned_photos']))
                    <div class="alert alert-info" data-device-photo-orphaned-empty>No orphaned photos found.</div>
                @else
                    <div class="alert alert-info" data-device-photo-orphaned-empty style="display: none;">No orphaned photos found.</div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 240px)); gap: 14px;" data-device-photo-orphaned-grid>
                        @foreach ($overview['orphaned_photos'] as $photo)
                            @include('device-photo::partials.orphaned-photo-card', [
                                'photo' => $photo,
                                'can_upload' => $can_upload,
                                'can_delete' => $can_delete,
                            ])
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
                            <i class="fa fa-exclamation-circle"></i>
                            <span id="device-photo-confirm-title">Confirm action</span>
                        </div>

                        <div id="device-photo-confirm-message" class="device-photo-confirm-message"></div>

                        <div class="device-photo-confirm-actions">
                            <button type="button" class="btn btn-default" id="device-photo-confirm-cancel">
                                Cancel
                            </button>
                            <button type="button" class="btn btn-primary" id="device-photo-confirm-ok">
                                <i class="fa fa-check" id="device-photo-confirm-ok-icon"></i>
                                <span id="device-photo-confirm-ok-text">Continue</span>
                            </button>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var pendingForm = null;
                        var backdrop = document.getElementById('device-photo-confirm-backdrop');
                        var title = document.getElementById('device-photo-confirm-title');
                        var message = document.getElementById('device-photo-confirm-message');
                        var ok = document.getElementById('device-photo-confirm-ok');
                        var okIcon = document.getElementById('device-photo-confirm-ok-icon');
                        var okText = document.getElementById('device-photo-confirm-ok-text');
                        var cancel = document.getElementById('device-photo-confirm-cancel');

                        if (!backdrop || !title || !message || !ok || !okIcon || !okText || !cancel) {
                            return;
                        }

                        document.addEventListener('submit', function (e) {
                            var form = e.target.closest('form[data-device-photo-confirm]');

                            if (!form) {
                                return;
                            }

                            if (form.getAttribute('data-device-photo-confirmed') === '1') {
                                return;
                            }

                            e.preventDefault();

                            pendingForm = form;

                            title.textContent = form.getAttribute('data-device-photo-confirm-title') || 'Confirm action';
                            message.textContent = form.getAttribute('data-device-photo-confirm') || 'Continue?';
                            okText.textContent = form.getAttribute('data-device-photo-confirm-ok-text') || 'Continue';

                            ok.className = 'btn ' + (form.getAttribute('data-device-photo-confirm-ok-class') || 'btn-primary');
                            okIcon.className = 'fa ' + (form.getAttribute('data-device-photo-confirm-ok-icon') || 'fa-check');

                            backdrop.style.display = 'flex';
                        }, true);
                        function submitNormally(form) {
                            form.removeAttribute('data-device-photo-ajax');
                            form.setAttribute('data-device-photo-confirmed', '1');
                            form.submit();
                        }

                        function maintenanceNumber(selector) {
                            var el = document.querySelector(selector);

                            if (!el) {
                                return 0;
                            }

                            var value = parseInt(el.textContent || '0', 10);

                            return isNaN(value) ? 0 : value;
                        }

                        function updateMaintenanceOkUi() {
                            var ok = document.querySelector('[data-device-photo-maintenance-ok]');

                            if (!ok) {
                                return;
                            }

                            var total =
                                maintenanceNumber('[data-device-photo-orphaned-count]') +
                                maintenanceNumber('[data-device-photo-broken-links-count]') +
                                maintenanceNumber('[data-device-photo-missing-thumbnails-count]') +
                                maintenanceNumber('[data-device-photo-stale-thumbnails-count]');

                            ok.style.display = total < 1 ? 'inline-flex' : 'none';
                        }

                        function updateBrokenLinksUi() {
                            var rows = document.querySelectorAll('[data-device-photo-ajax-row="broken-link"]');
                            var remaining = rows.length;
                            var count = document.querySelector('[data-device-photo-broken-links-count]');
                            var summary = document.querySelector('[data-device-photo-broken-links-summary]');
                            var manageButton = document.querySelector('[data-device-photo-broken-links-manage-button]');
                            var table = document.querySelector('[data-device-photo-broken-links-table]');
                            var empty = document.querySelector('[data-device-photo-broken-links-empty]');

                            if (count) {
                                count.textContent = String(remaining);
                            }

                            if (remaining < 1) {
                                if (summary) {
                                    summary.style.display = 'none';
                                }

                                if (manageButton) {
                                    manageButton.style.display = 'none';
                                }

                                if (table) {
                                    table.style.display = 'none';
                                }

                                if (empty) {
                                    empty.style.display = 'block';
                                }
                            }

                            updateMaintenanceOkUi();
                        }

                        function updateOrphanedPhotosUi() {
                            var rows = document.querySelectorAll('[data-device-photo-ajax-row="orphaned-photo"]');
                            var remaining = rows.length;
                            var count = document.querySelector('[data-device-photo-orphaned-count]');
                            var summary = document.querySelector('[data-device-photo-orphaned-summary]');
                            var manageButton = document.querySelector('[data-device-photo-orphaned-manage-button]');
                            var grid = document.querySelector('[data-device-photo-orphaned-grid]');
                            var empty = document.querySelector('[data-device-photo-orphaned-empty]');

                            if (count) {
                                count.textContent = String(remaining);
                            }

                            if (remaining < 1) {
                                if (summary) {
                                    summary.style.display = 'none';
                                }

                                if (manageButton) {
                                    manageButton.style.display = 'none';
                                }

                                if (grid) {
                                    grid.style.display = 'none';
                                }

                                if (empty) {
                                    empty.style.display = 'block';
                                }
                            }

                            updateMaintenanceOkUi();
                        }

                        function updateThumbnailMaintenanceUi(data) {
                            var stats = data && data.maintenance_stats ? data.maintenance_stats : null;

                            if (!stats) {
                                return;
                            }

                            var missing = parseInt(stats.missing_thumbnail_count || 0, 10);
                            var stale = parseInt(stats.stale_thumbnail_count || 0, 10);
                            var activeTotalMb = typeof stats.active_total_mb !== 'undefined' ? stats.active_total_mb : null;
                            var activePhotoMb = typeof stats.active_photo_mb !== 'undefined' ? stats.active_photo_mb : null;
                            var thumbnailMb = typeof stats.thumbnail_mb !== 'undefined' ? stats.thumbnail_mb : null;

                            var activeSize = document.querySelector('[data-device-photo-active-size]');
                            var activeSizeSummary = document.querySelector('[data-device-photo-active-size-summary]');

                            if (activeSize && activeTotalMb !== null) {
                                activeSize.textContent = String(activeTotalMb) + ' MB';
                            }

                            if (activeSizeSummary && activePhotoMb !== null && thumbnailMb !== null) {
                                activeSizeSummary.setAttribute(
                                    'title',
                                    'Total size of active photos and thumbnails. Originals: ' + String(activePhotoMb) + ' MB, thumbnails: ' + String(thumbnailMb) + ' MB.'
                                );
                            }

                            var missingSummary = document.querySelector('[data-device-photo-missing-thumbnails-summary]');
                            var missingCount = document.querySelector('[data-device-photo-missing-thumbnails-count]');
                            var missingForm = document.querySelector('[data-device-photo-maintenance-form="missing-thumbnails"]');

                            if (missingCount) {
                                missingCount.textContent = String(missing);
                            }

                            if (missingSummary && missing < 1) {
                                missingSummary.style.display = 'none';
                            }

                            if (missingForm && missing < 1) {
                                missingForm.style.display = 'none';
                            }

                            var staleSummary = document.querySelector('[data-device-photo-stale-thumbnails-summary]');
                            var staleCount = document.querySelector('[data-device-photo-stale-thumbnails-count]');
                            var staleForm = document.querySelector('[data-device-photo-maintenance-form="stale-thumbnails"]');

                            if (staleCount) {
                                staleCount.textContent = String(stale);
                            }

                            if (staleSummary && stale < 1) {
                                staleSummary.style.display = 'none';
                            }

                            if (staleForm && stale < 1) {
                                staleForm.style.display = 'none';
                            }

                            updateMaintenanceOkUi();
                        }

                        function submitAjax(form) {
                            window.DevicePhotoAjax.submitForm(form).then(function (result) {
                                var data = result.data;
                                var row = form.closest('[data-device-photo-ajax-row]');
                                var rowType = row ? row.getAttribute('data-device-photo-ajax-row') : '';

                                if (!row && form.getAttribute('data-device-photo-ajax-remove-card') === 'orphaned-photo') {
                                    row = form.closest('.device-photo-orphan-card');
                                    rowType = 'orphaned-photo';
                                }

                                if (row && row.parentNode) {
                                    row.parentNode.removeChild(row);
                                }

                                if (rowType === 'broken-link') {
                                    updateBrokenLinksUi();
                                } else if (rowType === 'orphaned-photo') {
                                    updateOrphanedPhotosUi();
                                }

                                updateThumbnailMaintenanceUi(data);

                                window.DevicePhotoAjax.toast((data && data.message) || form.getAttribute('data-device-photo-ajax-success') || 'Action completed.');
                            }).catch(function (error) {
                                console.error('DevicePhoto AJAX failed:', error);
                                submitNormally(form);
                            });
                        }

                        ok.addEventListener('click', function () {
                            if (!pendingForm) {
                                backdrop.style.display = 'none';
                                return;
                            }

                            var form = pendingForm;

                            pendingForm = null;
                            backdrop.style.display = 'none';

                            if (form.getAttribute('data-device-photo-ajax') === '1') {
                                submitAjax(form);
                                return;
                            }

                            form.setAttribute('data-device-photo-confirmed', '1');
                            form.submit();
                        });

                        cancel.addEventListener('click', function () {
                            pendingForm = null;
                            title.textContent = 'Confirm action';
                            message.textContent = '';
                            okText.textContent = 'Continue';
                            ok.className = 'btn btn-primary';
                            okIcon.className = 'fa fa-check';
                            backdrop.style.display = 'none';
                        });

                        backdrop.addEventListener('click', function (e) {
                            if (e.target === backdrop) {
                                pendingForm = null;
                                title.textContent = 'Confirm action';
                                message.textContent = '';
                                okText.textContent = 'Continue';
                                ok.className = 'btn btn-primary';
                                okIcon.className = 'fa fa-check';
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
                <h4 id="device-photo-broken-links">Broken links</h4>

                <p class="text-muted">
                    Broken links are photo link entries that point to an image file that no longer exists.
                    The target device still has a saved link, but the original photo file is missing.
                    Removing a broken link only removes the link entry. It does not delete any photo file.
                </p>

                @if (empty($overview['broken_links']))
                    <div class="alert alert-info" data-device-photo-broken-links-empty>No broken links found.</div>
                @else
                    <div class="alert alert-info" data-device-photo-broken-links-empty style="display: none;">No broken links found.</div>

                    <div class="table-responsive" data-device-photo-broken-links-table>
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
                                    @include('device-photo::partials.broken-link-row', [
                                        'link' => $link,
                                        'can_delete' => $can_delete,
                                    ])
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
                var clearFilterButton = document.getElementById('device-photo-overview-filter-clear');
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

                if (clearFilterButton && input) {
                    clearFilterButton.addEventListener('click', function () {
                        input.value = '';
                        currentPage = 1;
                        openLinkRows = {};
                        applyOverviewState();
                        input.focus();
                    });
                }

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
                <i class="fa fa-exclamation-circle"></i>
                <span id="device-photo-confirm-title">Confirm action</span>
            </div>

            <div id="device-photo-confirm-message" class="device-photo-confirm-message"></div>

            <div class="device-photo-confirm-actions">
                <button type="button" class="btn btn-default" id="device-photo-confirm-cancel">
                    Cancel
                </button>
                <button type="button" class="btn btn-primary" id="device-photo-confirm-ok">
                    <i class="fa fa-check" id="device-photo-confirm-ok-icon"></i>
                    <span id="device-photo-confirm-ok-text">Continue</span>
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var pendingForm = null;
            var backdrop = document.getElementById('device-photo-confirm-backdrop');
            var title = document.getElementById('device-photo-confirm-title');
            var message = document.getElementById('device-photo-confirm-message');
            var ok = document.getElementById('device-photo-confirm-ok');
            var okIcon = document.getElementById('device-photo-confirm-ok-icon');
            var okText = document.getElementById('device-photo-confirm-ok-text');
            var cancel = document.getElementById('device-photo-confirm-cancel');

            if (!backdrop || !title || !message || !ok || !okIcon || !okText || !cancel) {
                return;
            }

            function resetConfirm() {
                title.textContent = 'Confirm action';
                message.textContent = '';
                okText.textContent = 'Continue';
                ok.className = 'btn btn-primary';
                okIcon.className = 'fa fa-check';
            }

            function closeConfirm() {
                pendingForm = null;
                backdrop.style.display = 'none';
                resetConfirm();
            }

            document.addEventListener('submit', function (e) {
                var form = e.target.closest('form[data-device-photo-confirm]');

                if (!form) {
                    return;
                }

                if (form.getAttribute('data-device-photo-confirmed') === '1') {
                    return;
                }

                e.preventDefault();

                pendingForm = form;

                title.textContent = form.getAttribute('data-device-photo-confirm-title') || 'Confirm action';
                message.textContent = form.getAttribute('data-device-photo-confirm') || 'Continue?';
                okText.textContent = form.getAttribute('data-device-photo-confirm-ok-text') || 'Continue';

                ok.className = 'btn ' + (form.getAttribute('data-device-photo-confirm-ok-class') || 'btn-primary');
                okIcon.className = 'fa ' + (form.getAttribute('data-device-photo-confirm-ok-icon') || 'fa-check');

                backdrop.style.display = 'flex';
            }, true);
            function submitNormally(form) {
                form.removeAttribute('data-device-photo-ajax');
                form.setAttribute('data-device-photo-confirmed', '1');
                form.submit();
            }

            function submitAjax(form) {
                window.DevicePhotoAjax.submitForm(form).then(function (result) {
                    var data = result.data;
                    var row = form.closest('[data-device-photo-ajax-row]');
                    var rowType = row ? row.getAttribute('data-device-photo-ajax-row') : '';

                    if (rowType === 'outgoing-link') {
                        var linkedToBox = row.closest('[data-device-photo-linked-to-box]');

                        if (row.parentNode) {
                            row.parentNode.removeChild(row);
                        }

                        if (linkedToBox) {
                            var remaining = linkedToBox.querySelectorAll('[data-device-photo-ajax-row="outgoing-link"]').length;
                            var count = linkedToBox.querySelector('[data-device-photo-linked-to-count]');

                            if (count) {
                                count.textContent = String(remaining);
                            }

                            if (remaining < 1 && linkedToBox.parentNode) {
                                linkedToBox.parentNode.removeChild(linkedToBox);
                            }
                        }
                    } else if (form.getAttribute('data-device-photo-ajax-remove-card') === '1') {
                        var card = form.closest('.device-photo-manager-card');

                        if (card && card.parentNode) {
                            card.parentNode.removeChild(card);
                        }
                    }

                    if (
                        window.DevicePhotoLinkUi &&
                        typeof window.DevicePhotoLinkUi.restoreIncomingLinkButtonAfterRemove === 'function'
                    ) {
                        window.DevicePhotoLinkUi.restoreIncomingLinkButtonAfterRemove(form);
                    }

                    window.DevicePhotoAjax.toast((data && data.message) || form.getAttribute('data-device-photo-ajax-success') || 'Action completed.');
                }).catch(function (error) {
                    console.error('DevicePhoto AJAX failed:', error);
                    submitNormally(form);
                });
            }

            ok.addEventListener('click', function () {
                if (!pendingForm) {
                    closeConfirm();
                    return;
                }

                var form = pendingForm;

                pendingForm = null;
                backdrop.style.display = 'none';

                if (form.getAttribute('data-device-photo-ajax') === '1') {
                    submitAjax(form);
                    return;
                }

                form.setAttribute('data-device-photo-confirmed', '1');
                form.submit();
            });

            cancel.addEventListener('click', closeConfirm);

            backdrop.addEventListener('click', function (e) {
                if (e.target === backdrop) {
                    closeConfirm();
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && backdrop.style.display === 'flex') {
                    closeConfirm();
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
                box-shadow: 0 6px 18px rgba(0, 0, 0, 0.16);
                transition: box-shadow 0.15s ease, transform 0.15s ease, border-color 0.15s ease;
            }

            .device-photo-manager-card:hover {
                box-shadow: 0 10px 28px rgba(0, 0, 0, 0.24);
                transform: translateY(-2px);
            }

            .device-photo-manager-card.device-photo-owned-photo-card,
            .device-photo-manager-card.device-photo-linked-photo-card {
                background: #f3f3f3;
                border-color: #ddd;
            }

            .device-photo-card-image {
                width: 100%;
                max-height: 180px;
                object-fit: contain;
                background: #fff;
                border-radius: 5px;
                margin-bottom: 10px;
            }

            .device-photo-card-type-row {
                margin: 6px 0 4px 0;
            }

            .device-photo-card-meta {
                font-size: 12px;
                margin: 6px 0 8px 0;
                line-height: 1.35;
                word-break: break-all;
            }

            .device-photo-card-link-box {
                font-size: 12px;
                padding: 6px 8px;
                margin-bottom: 8px;
                border-radius: 6px;
            }

            .device-photo-card-action {
                margin-bottom: 8px;
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

            html.dark .device-photo-plugin .device-photo-manager-card.drop-before {
                box-shadow:
                    inset 10px 0 0 #5dade2,
                    0 0 0 1px rgba(93, 173, 226, 0.35) !important;
            }

            html.dark .device-photo-plugin .device-photo-manager-card.drop-after {
                box-shadow:
                    inset -10px 0 0 #5dade2,
                    0 0 0 1px rgba(93, 173, 226, 0.35) !important;
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

                        .device-photo-file-list-row {
                            display: flex;
                            align-items: center;
                            justify-content: flex-start;
                            gap: 8px;
                            padding: 3px 0;
                        }

                        .device-photo-file-list-name {
                            overflow-wrap: anywhere;
                        }

                        .device-photo-file-remove {
                            border: 0;
                            background: #d9534f;
                            color: #fff;
                            cursor: pointer;
                            font-weight: bold;
                            line-height: 1;
                            padding: 2px 7px;
                            border-radius: 4px;
                            min-width: 22px;
                            height: 22px;
                            text-align: center;
                        }

                        .device-photo-file-remove:hover {
                            background: #c9302c;
                            color: #fff;
                            text-decoration: none;
                        }
                    </style>

                    <div id="device-photo-dropzone" class="device-photo-dropzone">
                        <div class="main-text">
                            <i class="fa fa-cloud-upload"></i> Drop photos here or tap to add photos
                        </div>
                        <div class="sub-text">
                            Choose photos from your device or take a new photo
                        </div>

                        <input
                            id="device-photo-file"
                            type="file"
                            name="photos[]"
                            multiple
                            accept="image/*,.jpg,.jpeg,.png,.webp,.heic,.heif,image/jpeg,image/png,image/webp,image/heic,image/heif"
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

                    <div style="margin-top: 12px; display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                        <button type="submit" class="btn btn-primary" id="device-photo-upload-submit">
                            <i class="fa fa-upload"></i> Upload photos
                        </button>

                        <button type="button" class="btn btn-default" id="device-photo-clear-files" style="display: none;">
                            <i class="fa fa-times"></i> Clear all
                        </button>
                    </div>

                    <div class="text-muted" style="margin-top: 8px; font-size: 12px;">
                        Allowed file types: jpg, jpeg, png, webp, heic, heif. Max file size: 10 MB per file.
                    </div>

                    <div class="alert alert-info" style="margin-top: 12px; margin-bottom: 0; font-size: 12px; padding: 8px 10px;">
                        <div style="display: flex; flex-wrap: wrap; gap: 6px 8px; align-items: center;">
                            <strong style="margin-right: 4px;">Upload status:</strong>

                            <span class="label label-primary" title="PHP upload_max_filesize">
                                PHP upload_max_filesize: {{ $php_upload_max_filesize ?? 'unknown' }}
                            </span>

                            <span class="label label-primary" title="PHP post_max_size">
                                PHP post_max_size: {{ $php_post_max_size ?? 'unknown' }}
                            </span>

                            <span class="label {{ !empty($php_file_uploads) ? 'label-success' : 'label-danger' }}" title="PHP file_uploads">
                                PHP file_uploads: {{ !empty($php_file_uploads) ? 'Enabled' : 'Disabled' }}
                            </span>

                            <span class="label {{ !empty($heic_conversion_available) ? 'label-success' : 'label-warning' }}" title="HEIC/HEIF files are converted to JPG during upload when available.">
                                HEIC/HEIF: {{ !empty($heic_conversion_available) ? 'Available' : 'Not available' }}
                            </span>

                            <span class="label {{ !empty($exiftool_available) ? 'label-success' : 'label-warning' }}" title="ExifTool is used to write Photo taken metadata back to JPG/JPEG files.">
                                ExifTool: {{ !empty($exiftool_available) ? 'Available' : 'Not available' }}
                            </span>
                        </div>

                        <div style="margin-top: 8px; color: #31708f; display: flex; flex-wrap: wrap; gap: 6px 8px; align-items: center;">
                            <strong style="margin-right: 4px;">Web server:</strong>

                            <span class="label label-primary" title="{{ $web_server_software ?: 'SERVER_SOFTWARE not available' }}">
                                {{ $web_server_name ?? 'unknown' }}
                            </span>

                            <span>
                                If uploads fail with <strong>HTTP 413 Request Entity Too Large</strong>,
                                {{ $web_server_upload_hint ?? 'check your web server body size limit.' }}
                            </span>
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
                        var clearButton = document.getElementById('device-photo-clear-files');
                        var uploadSubmit = document.getElementById('device-photo-upload-submit');

                        function setFiles(files) {
                            var dt = new DataTransfer();

                            Array.prototype.forEach.call(files || [], function (file) {
                                dt.items.add(file);
                            });

                            fileInput.files = dt.files;
                            updateSelectedText();
                        }

                        function removeFileAt(indexToRemove) {
                            var files = Array.prototype.filter.call(fileInput.files || [], function (_file, index) {
                                return index !== indexToRemove;
                            });

                            setFiles(files);
                        }

                        function clearFiles() {
                            setFiles([]);
                        }

                        function updateSelectedText() {
                            var fileListBox = document.getElementById('device-photo-file-list');
                            var fileListItems = document.getElementById('device-photo-file-list-items');

                            fileListItems.innerHTML = '';

                            if (!fileInput.files || fileInput.files.length === 0) {
                                selected.textContent = 'No photos selected';
                                fileListBox.style.display = 'none';

                                if (clearButton) {
                                    clearButton.style.display = 'none';
                                }

                                if (uploadSubmit) {
                                    uploadSubmit.disabled = true;
                                }

                                return;
                            }

                            if (fileInput.files.length === 1) {
                                selected.textContent = '1 photo selected';
                            } else {
                                selected.textContent = fileInput.files.length + ' photos selected';
                            }

                            Array.prototype.forEach.call(fileInput.files, function (file, index) {
                                var li = document.createElement('li');
                                var row = document.createElement('div');
                                var name = document.createElement('span');
                                var removeButton = document.createElement('button');
                                var sizeMb = file.size / 1024 / 1024;

                                row.className = 'device-photo-file-list-row';
                                name.className = 'device-photo-file-list-name';
                                name.textContent = file.name + ' (' + sizeMb.toFixed(2) + ' MB)';

                                removeButton.type = 'button';
                                removeButton.className = 'device-photo-file-remove';
                                removeButton.setAttribute('aria-label', 'Remove ' + file.name);
                                removeButton.setAttribute('title', 'Remove this file');
                                removeButton.innerHTML = '&times;';
                                removeButton.addEventListener('click', function () {
                                    removeFileAt(index);
                                });

                                row.appendChild(removeButton);
                                row.appendChild(name);
                                li.appendChild(row);
                                fileListItems.appendChild(li);
                            });

                            fileListBox.style.display = 'block';

                            if (clearButton) {
                                clearButton.style.display = 'inline-block';
                            }

                            if (uploadSubmit) {
                                uploadSubmit.disabled = false;
                            }
                        }

                        dropzone.addEventListener('click', function () {
                            fileInput.click();
                        });

                        if (clearButton) {
                            clearButton.addEventListener('click', clearFiles);
                        }

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
                            var originalDevices = devices;

                            if (input.getAttribute('name') === 'owner_device_query') {
                                devices = ownerDevices;
                            }

                            var matches = findMatches(input.value);
                            devices = originalDevices;

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

                <h4 style="margin-bottom: 8px;">Device photos</h4>

                @if (count($photos) === 0 && count($linked_photos) === 0)
                    <div class="alert alert-info">No device photos found</div>
                @else
                    @if ((count($photos) + count($linked_photos)) > 0)
                    @if ($can_reorder)
                    <div class="device-photo-drag-hint">
                        Drag and drop photos to change the order. Changes are saved automatically.
                    </div>

                    <form method="post"
                          action="{{ url('plugin/device-photo-package/action') }}"
                          id="device-photo-order-form"
                          data-device-photo-ajax-success="Photo order saved."
                          style="margin-bottom: 14px;">
                        @csrf
                        <input type="hidden" name="action" value="save_order">
                        <input type="hidden" name="device_id" value="{{ $device->device_id }}">
                        <input type="hidden" name="order_json" id="device-photo-order-json" value="[]">
                        <input type="hidden" name="return_anchor" value="device-photo-manager-grid">
                    </form>

                    @else
                    <div class="device-photo-drag-hint">
                        You do not have permission to reorder photos.
                    </div>
                    @endif
                    @endif

                    <div class="device-photo-manager-grid" id="device-photo-manager-grid">
                        @foreach ($photos as $photo)
                            @include('device-photo::partials.manager-owned-photo-card', [
                                'photo' => $photo,
                                'device' => $device,
                                'can_reorder' => $can_reorder,
                                'can_upload' => $can_upload,
                                'can_delete' => $can_delete,
                                'exiftool_available' => $exiftool_available,
                            ])
                        @endforeach

                        @foreach ($linked_photos as $photo)
                            @include('device-photo::partials.manager-linked-photo-card', [
                                'photo' => $photo,
                                'device' => $device,
                                'can_reorder' => $can_reorder,
                                'can_delete' => $can_delete,
                            ])
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

                    @if ($can_reorder && ((count($photos) + count($linked_photos)) > 0))
                    <script>
                        (function () {
                            var grid = document.getElementById('device-photo-manager-grid');
                            var orderInput = document.getElementById('device-photo-order-json');
                            var dragged = null;

                            function cards() {
                                return Array.prototype.slice.call(grid.querySelectorAll('.device-photo-manager-card'));
                            }

                            function normalizeInitialDomOrder() {
                                /*
                                 * The server may use CSS order to mix owned and linked photos
                                 * while still rendering them in separate Blade loops.
                                 *
                                 * Drag/drop works on DOM order, so normalize the DOM to match
                                 * the visual order once, then remove CSS order values.
                                 */
                                cards()
                                    .map(function (card, index) {
                                        var order = parseInt(card.style.order || '', 10);

                                        return {
                                            card: card,
                                            order: isNaN(order) ? index : order,
                                            index: index
                                        };
                                    })
                                    .sort(function (a, b) {
                                        if (a.order === b.order) {
                                            return a.index - b.index;
                                        }

                                        return a.order - b.order;
                                    })
                                    .forEach(function (item) {
                                        item.card.style.order = '';
                                        grid.appendChild(item.card);
                                    });
                            }

                            function updateOrderJson() {
                                var order = cards().map(function (card) {
                                    return card.getAttribute('data-order-key');
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
                                e.dataTransfer.setData('text/plain', card.getAttribute('data-order-key'));
                            });

                            grid.addEventListener('dragend', function () {
                                if (dragged) {
                                    dragged.classList.remove('dragging');
                                }

                                clearDropClasses();
                                dragged = null;
                                updateOrderJson();
                            });

                            function closestDropTarget(x, y) {
                                var best = null;
                                var bestDistance = Infinity;

                                cards().forEach(function (card) {
                                    if (card === dragged) {
                                        return;
                                    }

                                    var box = card.getBoundingClientRect();

                                    /*
                                     * Treat the whole card as a generous drop zone.
                                     * Use distance from pointer to card center so it feels natural
                                     * even when the pointer is between cards in the grid.
                                     */
                                    var centerX = box.left + (box.width / 2);
                                    var centerY = box.top + (box.height / 2);
                                    var dx = x - centerX;
                                    var dy = y - centerY;
                                    var distance = Math.sqrt((dx * dx) + (dy * dy));

                                    if (distance < bestDistance) {
                                        bestDistance = distance;
                                        best = card;
                                    }
                                });

                                return best;
                            }

                            function dropAfterTarget(target, x) {
                                if (!target) {
                                    return false;
                                }

                                var box = target.getBoundingClientRect();

                                /*
                                 * Horizontal before/after still decides final position,
                                 * but closestDropTarget() makes it much easier to hit a card.
                                 */
                                return x > (box.left + (box.width / 2));
                            }

                            grid.addEventListener('dragover', function (e) {
                                e.preventDefault();

                                if (!dragged) {
                                    return;
                                }

                                var target = closestDropTarget(e.clientX, e.clientY);

                                clearDropClasses();

                                if (!target || target === dragged) {
                                    return;
                                }

                                if (dropAfterTarget(target, e.clientX)) {
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

                                var target = closestDropTarget(e.clientX, e.clientY);

                                if (!target || target === dragged) {
                                    clearDropClasses();
                                    updateOrderJson();
                                    return;
                                }

                                if (dropAfterTarget(target, e.clientX)) {
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
                                    saveOrderAjax();
                                }, 150);
                            });

                            var orderForm = document.getElementById('device-photo-order-form');
                            var saveOrderInProgress = false;

                            function saveOrderAjax() {
                                if (!orderForm || saveOrderInProgress) {
                                    return;
                                }

                                updateOrderJson();

                                var button = orderForm.querySelector('button[type="submit"]');

                                saveOrderInProgress = true;

                                if (button) {
                                    button.disabled = true;
                                }

                                window.DevicePhotoAjax.submitForm(orderForm).then(function (result) {
                                    var data = result.data;

                                    if (window.DevicePhotoAjax && typeof window.DevicePhotoAjax.toast === 'function') {
                                        window.DevicePhotoAjax.toast((data && data.message) || orderForm.getAttribute('data-device-photo-ajax-success') || 'Photo order saved.');
                                    }
                                }).catch(function (error) {
                                    console.error('DevicePhoto save order AJAX failed:', error);

                                    if (window.DevicePhotoAjax && typeof window.DevicePhotoAjax.toast === 'function') {
                                        window.DevicePhotoAjax.toast('Could not save photo order with AJAX. Check browser console.');
                                    }
                                }).finally(function () {
                                    saveOrderInProgress = false;

                                    if (button) {
                                        button.disabled = false;
                                    }
                                });
                            }

                            if (orderForm) {
                                orderForm.addEventListener('submit', function (e) {
                                    e.preventDefault();
                                    e.stopImmediatePropagation();

                                    saveOrderAjax();
                                }, true);
                            }

                            normalizeInitialDomOrder();
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

                    <form method="get" action="{{ url('plugin/device-photo') }}#device-photo-incoming-link" style="margin-bottom: 14px; position: relative;">
                        <input type="hidden" name="device_id" value="{{ $device->device_id }}">

                        <div class="input-group input-group-sm" style="max-width: 520px;">
                            <input
                                type="text"
                                name="owner_device_query"
                                class="form-control device-photo-target-input"
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
                            @php
                                $devicePhotoIncomingLinkedKeys = [];

                                foreach (($linked_photos ?? []) as $linkedPhoto) {
                                    $linkedOwnerDeviceId = (int) ($linkedPhoto['owner_device_id'] ?? 0);
                                    $linkedFilename = basename((string) ($linkedPhoto['filename'] ?? ''));

                                    if ($linkedOwnerDeviceId > 0 && $linkedFilename !== '') {
                                        $devicePhotoIncomingLinkedKeys[$linkedOwnerDeviceId . ':' . $linkedFilename] = true;
                                    }
                                }
                            @endphp

                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 230px)); gap: 14px; margin-bottom: 18px;">
                                @foreach ($incoming_owner_photos as $ownerPhoto)
                                    @php
                                        $devicePhotoIncomingLinkKey = (int) $incoming_owner_device->device_id . ':' . basename((string) ($ownerPhoto['filename'] ?? ''));
                                        $devicePhotoIncomingAlreadyLinked = isset($devicePhotoIncomingLinkedKeys[$devicePhotoIncomingLinkKey]);
                                    @endphp

                                    @include('device-photo::partials.incoming-owner-photo-card', [
                                        'ownerPhoto' => $ownerPhoto,
                                        'device' => $device,
                                        'incoming_owner_device' => $incoming_owner_device,
                                        'incoming_owner_query' => $incoming_owner_query ?? '',
                                        'devicePhotoIncomingAlreadyLinked' => $devicePhotoIncomingAlreadyLinked,
                                    ])
                                @endforeach
                            </div>
                        @endif
                    @endif
                @endif
            </div>
        </div>
        @endif
    @endif
    @endif

    @if (request()->query('view') !== 'restore-deleted')
    <div id="device-photo-set-taken-modal" class="device-photo-confirm-backdrop">
        <div class="device-photo-confirm-box" style="max-width: 460px; padding-bottom: 18px;">
            <h4 style="margin-top: 0;">
                <i class="fa fa-clock-o"></i> Set photo taken
            </h4>

            <div class="alert alert-warning" style="font-size: 12px; padding: 8px 10px; margin-bottom: 12px;">
                <strong>Warning:</strong>
                This writes the selected date/time back to the JPG/JPEG EXIF metadata in the original photo file.
            </div>

            <form method="post"
                  action="{{ url('plugin/device-photo-package/action') }}"
                  id="device-photo-set-taken-form"
                  data-device-photo-ajax-success="Photo taken updated.">
                @csrf
                <input type="hidden" name="action" value="set_photo_taken">
                <input type="hidden" name="device_id" id="device-photo-set-taken-device-id" value="{{ $device ? $device->device_id : 0 }}">
                <input type="hidden" name="filename" id="device-photo-set-taken-filename" value="">
                <input type="hidden" name="return_anchor" id="device-photo-set-taken-return-anchor" value="">

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
            var returnAnchorInput = document.getElementById('device-photo-set-taken-return-anchor');
            var dateInput = document.getElementById('device-photo-set-taken-input');
            var cancelButton = document.getElementById('device-photo-set-taken-cancel');

            if (!modal || !form || !filenameInput || !dateInput) {
                return;
            }

            function closeModal() {
                modal.style.display = 'none';
                filenameInput.value = '';
                if (returnAnchorInput) {
                    returnAnchorInput.value = '';
                }
                dateInput.value = '';
            }

            document.querySelectorAll('.device-photo-set-taken-button').forEach(function (button) {
                button.addEventListener('click', function () {
                    filenameInput.value = button.getAttribute('data-filename') || '';
                    if (returnAnchorInput) {
                        returnAnchorInput.value = button.getAttribute('data-return-anchor') || '';
                    }
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

            function findPhotoCardByFilename(filename) {
                var cards = document.querySelectorAll('.device-photo-manager-card[data-filename]');

                for (var i = 0; i < cards.length; i++) {
                    if (cards[i].getAttribute('data-filename') === filename) {
                        return cards[i];
                    }
                }

                return null;
            }

            function formatDateForDisplay(value) {
                var date = new Date(value);

                if (isNaN(date.getTime())) {
                    return value || '';
                }

                return date.toLocaleDateString(undefined, {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit'
                });
            }

            function updatePhotoTakenCard(data) {
                if (!data || !data.filename) {
                    return;
                }

                var card = findPhotoCardByFilename(data.filename);

                if (!card) {
                    return;
                }

                var image = card.querySelector('[data-device-photo-preview-src]');
                var button = card.querySelector('.device-photo-set-taken-button');
                var meta = card.querySelector('[data-device-photo-meta]');
                var row = card.querySelector('[data-device-photo-taken-row]');
                var display = card.querySelector('[data-device-photo-taken-display]');

                if (image && data.photo_taken_iso) {
                    image.setAttribute('data-device-photo-taken', data.photo_taken_iso);
                }

                if (button && data.photo_taken_input) {
                    button.setAttribute('data-photo-taken', data.photo_taken_input);
                }

                if (!row && meta) {
                    row = document.createElement('div');
                    row.setAttribute('data-device-photo-taken-row', '1');

                    var label = document.createElement('strong');
                    label.textContent = 'Photo taken:';

                    display = document.createElement('span');
                    display.className = 'device-photo-local-date';
                    display.setAttribute('data-device-photo-taken-display', '1');
                    display.setAttribute('data-device-photo-format', 'date');

                    row.appendChild(label);
                    row.appendChild(document.createTextNode(' '));
                    row.appendChild(display);
                    meta.insertBefore(row, meta.firstChild);
                }

                if (display) {
                    display.setAttribute('data-device-photo-date', data.photo_taken_iso || '');
                    display.textContent = formatDateForDisplay(data.photo_taken_iso || data.photo_taken_display || '');
                }
            }

            form.addEventListener('submit', function (e) {
                e.preventDefault();
                e.stopImmediatePropagation();

                var button = form.querySelector('button[type="submit"]');

                if (button) {
                    button.disabled = true;
                }

                window.DevicePhotoAjax.submitForm(form).then(function (result) {
                    var data = result.data;

                    updatePhotoTakenCard(data);
                    closeModal();

                    if (window.DevicePhotoAjax && typeof window.DevicePhotoAjax.toast === 'function') {
                        window.DevicePhotoAjax.toast((data && data.message) || form.getAttribute('data-device-photo-ajax-success') || 'Photo taken updated.');
                    }
                }).catch(function (error) {
                    console.error('DevicePhoto set photo taken AJAX failed:', error);

                    if (window.DevicePhotoAjax && typeof window.DevicePhotoAjax.toast === 'function') {
                        window.DevicePhotoAjax.toast('Could not update photo taken. Check browser console.');
                    }
                }).finally(function () {
                    if (button) {
                        button.disabled = false;
                    }
                });
            }, true);

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
    @endif

    <hr>

    @include('device-photo::partials.footer')
</div>
