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
