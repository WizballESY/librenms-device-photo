<style>
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
        max-width: 96vw;
        max-height: 92vh;
        overflow: hidden;
        cursor: grab;
        display: flex;
        flex-direction: column;
        align-items: center;
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

    [data-device-photo-preview-src] {
        cursor: zoom-in;
    }
</style>

<div id="device-photo-shared-modal" class="device-photo-shared-modal">
    <button type="button" class="device-photo-shared-close" id="device-photo-shared-close">&times;</button>

    <div class="device-photo-shared-toolbar" onclick="event.stopPropagation();">
        <button type="button" id="device-photo-shared-zoom-out">−</button>
        <span id="device-photo-shared-zoom-label">100%</span>
        <button type="button" id="device-photo-shared-zoom-in">+</button>
        <button type="button" id="device-photo-shared-reset">Reset</button>
    </div>

    <div id="device-photo-shared-inner" class="device-photo-shared-modal-inner">
        <img id="device-photo-shared-img" src="" alt="Device photo" draggable="false">
        <div id="device-photo-shared-meta" class="device-photo-shared-meta"></div>
    </div>
</div>

<script>
    window.DevicePhotoSharedModal = window.DevicePhotoSharedModal || (function () {
        var initialized = false;

        function formatDate(value) {
            if (!value) {
                return '';
            }

            var date = new Date(value);

            if (isNaN(date.getTime())) {
                return '';
            }

            return date.toLocaleString(undefined, {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function init() {
            if (initialized) {
                return;
            }

            initialized = true;

            var modal = document.getElementById('device-photo-shared-modal');
            var inner = document.getElementById('device-photo-shared-inner');
            var img = document.getElementById('device-photo-shared-img');
            var meta = document.getElementById('device-photo-shared-meta');
            var closeBtn = document.getElementById('device-photo-shared-close');
            var zoomIn = document.getElementById('device-photo-shared-zoom-in');
            var zoomOut = document.getElementById('device-photo-shared-zoom-out');
            var resetBtn = document.getElementById('device-photo-shared-reset');
            var zoomLabel = document.getElementById('device-photo-shared-zoom-label');

            if (!modal || !inner || !img) {
                return;
            }

            var state = {
                scale: 1,
                x: 0,
                y: 0,
                dragging: false,
                startX: 0,
                startY: 0
            };

            function updateTransform() {
                img.style.transform = 'translate(' + state.x + 'px, ' + state.y + 'px) scale(' + state.scale + ')';

                if (zoomLabel) {
                    zoomLabel.textContent = Math.round(state.scale * 100) + '%';
                }
            }

            function resetImage() {
                state.scale = 1;
                state.x = 0;
                state.y = 0;
                state.dragging = false;
                updateTransform();
            }

            function openPreview(src, takenIso, fileIso) {
                img.src = src;

                if (meta) {
                    var parts = [];
                    var takenText = formatDate(takenIso);
                    var fileText = formatDate(fileIso);

                    if (takenText) {
                        parts.push('<span><strong>Photo taken:</strong> ' + takenText + '</span>');
                    }

                    if (fileText) {
                        parts.push('<span><strong>File date:</strong> ' + fileText + '</span>');
                    }

                    meta.innerHTML = parts.join('');
                    meta.style.display = parts.length > 0 ? 'block' : 'none';
                }

                resetImage();
                modal.classList.add('is-open');
            }

            function closePreview() {
                modal.classList.remove('is-open');
                img.src = '';

                if (meta) {
                    meta.innerHTML = '';
                    meta.style.display = 'none';
                }

                resetImage();
            }

            document.addEventListener('click', function (e) {
                var el = e.target.closest('[data-device-photo-preview-src]');

                if (!el) {
                    return;
                }

                e.preventDefault();
                e.stopPropagation();

                var src = el.getAttribute('data-device-photo-preview-src');
                var takenIso = el.getAttribute('data-device-photo-taken') || '';
                var fileIso = el.getAttribute('data-device-photo-file-date') || '';

                if (src) {
                    openPreview(src, takenIso, fileIso);
                }
            }, true);

            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    closePreview();
                }
            });

            if (closeBtn) {
                closeBtn.addEventListener('click', closePreview);
            }

            if (zoomIn) {
                zoomIn.addEventListener('click', function () {
                    state.scale = Math.min(8, state.scale + 0.25);
                    updateTransform();
                });
            }

            if (zoomOut) {
                zoomOut.addEventListener('click', function () {
                    state.scale = Math.max(0.25, state.scale - 0.25);
                    updateTransform();
                });
            }

            if (resetBtn) {
                resetBtn.addEventListener('click', resetImage);
            }

            inner.addEventListener('mousedown', function (e) {
                if (!modal.classList.contains('is-open')) {
                    return;
                }

                state.dragging = true;
                state.startX = e.clientX - state.x;
                state.startY = e.clientY - state.y;
                inner.classList.add('dragging');
                e.preventDefault();
            });

            document.addEventListener('mousemove', function (e) {
                if (!state.dragging) {
                    return;
                }

                state.x = e.clientX - state.startX;
                state.y = e.clientY - state.startY;
                updateTransform();
            });

            document.addEventListener('mouseup', function () {
                state.dragging = false;
                inner.classList.remove('dragging');
            });

            modal.addEventListener('wheel', function (e) {
                e.preventDefault();

                var delta = e.deltaY < 0 ? 0.15 : -0.15;
                state.scale = Math.max(0.25, Math.min(8, state.scale + delta));
                updateTransform();
            }, { passive: false });

            document.addEventListener('keydown', function (e) {
                if (!modal.classList.contains('is-open')) {
                    return;
                }

                if (e.key === 'Escape') {
                    closePreview();
                }
            });
        }

        return {
            init: init
        };
    })();

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', window.DevicePhotoSharedModal.init);
    } else {
        window.DevicePhotoSharedModal.init();
    }
</script>
