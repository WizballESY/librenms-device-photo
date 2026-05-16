<?php

namespace WizballEsy\LibreNmsDevicePhoto\Services;

class PhotoPathService
{
    public function photosDir(): string
    {
        return storage_path(config('device-photo.photos_path', 'app/device-photos'));
    }

    public function thumbsDir(): string
    {
        return $this->photosDir() . '/thumbs';
    }

    public function deletedDir(): string
    {
        return $this->photosDir() . '/deleted';
    }

    public function deletedThumbsDir(): string
    {
        return $this->deletedDir() . '/thumbs';
    }

    public function orderDir(): string
    {
        return storage_path(config('device-photo.order_path', 'app/device-photos-order'));
    }

    public function linksDir(): string
    {
        return storage_path(config('device-photo.links_path', 'app/device-photos-links'));
    }

    public function orderFile(string $safeShortName): string
    {
        return $this->orderDir() . '/' . basename($safeShortName) . '.json';
    }

    public function linksFile(int $deviceId): string
    {
        return $this->linksDir() . '/device-' . $deviceId . '.json';
    }

    public function photoPath(string $filename): string
    {
        return $this->photosDir() . '/' . basename($filename);
    }

    public function thumbPath(string $filename): string
    {
        return $this->thumbsDir() . '/' . basename($filename);
    }

    public function deletedPath(string $filename): string
    {
        return $this->deletedDir() . '/' . basename($filename);
    }

    public function deletedThumbPath(string $filename): string
    {
        return $this->deletedThumbsDir() . '/' . basename($filename);
    }
}