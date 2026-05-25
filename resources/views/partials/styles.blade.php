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

</style>
