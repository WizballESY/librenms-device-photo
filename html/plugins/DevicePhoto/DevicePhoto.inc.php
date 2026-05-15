<?php

use App\Models\Device;
use App\Models\Plugin;

if (! auth()->user()) {
    http_response_code(403);
    echo 'Forbidden';
    exit;
}

$request = request();

function devicephoto_is_valid_image_filename(string $filename): bool
{
    return (bool) preg_match('/^device-\d+-\d+\.(jpg|jpeg|png|webp)$/i', $filename)
        || (bool) preg_match('/^device-\d+-\d+\.deleted-\d{8}-\d{6}\.(jpg|jpeg|png|webp)$/i', $filename);
}

function devicephoto_content_type(string $filename): string
{
    $ext = strtolower((string) pathinfo($filename, PATHINFO_EXTENSION));

    return match ($ext) {
        'jpg', 'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'webp' => 'image/webp',
        default => 'application/octet-stream',
    };
}

function devicephoto_send_image_file(string $baseDir, string $filename): void
{
    $filename = basename($filename);

    if (! devicephoto_is_valid_image_filename($filename)) {
        http_response_code(400);
        echo 'Invalid filename';
        exit;
    }

    $path = rtrim($baseDir, '/') . '/' . $filename;
    $realBase = realpath($baseDir);
    $realPath = realpath($path);

    if (! $realBase || ! $realPath || ! str_starts_with($realPath, $realBase . DIRECTORY_SEPARATOR)) {
        http_response_code(404);
        echo 'Not found';
        exit;
    }

    if (! is_file($realPath) || ! is_readable($realPath)) {
        http_response_code(404);
        echo 'Not found';
        exit;
    }

    header('Content-Type: ' . devicephoto_content_type($filename));
    header('Content-Length: ' . filesize($realPath));
    header('Cache-Control: private, max-age=3600');
    header('X-Content-Type-Options: nosniff');

    readfile($realPath);
    exit;
}

if (strtoupper((string) $request->method()) === 'GET') {
    $imageAction = (string) $request->query('action', '');
    $filename = (string) $request->query('filename', '');

    if ($imageAction === 'photo') {
        devicephoto_send_image_file(devicephoto_photo_dir(), $filename);
    }

    if ($imageAction === 'thumb') {
        devicephoto_send_image_file(devicephoto_thumbs_dir(), $filename);
    }

    if ($imageAction === 'deleted_photo') {
        devicephoto_send_image_file(devicephoto_deleted_dir(), $filename);
    }

    if ($imageAction === 'deleted_thumb') {
        devicephoto_send_image_file(devicephoto_deleted_thumbs_dir(), $filename);
    }
}

function devicephoto_settings(): array
{
    $plugin = Plugin::where('plugin_name', 'DevicePhoto')->first();

    $settings = $plugin->settings ?? [];

    return is_array($settings) ? $settings : [];
}

function devicephoto_allowed_roles(array $settings, string $key): array
{
    $roles = $settings[$key] ?? ['admin'];

    if (! is_array($roles)) {
        $roles = ['admin'];
    }

    $roles = array_values(array_filter($roles, function ($role) {
            return is_string($role) && trim($role) !== '';
        }));

    return empty($roles) ? ['admin'] : $roles;
}

function devicephoto_user_can_action($user, array $settings, string $key): bool
{
    if (! $user) {
        return false;
    }

    if ($user->can('admin')) {
        return true;
    }

    $allowedRoles = devicephoto_allowed_roles($settings, $key);

    if (method_exists($user, 'getRoleNames')) {
        return $user->getRoleNames()->intersect($allowedRoles)->isNotEmpty();
    }

    if (method_exists($user, 'hasRole')) {
        foreach ($allowedRoles as $role) {
            if ($user->hasRole($role)) {
                return true;
            }
        }
    }

    return false;
}



function devicephoto_links_dir(): string
{
    $dir = storage_path('app/device-photos-links');

    if (! is_dir($dir)) {
        mkdir($dir, 02775, true);
    }

    return $dir;
}

function devicephoto_links_file(int $targetDeviceId): string
{
    return devicephoto_links_dir() . '/device-' . $targetDeviceId . '.json';
}

function devicephoto_load_links(int $targetDeviceId): array
{
    $file = devicephoto_links_file($targetDeviceId);

    if (! is_file($file)) {
        return [];
    }

    $data = json_decode((string) file_get_contents($file), true);

    return is_array($data) ? $data : [];
}

