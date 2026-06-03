<?php

namespace WizballEsy\LibreNmsDevicePhoto\Services;

class PhotoImageService
{
    private ?bool $heicConversionAvailable = null;

    public function __construct(
        private readonly PhotoPathService $paths,
    ) {
    }

    public function findBinary(string $binary): ?string
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

    public function heicConversionAvailable(): bool
    {
        if ($this->heicConversionAvailable !== null) {
            return $this->heicConversionAvailable;
        }

        $magick = $this->findBinary('magick');

        if (! $magick) {
            return $this->heicConversionAvailable = false;
        }

        $output = (string) @shell_exec(escapeshellarg($magick) . ' -list format 2>/dev/null');

        if ($output === '') {
            return $this->heicConversionAvailable = false;
        }

        foreach (preg_split('/\R/', $output) ?: [] as $line) {
            if (preg_match('/^\s*(HEIC|HEIF)\s+HEIC\s+.*r/i', $line)) {
                return $this->heicConversionAvailable = true;
            }
        }

        return $this->heicConversionAvailable = false;
    }

    public function convertHeicToJpeg(string $sourcePath, string $targetPath): bool
    {
        if (! is_file($sourcePath)) {
            return false;
        }

        $magick = $this->findBinary('magick');

        if (! $magick || ! $this->heicConversionAvailable()) {
            return false;
        }

        $cmd = escapeshellarg($magick)
            . ' -limit memory 256MiB'
            . ' -limit map 512MiB'
            . ' -limit time 30'
            . ' '
            . escapeshellarg($sourcePath . '[0]')
            . ' '
            . escapeshellarg('jpg:' . $targetPath)
            . ' 2>&1';

        @exec($cmd, $output, $exitCode);

        return $exitCode === 0 && is_file($targetPath) && filesize($targetPath) > 0;
    }

    public function cleanupStaleThumbnails(string $photoDir, ?string $thumbDir = null): int
    {
        $thumbDir ??= $this->paths->thumbsDir();
        $removed = 0;

        if (! is_dir($thumbDir) || ! is_dir($photoDir)) {
            return 0;
        }

        foreach (glob($thumbDir . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE) ?: [] as $thumbPath) {
            $filename = basename($thumbPath);

            if (! is_file($photoDir . '/' . $filename)) {
                if (@unlink($thumbPath)) {
                    $removed++;
                }
            }
        }

        return $removed;
    }

    public function createThumbnail(string $sourcePath, string $filename, ?int $maxWidth = null, ?int $maxHeight = null): bool
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

        $maxWidth = max(1, (int) ($maxWidth ?? config('device-photo.thumbnail_max_width', 360)));
        $maxHeight = max(1, (int) ($maxHeight ?? config('device-photo.thumbnail_max_height', 360)));

        $info = @getimagesize($sourcePath);

        if (! is_array($info) || empty($info[0]) || empty($info[1])) {
            return false;
        }

        $srcWidth = (int) $info[0];
        $srcHeight = (int) $info[1];
        $mime = (string) ($info['mime'] ?? '');

        if ($srcWidth < 1 || $srcHeight < 1 || ($srcWidth * $srcHeight) > 40000000) {
            return false;
        }

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

        /*
         * Respect EXIF orientation for JPEG thumbnails.
         */
        if ($mime === 'image/jpeg' && function_exists('exif_read_data')) {
            $exif = @exif_read_data($sourcePath);

            if (is_array($exif)) {
                $orientation = (int) ($exif['Orientation'] ?? 1);

                if ($orientation === 3) {
                    $rotated = @imagerotate($srcImage, 180, 0);

                    if ($rotated) {
                        imagedestroy($srcImage);
                        $srcImage = $rotated;
                    }
                } elseif ($orientation === 6) {
                    $rotated = @imagerotate($srcImage, -90, 0);

                    if ($rotated) {
                        imagedestroy($srcImage);
                        $srcImage = $rotated;
                    }
                } elseif ($orientation === 8) {
                    $rotated = @imagerotate($srcImage, 90, 0);

                    if ($rotated) {
                        imagedestroy($srcImage);
                        $srcImage = $rotated;
                    }
                }
            }

            $srcWidth = imagesx($srcImage);
            $srcHeight = imagesy($srcImage);
        }

        $scale = min($maxWidth / $srcWidth, $maxHeight / $srcHeight, 1);
        $dstWidth = max(1, (int) floor($srcWidth * $scale));
        $dstHeight = max(1, (int) floor($srcHeight * $scale));

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

        if (! is_dir($this->paths->thumbsDir())) {
            @mkdir($this->paths->thumbsDir(), 02775, true);
        }

        $targetPath = $this->paths->thumbPath($filename);
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
}