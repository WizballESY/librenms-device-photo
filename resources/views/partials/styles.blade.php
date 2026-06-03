<style>
    html.dark .device-photo-plugin .device-photo-alpha-notice.alert-warning {
        background: #3b3124 !important;
        border-color: #5f4728 !important;
        color: #ead6b8 !important;
    }

    html.dark .device-photo-plugin .device-photo-alpha-notice.alert-warning strong {
        color: #f4c77c !important;
    }

    html.dark .device-photo-plugin .device-photo-alpha-notice.alert-warning a {
        color: #8ecbff !important;
        font-weight: 600;
    }

    html.dark .device-photo-plugin .device-photo-alpha-notice.alert-warning a:hover,
    html.dark .device-photo-plugin .device-photo-alpha-notice.alert-warning a:focus {
        color: #b7ddff !important;
    }

    html.dark .device-photo-version-badge.label-warning {
        background: #8a6428 !important;
        border-color: #a77a32 !important;
        color: #fff3d6 !important;
    }

    html.dark .device-photo-version-badge.label-warning:hover,
    html.dark .device-photo-version-badge.label-warning:focus {
        background: #9a7330 !important;
        border-color: #bd8b3b !important;
        color: #ffffff !important;
    }

    /*
     * Shared photo preview modal.
     */
    .device-photo-shared-modal {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 25000;
        background: rgba(0,0,0,0.88);
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .device-photo-shared-modal.is-open {
        display: flex;
    }

    .device-photo-shared-modal-inner {
        width: 96vw;
        height: 92vh;
        overflow: hidden;
        cursor: grab;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .device-photo-shared-modal-inner.dragging {
        cursor: grabbing;
    }

    .device-photo-shared-modal img {
        max-width: 92vw;
        max-height: 86vh;
        transform-origin: center center;
        user-select: none;
        -webkit-user-drag: none;
        transition: transform 0.04s linear;
    }

    .device-photo-shared-meta {
        display: none;
        margin-top: 10px;
        padding: 7px 10px;
        background: rgba(0,0,0,0.68);
        color: #eee;
        border-radius: 6px;
        font-size: 12px;
        line-height: 1.35;
        text-align: center;
        max-width: 92vw;
    }

    .device-photo-shared-meta span + span {
        margin-left: 14px;
    }

    .device-photo-shared-close {
        position: absolute;
        top: 14px;
        right: 18px;
        z-index: 25002;
        border: 0;
        background: rgba(255,255,255,0.95);
        color: #333;
        border-radius: 50%;
        width: 34px;
        height: 34px;
        font-size: 22px;
        line-height: 30px;
        cursor: pointer;
    }

    .device-photo-shared-toolbar {
        position: absolute;
        top: 14px;
        left: 14px;
        z-index: 25002;
        display: flex;
        gap: 6px;
        align-items: center;
        background: rgba(255,255,255,0.95);
        border-radius: 8px;
        padding: 6px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.35);
    }

    .device-photo-shared-toolbar button {
        border: 1px solid #ccc;
        background: #fff;
        border-radius: 5px;
        padding: 4px 10px;
        cursor: pointer;
        font-weight: bold;
    }

    .device-photo-shared-toolbar span {
        min-width: 48px;
        text-align: center;
        color: #333;
        font-size: 12px;
    }

    .device-photo-shared-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 25002;
        border: 0;
        background: rgba(255,255,255,0.88);
        color: #333;
        border-radius: 50%;
        width: 44px;
        height: 44px;
        font-size: 30px;
        line-height: 38px;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0,0,0,0.35);
    }

    .device-photo-shared-nav:hover {
        background: rgba(255,255,255,1);
    }

    .device-photo-shared-nav.is-hidden {
        display: none;
    }

    .device-photo-shared-prev {
        left: 18px;
    }

    .device-photo-shared-next {
        right: 18px;
    }

    .device-photo-shared-counter {
        position: absolute;
        bottom: 14px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 25002;
        background: rgba(0,0,0,0.68);
        color: #eee;
        border-radius: 999px;
        padding: 5px 11px;
        font-size: 12px;
        line-height: 1.2;
    }

    .device-photo-shared-counter.is-hidden {
        display: none;
    }

    html.dark .device-photo-shared-close {
        background: rgba(47, 56, 66, 0.96) !important;
        border: 1px solid #4b5563 !important;
        color: #f3f4f6 !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.45) !important;
    }

    html.dark .device-photo-shared-close:hover,
    html.dark .device-photo-shared-close:focus {
        background: rgba(70, 85, 100, 0.98) !important;
        border-color: #718193 !important;
        color: #ffffff !important;
    }

    html.dark .device-photo-shared-toolbar {
        background: rgba(31, 37, 44, 0.96) !important;
        border: 1px solid #4b5563 !important;
        box-shadow: 0 2px 10px rgba(0,0,0,0.55) !important;
    }

    html.dark .device-photo-shared-toolbar button {
        background: #3a4652 !important;
        border-color: #5b6875 !important;
        color: #f3f4f6 !important;
    }

    html.dark .device-photo-shared-toolbar button:hover,
    html.dark .device-photo-shared-toolbar button:focus {
        background: #465564 !important;
        border-color: #718193 !important;
        color: #ffffff !important;
    }

    html.dark .device-photo-shared-toolbar span {
        color: #f3f4f6 !important;
    }

    html.dark .device-photo-shared-nav {
        background: rgba(47, 56, 66, 0.92) !important;
        border: 1px solid #4b5563 !important;
        color: #f3f4f6 !important;
        box-shadow: 0 2px 10px rgba(0,0,0,0.55) !important;
    }

    html.dark .device-photo-shared-nav:hover,
    html.dark .device-photo-shared-nav:focus {
        background: rgba(70, 85, 100, 0.98) !important;
        border-color: #718193 !important;
        color: #ffffff !important;
    }

    html.dark .device-photo-shared-meta,
    html.dark .device-photo-shared-counter {
        background: rgba(47, 56, 66, 0.94) !important;
        border: 1px solid #4b5563 !important;
        color: #f3f4f6 !important;
        box-shadow: 0 2px 10px rgba(0,0,0,0.45) !important;
    }

    html.dark .device-photo-shared-meta strong {
        color: #ffffff !important;
    }

    [data-device-photo-preview-src] {
        cursor: zoom-in;
    }


    /*
     * Base plugin dark mode compatibility.
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

    .device-photo-plugin .device-photo-orphan-card .device-photo-restore-target-input {
        font-size: 12px;
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


    /*
     * Page dark mode detail fixes.
     */
    /*
     * Dark mode labels for expanded overview linked-photo rows.
     */
    html.dark .device-photo-plugin .device-photo-links-row .label-primary {
        background-color: #337ab7 !important;
        color: #ffffff !important;
    }

    html.dark .device-photo-plugin .device-photo-links-row .label-success {
        background-color: #3c9b5f !important;
        color: #ffffff !important;
    }

    html.dark .device-photo-plugin .device-photo-links-row .label .fa {
        background: transparent !important;
        color: inherit !important;
        border: 0 !important;
        box-shadow: none !important;
    }

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
        background: #3a4652 !important;
        border-color: #5b6875 !important;
        color: #f3f4f6 !important;
    }

    html.dark .device-photo-plugin .device-photo-orphan-card .input-group-btn .btn:hover,
    html.dark .device-photo-plugin .device-photo-orphan-card .input-group-btn .btn:focus,
    html.dark .device-photo-plugin .device-photo-orphan-card .btn-default:hover,
    html.dark .device-photo-plugin .device-photo-orphan-card .btn-default:focus {
        background: #465564 !important;
        border-color: #718193 !important;
        color: #ffffff !important;
    }

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


    /*
     * Manage photo card dark mode fixes.
     */
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

    html.dark .device-photo-plugin .device-photo-owned-photo-card [data-device-photo-linked-to-box].alert-info {
        background: #26303a !important;
        border-color: #4b5563 !important;
        color: #d8dee9 !important;
    }

    html.dark .device-photo-plugin .device-photo-owned-photo-card [data-device-photo-linked-to-box].alert-info.device-photo-sharing-active {
        border-left-color: #5dade2 !important;
    }

    html.dark .device-photo-plugin .device-photo-linked-photo-card strong {
        color: #f3f4f6 !important;
    }

    html.dark .device-photo-plugin .device-photo-linked-photo-card a {
        color: #8ecbff !important;
    }

    html.dark .device-photo-plugin .device-photo-owned-photo-card [data-device-photo-linked-to-box] .device-photo-linked-to-device-name a {
        color: #8ecbff !important;
    }

    html.dark .device-photo-plugin .device-photo-owned-photo-card [data-device-photo-linked-to-box] .device-photo-linked-to-device-name a:hover,
    html.dark .device-photo-plugin .device-photo-owned-photo-card [data-device-photo-linked-to-box] .device-photo-linked-to-device-name a:focus {
        color: #b7dcff !important;
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


    /*
     * Restore deleted photo device suggestions.
     */
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


    /*
     * Overview summary panels and links rows.
     */
    .device-photo-links-row td {
        border-top: 0;
    }

    .device-photo-links-row td > div,
    .device-photo-links-row td {
        background: #fafafa;
    }

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


    /*
     * Overview table sorting controls.
     */
    .device-photo-sort-header {
        cursor: pointer;
        user-select: none;
        white-space: nowrap;
    }

    .device-photo-sort-header:hover {
        background: #f5f5f5;
    }

    html.dark .device-photo-plugin .device-photo-sort-header:hover {
        background: #35414d !important;
        color: #f3f4f6 !important;
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


    /*
     * Orphaned photo assignment suggestions.
     */
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


    /*
     * Confirm dialog base styles.
     */
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


    /*
     * Manage device photo grid and cards.
     */
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

    .device-photo-linked-owner-device {
        margin-top: 8px;
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


    /*
     * Upload dropzone and selected file list.
     */
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


    /*
     * Scoped device target autocomplete suggestions.
     */
    /*
     * Device Photos Overview target suggestions.
     */
    .device-photo-overview-page .device-photo-target-suggestions {
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

    .device-photo-overview-page .device-photo-target-suggestion {
        padding: 6px 8px;
        cursor: pointer;
        border-bottom: 1px solid #eee;
    }

    .device-photo-overview-page .device-photo-target-suggestion:hover {
        background: #f3f7fb;
    }

    .device-photo-overview-page .device-photo-target-suggestion .device-id {
        font-family: monospace;
        color: #b00040;
    }

    .device-photo-overview-page .device-photo-target-suggestion .device-name {
        margin-left: 6px;
    }

    /*
     * Manage Device Photos target suggestions.
     */
    .device-photo-manage-page .device-photo-target-suggestions {
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

    .device-photo-manage-page .device-photo-target-suggestion {
        padding: 6px 8px;
        cursor: pointer;
        border-bottom: 1px solid #eee;
    }

    .device-photo-manage-page .device-photo-target-suggestion:hover {
        background: #f3f7fb;
    }

    .device-photo-manage-page .device-photo-target-suggestion .device-id {
        font-family: monospace;
        color: #b00040;
    }

    .device-photo-manage-page .device-photo-target-suggestion .device-name {
        margin-left: 6px;
    }


    /*
     * Device overview widget dark mode.
     * LibreNMS dark mode fix for the Device Photos widget wrapper.
     */
    html.dark .device-photo-wrapper {
        background: #2f3842 !important;
        border-color: #4b5563 !important;
        color: #d8dee9 !important;
    }

    html.dark .device-photo-wrapper .device-photo-title,
    html.dark .device-photo-wrapper .device-photo-title strong,
    html.dark .device-photo-wrapper h1,
    html.dark .device-photo-wrapper h2,
    html.dark .device-photo-wrapper h3,
    html.dark .device-photo-wrapper h4,
    html.dark .device-photo-wrapper h5 {
        color: #f3f4f6 !important;
    }

    html.dark .device-photo-wrapper .device-photo-card {
        background: #26303a !important;
        border-color: #4b5563 !important;
        color: #d8dee9 !important;
        box-shadow: none !important;
    }

    html.dark .device-photo-wrapper .device-photo-card:hover {
        background: #2f3842 !important;
        border-color: #64748b !important;
        box-shadow: 0 3px 8px rgba(0,0,0,0.35) !important;
    }

    html.dark .device-photo-wrapper .device-photo-card img {
        background: #1f252c !important;
    }

    html.dark .device-photo-wrapper .device-photo-options {
        background: #26303a !important;
        border-color: #4b5563 !important;
        color: #f3f4f6 !important;
    }

    html.dark .device-photo-wrapper .device-photo-options:hover {
        background: #35414d !important;
        border-color: #64748b !important;
    }

    html.dark .device-photo-wrapper .device-photo-empty {
        background: #26303a !important;
        border-color: #4b5563 !important;
        color: #d8dee9 !important;
    }


    /*
     * LibreNMS Device Overview widget base styles.
     */
    .device-photo-wrapper {
        background: #f3f3f3;
        border: 1px solid #dedede;
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 22px;
    }

    .device-photo-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid #e1e1e1;
    }

    .device-photo-title {
        font-weight: bold;
        font-size: 15px;
        color: #333;
    }

    .device-photo-title i {
        margin-right: 6px;
    }

    .device-photo-options {
        border: 1px solid #ccc;
        background: #fff;
        border-radius: 6px;
        padding: 2px 9px;
        cursor: pointer;
        font-weight: bold;
        line-height: 20px;
    }

    .device-photo-options:hover {
        background: #f7f7f7;
    }

    .device-photo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 190px));
        gap: 14px;
        align-items: start;
    }

    .device-photo-linked-tooltip {
        display: none;
        position: absolute;
        top: 34px;
        right: 6px;
        max-width: 220px;
        background: rgba(0, 0, 0, 0.82);
        color: #fff;
        font-size: 11px;
        line-height: 1.35;
        padding: 6px 8px;
        border-radius: 6px;
        z-index: 3;
        pointer-events: none;
        text-align: left;
        box-shadow: 0 2px 8px rgba(0,0,0,0.25);
    }

    .device-photo-card:hover .device-photo-linked-tooltip {
        display: block;
    }

    .device-photo-linked-tooltip code {
        color: #fff;
        background: transparent;
        padding: 0;
    }

    .device-photo-linked-icon {
        position: absolute;
        top: 6px;
        right: 6px;
        width: 22px;
        height: 22px;
        line-height: 22px;
        text-align: center;
        border-radius: 50%;
        background: rgba(0, 0, 0, 0.72);
        color: #fff;
        font-size: 11px;
        z-index: 2;
        pointer-events: none;
    }

    .device-photo-card {
        position: relative;
        background: #fff;
        border: 1px solid #d7d7d7;
        border-radius: 8px;
        padding: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12);
        cursor: pointer;
        transition: transform 0.12s ease, box-shadow 0.12s ease;
    }

    .device-photo-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(0,0,0,0.18);
    }

    .device-photo-card img {
        display: block;
        width: 100%;
        max-height: 220px;
        object-fit: contain;
        border-radius: 5px;
    }

    .device-photo-empty {
        padding: 12px 14px;
        background: #eef7fb;
        border: 1px solid #c7e5f2;
        border-radius: 6px;
        color: #31708f;
    }

    .device-photo-count {
        margin-bottom: 10px;
        color: #777;
        font-size: 12px;
    }


    .device-photo-maintenance-overlay {
        position: fixed;
        inset: 0;
        z-index: 10550;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(0, 0, 0, 0.35);
        backdrop-filter: blur(1px);
    }

    .device-photo-maintenance-overlay-box {
        width: min(360px, 92vw);
        padding: 26px 30px;
        border-radius: 14px;
        background: #fff;
        color: #333;
        text-align: center;
        box-shadow: 0 18px 48px rgba(0, 0, 0, 0.35);
    }

    .device-photo-maintenance-overlay-spinner {
        margin-bottom: 12px;
        font-size: 42px;
        line-height: 1;
    }

    .device-photo-plugin .fa-spin,
    .device-photo-maintenance-overlay .fa-spin {
        animation: device-photo-spin 1s infinite linear;
    }

    @keyframes device-photo-spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(359deg);
        }
    }

    .device-photo-maintenance-overlay-title {
        font-size: 17px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .device-photo-maintenance-overlay-help {
        color: #666;
        font-size: 13px;
    }

    html.dark .device-photo-maintenance-overlay-box {
        background: #1f2933;
        color: #f5f5f5;
        box-shadow: 0 18px 48px rgba(0, 0, 0, 0.65);
    }

    html.dark .device-photo-maintenance-overlay-help {
        color: #c7d0d9;
    }


    .device-photo-owner-change-modal-box {
        max-width: 520px;
        padding-bottom: 18px;
    }

    .device-photo-owner-change-label {
        font-size: 12px;
    }

    .device-photo-owner-change-target-wrap {
        position: relative;
    }

    .device-photo-owner-change-current-file {
        font-size: 12px;
    }

    .device-photo-owner-change-actions {
        margin-top: 16px;
    }


    .device-photo-linked-to-summary {
        display: block;
    }

    .device-photo-linked-to-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 4px;
    }

    .device-photo-linked-to-title {
        margin-bottom: 0;
    }

    .device-photo-linked-to-status-line {
        margin-bottom: 2px;
    }

    .device-photo-card-link-box .device-photo-linked-to-summary {
        margin-bottom: -2px;
    }

    .device-photo-linked-to-toggle-row {
        text-align: right;
        flex-shrink: 0;
    }

    .device-photo-linked-to-hidden {
        display: none !important;
    }

    .device-photo-card-link-box.device-photo-sharing-active {
        border-left: 4px solid #337ab7;
    }

    .device-photo-sharing-status {
        display: inline-block;
        color: #245269;
        font-weight: 600;
        line-height: 1.35;
    }

    .device-photo-sharing-status-icon {
        margin-right: 5px;
        opacity: 0.85;
    }

    html.dark .device-photo-plugin .device-photo-card-link-box.device-photo-sharing-active {
        border-left-color: #5dade2;
    }

    html.dark .device-photo-plugin .device-photo-sharing-status {
        color: #b7d7ef;
    }

    html.dark .device-photo-plugin .device-photo-sharing-status-icon {
        color: #9fc5df;
    }

    html.dark .device-photo-plugin .device-photo-linked-to-toggle {
        border-color: #9ca3af;
        background: #374151;
        color: #f3f4f6;
    }

    html.dark .device-photo-plugin .device-photo-linked-to-toggle:hover,
    html.dark .device-photo-plugin .device-photo-linked-to-toggle:focus {
        border-color: #d1d5db;
        background: #4b5563;
        color: #ffffff;
    }

    .device-photo-linked-to-toggle {
        flex: 0 0 auto;
    }

    .device-photo-linked-to-list {
        margin-top: 8px;
    }

    .device-photo-linked-to-row {
        margin-bottom: 8px;
        padding-bottom: 8px;
        border-bottom: 1px solid #eadfbf;
    }

    .device-photo-owned-photo-card [data-device-photo-linked-to-box].alert-info .device-photo-linked-to-row {
        border-bottom-color: #8fa9bc;
    }

    .device-photo-linked-to-row:last-child {
        margin-bottom: 0;
    }

    .device-photo-linked-to-device-name {
        word-break: break-word;
    }

    .device-photo-linked-to-remove-form {
        margin-top: 6px;
    }

    html.dark .device-photo-plugin .device-photo-linked-to-row {
        border-bottom-color: #4b5563;
    }


    .device-photo-card-action-note {
        font-size: 12px;
        margin-bottom: 4px;
    }

    .device-photo-card-action-note-spaced {
        margin-bottom: 8px;
    }

    .device-photo-add-link-form {
        margin-bottom: 8px;
        position: relative;
    }


    .device-photo-linked-photo-card {
        position: relative;
    }

    .device-photo-linked-photo-card .device-photo-linked-icon {
        display: block;
        opacity: 1;
        visibility: visible;
        pointer-events: none;
        z-index: 4;
    }

</style>
