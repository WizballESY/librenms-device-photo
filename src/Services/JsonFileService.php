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

    public function mutateArrayWithLock(string $path, callable $callback, bool $deleteIfEmpty = false): bool
    {
        $dir = dirname($path);

        if (! is_dir($dir)) {
            @mkdir($dir, 02775, true);
        }

        if (is_dir($dir)) {
            @chmod($dir, 02775);
        }

        $handle = @fopen($path, 'c+');

        if (! $handle) {
            return false;
        }

        try {
            if (! flock($handle, LOCK_EX)) {
                fclose($handle);

                return false;
            }

            rewind($handle);

            $json = stream_get_contents($handle);
            $decoded = [];

            if (is_string($json) && trim($json) !== '') {
                $maybeDecoded = json_decode($json, true);
                $decoded = is_array($maybeDecoded) ? $maybeDecoded : [];
            }

            $mutated = $callback($decoded);

            if (! is_array($mutated)) {
                $mutated = [];
            }

            rewind($handle);

            if ($deleteIfEmpty && empty($mutated)) {
                if (! ftruncate($handle, 0)) {
                    flock($handle, LOCK_UN);
                    fclose($handle);

                    return false;
                }

                fflush($handle);
                flock($handle, LOCK_UN);
                fclose($handle);

                return is_file($path) ? @unlink($path) : true;
            }

            $encoded = json_encode($mutated, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

            if (! is_string($encoded)) {
                flock($handle, LOCK_UN);
                fclose($handle);

                return false;
            }

            if (! ftruncate($handle, 0)) {
                flock($handle, LOCK_UN);
                fclose($handle);

                return false;
            }

            rewind($handle);

            if (fwrite($handle, $encoded . "\n") === false) {
                flock($handle, LOCK_UN);
                fclose($handle);

                return false;
            }

            fflush($handle);
            @chmod($path, 0664);

            flock($handle, LOCK_UN);
            fclose($handle);

            return true;
        } catch (\Throwable $e) {
            @flock($handle, LOCK_UN);
            @fclose($handle);

            return false;
        }
    }

}
