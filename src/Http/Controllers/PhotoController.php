<?php

namespace WizballEsy\LibreNmsDevicePhoto\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use WizballEsy\LibreNmsDevicePhoto\Services\PhotoFilenameService;
use WizballEsy\LibreNmsDevicePhoto\Services\PhotoPathService;
use WizballEsy\LibreNmsDevicePhoto\Services\PhotoPermissionService;
use WizballEsy\LibreNmsDevicePhoto\Services\PhotoSettingsService;

class PhotoController extends Controller
{
    public function __construct(
        private readonly PhotoPathService $paths,
        private readonly PhotoFilenameService $filenames,
        private readonly PhotoPermissionService $permissions,
        private readonly PhotoSettingsService $settings,
    ) {
    }

    public function show(Request $request)
    {
        $user = auth()->user();

        if (! $user || ! $user->can('global-read')) {
            abort(403, 'Forbidden');
        }

        $action = (string) $request->query('action', '');
        $filename = $this->filenames->safeBasename((string) $request->query('filename', ''));

        $isDeletedAction = ($action === 'deleted_photo' || $action === 'deleted_thumb');

        if ($isDeletedAction) {
            if (! preg_match('/^device-\d+-\d+\.deleted-\d{8}-\d{6}(?:-\d{1,3})?\.(jpg|jpeg|png|webp)$/i', $filename)) {
                abort(400, 'Invalid filename');
            }
        } elseif (! $this->filenames->isValidImageFilename($filename)) {
            abort(400, 'Invalid filename');
        }

        $baseDir = match ($action) {
            'photo' => $this->paths->photosDir(),
            'thumb' => $this->paths->thumbsDir(),
            'deleted_photo' => $this->paths->deletedDir(),
            'deleted_thumb' => $this->paths->deletedThumbsDir(),
            default => null,
        };

        if ($baseDir === null) {
            abort(405, 'Method not allowed');
        }

        if ($action === 'deleted_photo' || $action === 'deleted_thumb') {
            $settings = $this->settings->settings();

            if (! $this->permissions->userCanAction($user, $settings, 'delete_roles')) {
                abort(403, 'Forbidden');
            }
        }

        $path = rtrim($baseDir, '/') . '/' . $filename;
        $realBase = realpath($baseDir);
        $realPath = realpath($path);

        if (! $realBase || ! $realPath || ! str_starts_with($realPath, $realBase . DIRECTORY_SEPARATOR)) {
            abort(404, 'Not found');
        }

        if (! is_file($realPath) || ! is_readable($realPath)) {
            abort(404, 'Not found');
        }

        return response()->file($realPath, [
            'Content-Type' => $this->filenames->contentType($filename),
            'Content-Length' => (string) filesize($realPath),
            'Cache-Control' => 'private, max-age=3600',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}