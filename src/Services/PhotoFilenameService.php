<?php

namespace WizballEsy\LibreNmsDevicePhoto\Services;

class PhotoFilenameService
{
    public function isValidImageFilename(string $filename): bool
    {
        $filename = basename($filename);

        return (bool) preg_match('/^device-\d+-\d+\.(jpg|jpeg|png|webp)$/i', $filename)
            || (bool) preg_match('/^device-\d+-\d+\.deleted-\d{8}-\d{6}(?:-\d{1,3})?\.(jpg|jpeg|png|webp)$/i', $filename);
    }

    public function contentType(string $filename): string
    {
        $ext = strtolower((string) pathinfo($filename, PATHINFO_EXTENSION));

        return match ($ext) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp',
            default => 'application/octet-stream',
        };
    }

    public function safeBasename(string $filename): string
    {
        return basename($filename);
    }

    public function safeDevicePrefix(int $deviceId): string
    {
        return 'device-' . $deviceId;
    }

    public function nextPhotoFilename(string $photoDir, int $deviceId, string $extension): string
    {
        $extension = strtolower(trim($extension, '.'));
        $prefix = $this->safeDevicePrefix($deviceId);

        $i = 1;

        while (is_file($photoDir . '/' . $prefix . '-' . $i . '.' . $extension)) {
            $i++;
        }

        return $prefix . '-' . $i . '.' . $extension;
    }
}