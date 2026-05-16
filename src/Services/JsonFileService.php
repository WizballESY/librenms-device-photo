<?php

namespace WizballEsy\LibreNmsDevicePhoto\Services;

class JsonFileService
{
    public function readArray(string $path): array
    {
        if (! is_file($path)) {
            return [];
        }

        $json = file_get_contents($path);

        if (! is_string($json) || trim($json) === '') {
            return [];
        }

        $decoded = json_decode($json, true);

        return is_array($decoded) ? $decoded : [];
    }

    public function writeArray(string $path, array $data): bool
    {
        $dir = dirname($path);

        if (! is_dir($dir)) {
            @mkdir($dir, 02775, true);
        }

        return file_put_contents(
            $path,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n",
            LOCK_EX
        ) !== false;
    }

    public function deleteIfEmptyArray(string $path, array $data): bool
    {
        if (! empty($data)) {
            return $this->writeArray($path, $data);
        }

        if (is_file($path)) {
            return @unlink($path);
        }

        return true;
    }
}