function devicephoto_save_links(int $targetDeviceId, array $links): void
{
    $clean = [];

    foreach ($links as $link) {
        if (! is_array($link)) {
            continue;
        }

        $ownerDeviceId = (int) ($link['owner_device_id'] ?? 0);
        $filename = basename((string) ($link['filename'] ?? ''));

        if ($ownerDeviceId < 1 || $filename === '') {
            continue;
        }

        $key = $ownerDeviceId . ':' . $filename;

        $clean[$key] = [
            'owner_device_id' => $ownerDeviceId,
            'filename' => $filename,
        ];
    }

    $file = devicephoto_links_file($targetDeviceId);

    /*
     * Keep the link directory clean.
     * If there are no links left for a target device, remove the JSON file.
     */
    if (empty($clean)) {
        if (is_file($file)) {
            @unlink($file);
        }

        return;
    }

    file_put_contents($file, json_encode(array_values($clean), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
    @chmod($file, 0664);
}

function devicephoto_remove_all_links_for_filename(string $filename): void
{
    foreach (glob(devicephoto_links_dir() . '/device-*.json') ?: [] as $file) {
        $targetDeviceId = (int) preg_replace('/[^0-9]/', '', basename($file, '.json'));

        if ($targetDeviceId < 1) {
            continue;
        }

        $links = devicephoto_load_links($targetDeviceId);

        $links = array_values(array_filter($links, function ($link) use ($filename) {
            return basename((string) ($link['filename'] ?? '')) !== $filename;
        }));

        devicephoto_save_links($targetDeviceId, $links);
    }
}


function devicephoto_photo_dir(): string
{
    return storage_path('app/device-photos');
}

function devicephoto_thumbs_dir(): string
{
    return storage_path('app/device-photos/thumbs');
}

function devicephoto_deleted_dir(): string
{
    return storage_path('app/device-photos/deleted');
}

function devicephoto_deleted_thumbs_dir(): string
{
    return storage_path('app/device-photos/deleted/thumbs');
}

function devicephoto_ensure_photo_dirs(): void
{
    foreach ([
        devicephoto_photo_dir(),
        devicephoto_thumbs_dir(),
        devicephoto_deleted_dir(),
        devicephoto_deleted_thumbs_dir(),
    ] as $dir) {
        if (! is_dir($dir)) {
            @mkdir($dir, 02775, true);
        }
    }
}

function devicephoto_ensure_thumbs_dir(): void
{
    devicephoto_ensure_photo_dirs();
}

function devicephoto_move_thumbnail_to_deleted(string $filename, string $deletedName): void
{
    $thumbPath = devicephoto_thumbs_dir() . '/' . basename($filename);

    if (! is_file($thumbPath)) {
        return;
    }

    $deletedThumbsDir = devicephoto_deleted_thumbs_dir();

    if (! is_dir($deletedThumbsDir)) {
        @mkdir($deletedThumbsDir, 02775, true);
    }

    if (@rename($thumbPath, $deletedThumbsDir . '/' . basename($deletedName))) {
        @chmod($deletedThumbsDir . '/' . basename($deletedName), 0664);
    }
}


function devicephoto_find_binary(string $binary): ?string
{
    $paths = [
        '/usr/bin/' . $binary,
        '/usr/local/bin/' . $binary,
        '/bin/' . $binary,
    ];

    foreach ($paths as $path) {
        if (is_executable($path)) {
            return $path;
        }
    }

    $found = trim((string) @shell_exec('command -v ' . escapeshellarg($binary) . ' 2>/dev/null'));

    return $found !== '' && is_executable($found) ? $found : null;
}

function devicephoto_heic_conversion_available(): bool
{
    $magick = devicephoto_find_binary('magick');

    if (! $magick) {
        return false;
    }

    $output = (string) @shell_exec(escapeshellcmd($magick) . ' -list format 2>/dev/null');

    if ($output === '') {
        return false;
    }

    foreach (preg_split('/\R/', $output) ?: [] as $line) {
        if (preg_match('/^\s*(HEIC|HEIF)\s+HEIC\s+.*r/i', $line)) {
            return true;
        }
    }

    return false;
}

function devicephoto_convert_heic_to_jpeg(string $sourcePath, string $targetPath): bool
{
    if (! is_file($sourcePath)) {
        return false;
    }

    $magick = devicephoto_find_binary('magick');

    if (! $magick || ! devicephoto_heic_conversion_available()) {
        return false;
    }

    $cmd = escapeshellcmd($magick)
        . ' '
        . escapeshellarg($sourcePath . '[0]')
        . ' '
        . escapeshellarg('jpg:' . $targetPath)
        . ' 2>&1';

    @exec($cmd, $output, $exitCode);

    return $exitCode === 0 && is_file($targetPath) && filesize($targetPath) > 0;
}


function devicephoto_cleanup_stale_thumbnails(string $photoDir): int
{
    $thumbDir = devicephoto_thumbs_dir();
    $removed = 0;

    if (! is_dir($thumbDir) || ! is_dir($photoDir)) {
        return 0;
    }

    foreach (glob($thumbDir . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE) ?: [] as $thumbPath) {
        $filename = basename($thumbPath);

        /*
         * A thumbnail is stale when the matching original active photo no longer exists.
         */
        if (! is_file($photoDir . '/' . $filename)) {
            if (@unlink($thumbPath)) {
                $removed++;
            }
        }
    }

    return $removed;
}


function devicephoto_create_thumbnail(string $sourcePath, string $filename, int $maxWidth = 500, int $maxHeight = 500): bool
{
    /*
     * Thumbnails are optional. If GD is not installed, do not fail upload/delete/assign.
     */
    if (! extension_loaded('gd')) {
        return false;
    }

    if (! is_file($sourcePath)) {
        return false;
    }

    $info = @getimagesize($sourcePath);

    if (! is_array($info) || empty($info[0]) || empty($info[1])) {
        return false;
    }

    $srcWidth = (int) $info[0];
    $srcHeight = (int) $info[1];
    $mime = (string) ($info['mime'] ?? '');

    if ($srcWidth < 1 || $srcHeight < 1) {
        return false;
    }

    $scale = min($maxWidth / $srcWidth, $maxHeight / $srcHeight, 1);
    $dstWidth = max(1, (int) floor($srcWidth * $scale));
    $dstHeight = max(1, (int) floor($srcHeight * $scale));

    switch ($mime) {
        case 'image/jpeg':
            if (! function_exists('imagecreatefromjpeg')) {
                return false;
            }
            $srcImage = @imagecreatefromjpeg($sourcePath);
            break;

        case 'image/png':
            if (! function_exists('imagecreatefrompng')) {
                return false;
            }
            $srcImage = @imagecreatefrompng($sourcePath);
            break;

        case 'image/webp':
            if (! function_exists('imagecreatefromwebp')) {
                return false;
            }
            $srcImage = @imagecreatefromwebp($sourcePath);
            break;

        default:
            return false;
    }

    if (! $srcImage) {
        return false;
    }

    $dstImage = imagecreatetruecolor($dstWidth, $dstHeight);

    if (! $dstImage) {
        imagedestroy($srcImage);
        return false;
    }

    if ($mime === 'image/png' || $mime === 'image/webp') {
        imagealphablending($dstImage, false);
        imagesavealpha($dstImage, true);
        $transparent = imagecolorallocatealpha($dstImage, 0, 0, 0, 127);
        imagefilledrectangle($dstImage, 0, 0, $dstWidth, $dstHeight, $transparent);
    }

    $ok = imagecopyresampled(
        $dstImage,
        $srcImage,
        0,
        0,
        0,
        0,
        $dstWidth,
        $dstHeight,
        $srcWidth,
        $srcHeight
    );

    if (! $ok) {
        imagedestroy($srcImage);
        imagedestroy($dstImage);
        return false;
    }

    devicephoto_ensure_thumbs_dir();

    $targetPath = devicephoto_thumbs_dir() . '/' . basename($filename);
    $saved = false;

    switch ($mime) {
        case 'image/jpeg':
            $saved = @imagejpeg($dstImage, $targetPath, 82);
            break;

        case 'image/png':
            $saved = @imagepng($dstImage, $targetPath, 6);
            break;

        case 'image/webp':
            if (function_exists('imagewebp')) {
                $saved = @imagewebp($dstImage, $targetPath, 82);
            }
            break;
    }

    imagedestroy($srcImage);
    imagedestroy($dstImage);

    if ($saved) {
        @chmod($targetPath, 0664);
        return true;
    }

    return false;
}


function devicephoto_redirect_after_action($deviceId, $status = null)
{
    $returnTo = (string) request()->input('return_to', '');

    if ($returnTo === 'overview') {
        $query = [];

        if ($status) {
            $query['status'] = $status;
        }

        header('Location: ' . url('plugin/DevicePhoto') . (empty($query) ? '' : '?' . http_build_query($query)));
        exit;
    }

    devicephoto_redirect($deviceId, $status);
}

function devicephoto_redirect($deviceId, $status = null)
{
    $url = url('plugin/DevicePhoto') . '?device_id=' . (int) $deviceId;

    if ($status) {
        $url .= '&status=' . rawurlencode($status);
    }

    header('Location: ' . $url);
    exit;
}

function devicephoto_safe_short_name($device): string
{
    /*
     * Use LibreNMS device_id as stable photo key.
     * This survives hostname/display/sysName changes.
     */
    return 'device-' . (int) $device->device_id;
}

function devicephoto_order_dir(): string
{
    $dir = storage_path('app/device-photos-order');

    if (! is_dir($dir)) {
        mkdir($dir, 02775, true);
    }

    return $dir;
}

function devicephoto_order_file(string $safeShortName): string
{
    return devicephoto_order_dir() . '/' . $safeShortName . '.json';
}

function devicephoto_load_order(string $safeShortName): array
{
    $file = devicephoto_order_file($safeShortName);

    if (! is_file($file)) {
        return [];
    }

    $data = json_decode((string) file_get_contents($file), true);

    return is_array($data) ? array_values(array_filter($data, 'is_string')) : [];
}

function devicephoto_save_order(string $safeShortName, array $order): void
{
    $file = devicephoto_order_file($safeShortName);

    $order = array_values(array_unique(array_filter($order, 'is_string')));

    file_put_contents($file, json_encode($order, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    @chmod($file, 0664);
}

function devicephoto_list_photos(string $photoDir, string $safeShortName): array
{
    $photos = [];

    foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
        foreach (glob($photoDir . '/' . $safeShortName . '-*.' . $ext) ?: [] as $path) {
            $filename = basename($path);

            if (is_file($path)) {
                $photos[$filename] = $filename;
            }
        }
    }

    ksort($photos, SORT_NATURAL | SORT_FLAG_CASE);

    $order = devicephoto_load_order($safeShortName);
    $ordered = [];

    foreach ($order as $filename) {
        if (isset($photos[$filename])) {
            $ordered[$filename] = $filename;
        }
    }

    foreach ($photos as $filename) {
        if (! isset($ordered[$filename])) {
            $ordered[$filename] = $filename;
        }
    }

    return array_values($ordered);
}

$deviceId = (int) $request->input('device_id', 0);
$device = $deviceId > 0 ? Device::find($deviceId) : null;
$action = (string) $request->input('action', '');

/*
 * Some actions are global and do not belong to one existing device.
 * Example: deleting orphaned photos from the global overview page.
 */
$globalActions = [
    'delete_orphan_photo',
    'assign_orphan_photo',
    'generate_missing_thumbnails',
    'clean_stale_thumbnails',
    'remove_broken_link',
];

if (! $device && ! in_array($action, $globalActions, true)) {
    devicephoto_redirect(0, 'device_not_found');
}

$photoDir = devicephoto_photo_dir();
$deletedDir = devicephoto_deleted_dir();

if (! is_dir($photoDir)) {
    mkdir($photoDir, 02775, true);
}

if (! is_dir($deletedDir)) {
    mkdir($deletedDir, 02775, true);
}

$safeShortName = $device ? devicephoto_safe_short_name($device) : '';

$settings = devicephoto_settings();
$user = auth()->user();

if ($action === 'upload' && ! devicephoto_user_can_action($user, $settings, 'upload_roles')) {
    devicephoto_redirect($deviceId, 'permission_denied');
}

if ($action === 'delete' && ! devicephoto_user_can_action($user, $settings, 'delete_roles')) {
    devicephoto_redirect($deviceId, 'permission_denied');
}

if ($action === 'save_order' && ! devicephoto_user_can_action($user, $settings, 'reorder_roles')) {
    devicephoto_redirect($deviceId, 'permission_denied');
}

if ($action === 'remove_outgoing_link' && ! devicephoto_user_can_action($user, $settings, 'delete_roles')) {
    devicephoto_redirect($deviceId, 'permission_denied');
}

if ($action === 'add_incoming_link' && ! devicephoto_user_can_action($user, $settings, 'upload_roles')) {
    devicephoto_redirect($deviceId, 'permission_denied');
}

if ($action === 'delete_orphan_photo' && ! devicephoto_user_can_action($user, $settings, 'delete_roles')) {
    devicephoto_redirect(0, 'permission_denied');
}

if ($action === 'remove_broken_link' && ! devicephoto_user_can_action($user, $settings, 'delete_roles')) {
    devicephoto_redirect(0, 'permission_denied');
}

if ($action === 'assign_orphan_photo' && ! devicephoto_user_can_action($user, $settings, 'upload_roles')) {
    devicephoto_redirect(0, 'permission_denied');
}

if ($action === 'generate_missing_thumbnails' && ! devicephoto_user_can_action($user, $settings, 'upload_roles')) {
    devicephoto_redirect(0, 'permission_denied');
}

if ($action === 'clean_stale_thumbnails' && ! devicephoto_user_can_action($user, $settings, 'upload_roles')) {
    devicephoto_redirect(0, 'permission_denied');
}

if ($action === 'remove_broken_link') {
    /*
     * Remove a broken linked-photo entry from the target device JSON.
     * This only removes the link entry, not any photo file.
     */
    $targetDeviceId = (int) $request->input('target_device_id', 0);
    $ownerDeviceId = (int) $request->input('owner_device_id', 0);
    $filename = basename((string) $request->input('filename', ''));

    if ($targetDeviceId < 1 || $ownerDeviceId < 1 || $filename === '') {
        devicephoto_redirect(0, 'invalid_link');
    }

    if (! preg_match('/^device-\d+-\d+\.(jpg|jpeg|png|webp)$/i', $filename)) {
        devicephoto_redirect(0, 'invalid_filename');
    }

    $links = devicephoto_load_links($targetDeviceId);
    $newLinks = [];
    $removed = false;

    foreach ($links as $link) {
        if (! is_array($link)) {
            continue;
        }

        $linkOwnerDeviceId = (int) ($link['owner_device_id'] ?? 0);
        $linkFilename = basename((string) ($link['filename'] ?? ''));

        if ($linkOwnerDeviceId === $ownerDeviceId && $linkFilename === $filename) {
            $removed = true;
            continue;
        }

        $newLinks[] = $link;
    }

    if (! $removed) {
        devicephoto_redirect(0, 'not_found');
    }

    devicephoto_save_links($targetDeviceId, $newLinks);
    devicephoto_redirect_after_action(0, 'link_removed');
}

if ($action === 'clean_stale_thumbnails') {
    devicephoto_cleanup_stale_thumbnails($photoDir);
    devicephoto_redirect(0, 'thumbnails_cleaned');
}

if ($action === 'generate_missing_thumbnails') {
    /*
     * Generate thumbnails for active photos that are missing thumbnails.
     * Fail-safe: if GD is missing, redirect without breaking anything.
     */
    if (! extension_loaded('gd')) {
        devicephoto_redirect(0, 'thumbnail_gd_missing');
    }

    devicephoto_ensure_thumbs_dir();

    $generated = 0;
    $failed = 0;

    foreach (glob($photoDir . '/device-*.*') ?: [] as $sourcePath) {
        if (! is_file($sourcePath)) {
            continue;
        }

        $filename = basename($sourcePath);

        if (! preg_match('/^device-\d+-\d+\.(jpg|jpeg|png|webp)$/i', $filename)) {
            continue;
        }

        $thumbPath = devicephoto_thumbs_dir() . '/' . $filename;

        if (is_file($thumbPath)) {
            continue;
        }

        if (devicephoto_create_thumbnail($sourcePath, $filename)) {
            $generated++;
        } else {
            $failed++;
        }
    }

    if ($generated > 0 && $failed > 0) {
        devicephoto_redirect(0, 'thumbnails_partial');
    }

    if ($generated > 0) {
        devicephoto_cleanup_stale_thumbnails($photoDir);

        devicephoto_redirect(0, 'thumbnails_generated');
    }

    if ($failed > 0) {
        devicephoto_redirect(0, 'thumbnails_failed');
    }

    devicephoto_redirect(0, 'thumbnails_none_missing');
}

if ($action === 'assign_orphan_photo') {
    /*
     * Assign an orphaned photo to an existing device.
     * The file is renamed to the target device ID and next available number.
     * Existing links pointing to the old filename are updated to the new owner/file.
     */
    $filename = basename((string) $request->input('filename', ''));

    /*
     * Accept both:
     *   21
     *   21 - device-name
     *   device-name
     *
     * target_device_id is kept as fallback for older/simple forms.
     */
    $targetInput = trim((string) $request->input('target_device_query', $request->input('target_device_id', '')));
    $targetDevice = null;

    if ($targetInput !== '' && preg_match('/^\s*(\d+)\b/', $targetInput, $targetMatches)) {
        $targetDevice = Device::find((int) $targetMatches[1]);
    }

    if (! $targetDevice && $targetInput !== '') {
        $exactMatches = Device::query()
            ->where('hostname', $targetInput)
            ->orWhere('sysName', $targetInput)
            ->orWhere('display', $targetInput)
            ->limit(2)
            ->get();

        if ($exactMatches->count() === 1) {
            $targetDevice = $exactMatches->first();
        }
    }

    if (! $targetDevice && $targetInput !== '') {
        $likeMatches = Device::query()
            ->where('hostname', 'like', '%' . $targetInput . '%')
            ->orWhere('sysName', 'like', '%' . $targetInput . '%')
            ->orWhere('display', 'like', '%' . $targetInput . '%')
            ->limit(2)
            ->get();

        if ($likeMatches->count() === 1) {
            $targetDevice = $likeMatches->first();
        }
    }

    $targetDeviceId = $targetDevice ? (int) $targetDevice->device_id : 0;

    if (! preg_match('/^device-\d+-\d+\.(jpg|jpeg|png|webp)$/i', $filename)) {
        devicephoto_redirect(0, 'invalid_filename');
    }

    if (! is_file($photoDir . '/' . $filename)) {
        devicephoto_redirect(0, 'not_found');
    }

    if (! preg_match('/^device-(\d+)-\d+\.(jpg|jpeg|png|webp)$/i', $filename, $matches)) {
        devicephoto_redirect(0, 'invalid_filename');
    }

    $oldDeviceId = (int) $matches[1];

    /*
     * Only allow assigning photos whose original owner device no longer exists.
     */
    if ($oldDeviceId > 0 && Device::find($oldDeviceId)) {
        devicephoto_redirect(0, 'not_orphaned');
    }

    if (! $targetDevice) {
        devicephoto_redirect(0, 'invalid_target_device');
    }

    $pathInfo = pathinfo($filename);
    $ext = strtolower((string) ($pathInfo['extension'] ?? ''));

    if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
        devicephoto_redirect(0, 'invalid_type');
    }

    $targetPrefix = 'device-' . $targetDeviceId;
    $nextNumber = 1;

    foreach (glob($photoDir . '/' . $targetPrefix . '-*.*') ?: [] as $existingPath) {
        $existingName = basename($existingPath);

        if (preg_match('/^' . preg_quote($targetPrefix, '/') . '-(\d+)\.(jpg|jpeg|png|webp)$/i', $existingName, $numberMatches)) {
            $nextNumber = max($nextNumber, ((int) $numberMatches[1]) + 1);
        }
    }

    $targetName = $targetPrefix . '-' . $nextNumber . '.' . $ext;

    while (is_file($photoDir . '/' . $targetName)) {
        $nextNumber++;
        $targetName = $targetPrefix . '-' . $nextNumber . '.' . $ext;
    }

    if (! @rename($photoDir . '/' . $filename, $photoDir . '/' . $targetName)) {
        devicephoto_redirect(0, 'assign_failed');
    }

    @chmod($photoDir . '/' . $targetName, 0664);

    /*
     * Keep the orphaned thumbnail with the photo when assigning it to a device.
     * If no orphaned thumbnail exists, thumbnail generation below will create one.
     */
    $oldThumbPath = devicephoto_thumbs_dir() . '/' . $filename;
    $newThumbPath = devicephoto_thumbs_dir() . '/' . $targetName;

    if (is_file($oldThumbPath)) {
        @rename($oldThumbPath, $newThumbPath);
        @chmod($newThumbPath, 0664);
    }

    /*
     * Create or refresh thumbnail for assigned orphaned photo if possible.
     */
    devicephoto_create_thumbnail($photoDir . '/' . $targetName, $targetName);

    /*
     * Update existing linked-photo JSON entries that pointed to the old orphaned filename.
     */
    foreach (glob(devicephoto_links_dir() . '/device-*.json') ?: [] as $linkFile) {
        $jsonTargetDeviceId = (int) preg_replace('/[^0-9]/', '', basename($linkFile, '.json'));

        if ($jsonTargetDeviceId < 1) {
            continue;
        }

        $links = devicephoto_load_links($jsonTargetDeviceId);
        $changed = false;

        foreach ($links as $index => $link) {
            if (! is_array($link)) {
                continue;
            }

            if (basename((string) ($link['filename'] ?? '')) === $filename) {
                $links[$index]['owner_device_id'] = $targetDeviceId;
                $links[$index]['filename'] = $targetName;
                $changed = true;
            }
        }

        if ($changed) {
            devicephoto_save_links($jsonTargetDeviceId, $links);
        }
    }

    devicephoto_redirect(0, 'assigned');
}

if ($action === 'delete_orphan_photo') {
    /*
     * Move an orphaned photo to deleted folder.
     * This does not permanently delete the file.
     */
    $filename = basename((string) $request->input('filename', ''));

    if (! preg_match('/^device-\d+-\d+\.(jpg|jpeg|png|webp)$/i', $filename)) {
        devicephoto_redirect(0, 'invalid_filename');
    }

    if (! is_file($photoDir . '/' . $filename)) {
        devicephoto_redirect(0, 'not_found');
    }

    if (! preg_match('/^device-(\d+)-\d+\.(jpg|jpeg|png|webp)$/i', $filename, $matches)) {
        devicephoto_redirect(0, 'invalid_filename');
    }

    $oldDeviceId = (int) $matches[1];

    /*
     * Only allow delete_orphan_photo if the owner device no longer exists.
     */
    if ($oldDeviceId > 0 && Device::find($oldDeviceId)) {
        devicephoto_redirect(0, 'not_orphaned');
    }

    $deletedDir = devicephoto_deleted_dir();

    if (! is_dir($deletedDir)) {
        mkdir($deletedDir, 0775, true);
    }

    $pathInfo = pathinfo($filename);
    $deletedName = $pathInfo['filename'] . '.deleted-' . date('Ymd-His') . '.' . strtolower($pathInfo['extension']);

    if (@rename($photoDir . '/' . $filename, $deletedDir . '/' . $deletedName)) {
        @chmod($deletedDir . '/' . $deletedName, 0664);
        devicephoto_move_thumbnail_to_deleted($filename, $deletedName);
        devicephoto_redirect(0, 'deleted');
    }

    devicephoto_redirect(0, 'delete_failed');
}

if ($action === 'upload') {
    /*
     * Supports both:
     *   photo      = single upload, old field name
     *   photos[]   = multi upload, new field name
     */
    $uploadedFiles = $request->file('photos');

    if (empty($uploadedFiles)) {
        $uploadedFiles = $request->file('photo');
    }

    if (empty($uploadedFiles)) {
        devicephoto_redirect($deviceId, 'no_file');
    }

    if (! is_array($uploadedFiles)) {
        $uploadedFiles = [$uploadedFiles];
    }

    $allowedExt = ['jpg', 'jpeg', 'png', 'webp', 'heic', 'heif'];
    $uploadedCount = 0;

    foreach ($uploadedFiles as $file) {
        if (! $file || ! $file->isValid()) {
            devicephoto_redirect($deviceId, 'upload_failed');
        }

        $ext = strtolower($file->getClientOriginalExtension());

        if (! in_array($ext, $allowedExt, true)) {
            devicephoto_redirect($deviceId, 'invalid_type');
        }

        if ($file->getSize() > 10 * 1024 * 1024) {
            devicephoto_redirect($deviceId, 'too_large');
        }

        $isHeicUpload = in_array($ext, ['heic', 'heif'], true);

        if (! $isHeicUpload && @getimagesize($file->getRealPath()) === false) {
            devicephoto_redirect($deviceId, 'invalid_image');
        }

        if ($isHeicUpload && ! devicephoto_heic_conversion_available()) {
            devicephoto_redirect($deviceId, 'heic_unavailable');
        }

        $targetName = null;

        /*
         * Always use numbered filenames:
         *   device-23-1.jpg
         *   device-23-2.jpg
         *   device-23-3.jpg
         *
         * Do not use:
         *   device-23.jpg
         */
        for ($i = 1; $i <= 999; $i++) {
            /*
             * Numbering is global per device, regardless of file extension.
             *
             * If device-61-1.jpg exists, do not create device-61-1.webp.
             * Next upload should become device-61-2.webp.
             */
            $numberInUse = false;

            foreach ($allowedExt as $checkExt) {
                if (file_exists($photoDir . '/' . $safeShortName . '-' . $i . '.' . $checkExt)) {
                    $numberInUse = true;
                    break;
                }
            }

            if (! $numberInUse) {
                $targetExt = $isHeicUpload ? 'jpg' : $ext;
                $targetName = $safeShortName . '-' . $i . '.' . $targetExt;
                break;
            }
        }

        if (! $targetName) {
            devicephoto_redirect($deviceId, 'no_filename');
        }

        /*
         * If this filename has been used before, remove stale links before storing
         * the new file. Otherwise a newly uploaded photo could inherit old links.
         */
        devicephoto_remove_all_links_for_filename($targetName);

        if ($isHeicUpload) {
            if (! devicephoto_convert_heic_to_jpeg($file->getRealPath(), $photoDir . '/' . $targetName)) {
                devicephoto_redirect($deviceId, 'heic_convert_failed');
            }
        } else {
            $file->move($photoDir, $targetName);
        }

        @chmod($photoDir . '/' . $targetName, 0664);

        /*
         * Thumbnail generation is optional and fail-safe.
         * If GD is missing or thumbnail generation fails, the original image is used as fallback.
         */
        devicephoto_create_thumbnail($photoDir . '/' . $targetName, $targetName);

        $uploadedCount++;
    }

    if ($uploadedCount < 1) {
        devicephoto_redirect($deviceId, 'no_file');
    }

    /*
     * Refresh JSON order after upload.
     * Existing custom order is preserved, new files are appended.
     */
    $order = devicephoto_list_photos($photoDir, $safeShortName);
    devicephoto_save_order($safeShortName, $order);

    devicephoto_redirect($deviceId, 'uploaded');
}

if ($action === 'delete') {
    $filename = basename((string) $request->input('filename', ''));

    $pattern = '/^' . preg_quote($safeShortName, '/') . '(-[A-Za-z0-9._-]+)?\.(jpg|jpeg|png|webp)$/i';

    if (! preg_match($pattern, $filename)) {
        devicephoto_redirect($deviceId, 'invalid_filename');
    }

    if (! is_file($photoDir . '/' . $filename)) {
        devicephoto_redirect($deviceId, 'not_found');
    }

    $timestamp = date('Ymd-His');
    $deletedName = preg_replace('/\.(jpg|jpeg|png|webp)$/i', '.deleted-' . $timestamp . '.$1', $filename);

    if (@rename($photoDir . '/' . $filename, $deletedDir . '/' . $deletedName)) {
        @chmod($deletedDir . '/' . $deletedName, 0664);
        devicephoto_move_thumbnail_to_deleted($filename, $deletedName);

        $order = devicephoto_list_photos($photoDir, $safeShortName);
        $order = array_values(array_filter($order, fn ($item) => $item !== $filename));
        devicephoto_save_order($safeShortName, $order);

        devicephoto_redirect_after_action($deviceId, 'deleted');
    }

    devicephoto_redirect($deviceId, 'delete_failed');
}

if ($action === 'add_link') {
    $filename = basename((string) $request->input('filename', ''));

    /*
     * Accept both:
     *   53
     *   53 - device-name
     *   device-name
     *
     * The GUI sends target_device_query, but target_device_id is kept as fallback
     * for older forms.
     */
    $targetInput = trim((string) $request->input('target_device_query', $request->input('target_device_id', '')));
    $targetDevice = null;

    if ($targetInput !== '' && preg_match('/^\s*(\d+)\b/', $targetInput, $matches)) {
        $targetDevice = Device::find((int) $matches[1]);
    }

    if (! $targetDevice && $targetInput !== '') {
        $exactMatches = Device::query()
            ->where('hostname', $targetInput)
            ->orWhere('sysName', $targetInput)
            ->orWhere('display', $targetInput)
            ->limit(2)
            ->get();

        if ($exactMatches->count() === 1) {
            $targetDevice = $exactMatches->first();
        }
    }

    if (! $targetDevice && $targetInput !== '') {
        $likeMatches = Device::query()
            ->where('hostname', 'like', '%' . $targetInput . '%')
            ->orWhere('sysName', 'like', '%' . $targetInput . '%')
            ->orWhere('display', 'like', '%' . $targetInput . '%')
            ->limit(2)
            ->get();

        if ($likeMatches->count() === 1) {
            $targetDevice = $likeMatches->first();
        }
    }

    $targetDeviceId = $targetDevice ? (int) $targetDevice->device_id : 0;

    $pattern = '/^' . preg_quote($safeShortName, '/') . '-[0-9]{1,3}\.(jpg|jpeg|png|webp)$/i';

    if (! preg_match($pattern, $filename)) {
        devicephoto_redirect($deviceId, 'invalid_filename');
    }

    if (! is_file($photoDir . '/' . $filename)) {
        devicephoto_redirect($deviceId, 'not_found');
    }

    if ($targetDeviceId < 1 || $targetDeviceId === $deviceId) {
        devicephoto_redirect($deviceId, 'invalid_target_device');
    }

    $links = devicephoto_load_links($targetDeviceId);
    $links[] = [
        'owner_device_id' => $deviceId,
        'filename' => $filename,
    ];

    devicephoto_save_links($targetDeviceId, $links);

    devicephoto_redirect_after_action($deviceId, 'link_added');
}

if ($action === 'remove_link') {
    $filename = basename((string) $request->input('filename', ''));
    $ownerDeviceId = (int) $request->input('owner_device_id', 0);

    $links = devicephoto_load_links($deviceId);

    $links = array_values(array_filter($links, function ($link) use ($filename, $ownerDeviceId) {
        return ! (
            (int) ($link['owner_device_id'] ?? 0) === $ownerDeviceId
            && basename((string) ($link['filename'] ?? '')) === $filename
        );
    }));

    devicephoto_save_links($deviceId, $links);

    devicephoto_redirect_after_action($deviceId, 'link_removed');
}

if ($action === 'remove_outgoing_link') {
    /*
     * Remove a link from a target device while staying on the owner device.
     * Current device_id = owner device.
     * target_device_id = device where the linked photo is shown.
     */
    $filename = basename((string) $request->input('filename', ''));
    $targetDeviceId = (int) $request->input('target_device_id', 0);

    $pattern = '/^' . preg_quote($safeShortName, '/') . '-[0-9]{1,3}\.(jpg|jpeg|png|webp)$/i';

    if (! preg_match($pattern, $filename)) {
        devicephoto_redirect($deviceId, 'invalid_filename');
    }

    if ($targetDeviceId < 1 || ! Device::find($targetDeviceId)) {
        devicephoto_redirect($deviceId, 'invalid_target_device');
    }

    $links = devicephoto_load_links($targetDeviceId);

    $links = array_values(array_filter($links, function ($link) use ($filename, $deviceId) {
        return ! (
            (int) ($link['owner_device_id'] ?? 0) === $deviceId
            && basename((string) ($link['filename'] ?? '')) === $filename
        );
    }));

    devicephoto_save_links($targetDeviceId, $links);

    devicephoto_redirect_after_action($deviceId, 'link_removed');
}

if ($action === 'add_incoming_link') {
    /*
     * Add a link to this device from a photo owned by another device.
     * Current device_id = target device.
     * owner_device_id = device that owns the photo.
     */
    $ownerDeviceId = (int) $request->input('owner_device_id', 0);
    $filename = basename((string) $request->input('filename', ''));

    if ($ownerDeviceId < 1 || $ownerDeviceId === $deviceId || ! Device::find($ownerDeviceId)) {
        devicephoto_redirect($deviceId, 'invalid_target_device');
    }

    $ownerKey = 'device-' . $ownerDeviceId;
    $pattern = '/^' . preg_quote($ownerKey, '/') . '-[0-9]{1,3}\.(jpg|jpeg|png|webp)$/i';

    if (! preg_match($pattern, $filename)) {
        devicephoto_redirect($deviceId, 'invalid_filename');
    }

    if (! is_file($photoDir . '/' . $filename)) {
        devicephoto_redirect($deviceId, 'not_found');
    }

    $links = devicephoto_load_links($deviceId);
    $links[] = [
        'owner_device_id' => $ownerDeviceId,
        'filename' => $filename,
    ];

    devicephoto_save_links($deviceId, $links);

    /*
     * Keep the owner-device search result visible after linking a photo,
     * so multiple photos can be linked without searching again.
     */
    $ownerDeviceQuery = trim((string) $request->input('owner_device_query', ''));

    $query = [
        'device_id' => $deviceId,
        'status' => 'link_added',
    ];

    if ($ownerDeviceQuery !== '') {
        $query['owner_device_query'] = $ownerDeviceQuery;
    } else {
        $query['owner_device_query'] = (string) $ownerDeviceId;
    }

    header('Location: ' . url('plugin/DevicePhoto') . '?' . http_build_query($query) . '#device-photo-incoming-link');
    exit;
}

if ($action === 'save_order') {
    $orderJson = (string) $request->input('order_json', '[]');
    $decoded = json_decode($orderJson, true);

    if (! is_array($decoded)) {
        devicephoto_redirect($deviceId, 'invalid_order');
    }

    $existing = devicephoto_list_photos($photoDir, $safeShortName);
    $existingLookup = array_flip($existing);

    $newOrder = [];

    foreach ($decoded as $filename) {
        $filename = basename((string) $filename);

        if (isset($existingLookup[$filename])) {
            $newOrder[] = $filename;
        }
    }

    foreach ($existing as $filename) {
        if (! in_array($filename, $newOrder, true)) {
            $newOrder[] = $filename;
        }
    }

    devicephoto_save_order($safeShortName, $newOrder);

    devicephoto_redirect($deviceId, 'order_updated');
}

devicephoto_redirect($deviceId, 'unknown_action');
