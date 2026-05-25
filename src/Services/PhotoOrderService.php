<?php

namespace WizballEsy\LibreNmsDevicePhoto\Services;

class PhotoOrderService
{
    public function __construct(
        private readonly PhotoPathService $paths,
        private readonly JsonFileService $json,
    ) {
    }

    public function load(string $safeShortName): array
    {
        $order = $this->json->readArray($this->paths->orderFile($safeShortName));

        return array_values(array_filter($order, 'is_string'));
    }

    public function save(string $safeShortName, array $order): bool
    {
        $order = array_values(array_unique(array_filter($order, 'is_string')));

        return $this->json->mutateArrayWithLock(
            $this->paths->orderFile($safeShortName),
            fn (array $_currentOrder): array => $order
        );
    }

    public function apply(array $photos, string $safeShortName): array
    {
        $photosByName = [];

        foreach ($photos as $photo) {
            if (is_string($photo)) {
                $photosByName[$photo] = $photo;
                continue;
            }

            if (is_array($photo) && isset($photo['filename']) && is_string($photo['filename'])) {
                $photosByName[$photo['filename']] = $photo;
            }
        }

        ksort($photosByName, SORT_NATURAL | SORT_FLAG_CASE);

        $order = $this->load($safeShortName);
        $ordered = [];

        foreach ($order as $filename) {
            if (isset($photosByName[$filename])) {
                $ordered[$filename] = $photosByName[$filename];
            }
        }

        foreach ($photosByName as $filename => $photo) {
            if (! isset($ordered[$filename])) {
                $ordered[$filename] = $photo;
            }
        }

        return array_values($ordered);
    }

    public function remove(string $safeShortName, string $filename): bool
    {
        $filename = trim($filename);

        if ($safeShortName === '' || $filename === '') {
            return false;
        }

        return $this->json->mutateArrayWithLock(
            $this->paths->orderFile($safeShortName),
            function (array $order) use ($filename): array {
                $cleaned = [];

                foreach ($order as $item) {
                    if (! is_string($item)) {
                        continue;
                    }

                    if ($item === $filename) {
                        continue;
                    }

                    if (! in_array($item, $cleaned, true)) {
                        $cleaned[] = $item;
                    }
                }

                return $cleaned;
            }
        );
    }

    public function appendOwnedPhoto(string $safeShortName, string $filename): bool
    {
        $filename = trim($filename);

        if ($safeShortName === '' || $filename === '') {
            return false;
        }

        return $this->json->mutateArrayWithLock(
            $this->paths->orderFile($safeShortName),
            function (array $order) use ($filename): array {
                $cleaned = [];

                foreach ($order as $item) {
                    if (! is_string($item) || $item === $filename) {
                        continue;
                    }

                    if (! in_array($item, $cleaned, true)) {
                        $cleaned[] = $item;
                    }
                }

                $cleaned[] = $filename;

                return $cleaned;
            }
        );
    }

    public function prune(string $safeShortName, array $validOrderKeys): bool
    {
        /*
         * Reconcile the saved order file with the current valid photo keys.
         *
         * This keeps existing order, removes stale keys and appends new valid
         * keys at the end. This is important for mixed owned/linked ordering:
         *
         * - existing custom order should not be reset
         * - removed links/photos should disappear from JSON
         * - new uploads/links should be added to JSON automatically
         */
        $valid = [];
        $validKeys = [];

        foreach ($validOrderKeys as $key) {
            if (is_string($key) && trim($key) !== '') {
                $key = trim($key);

                if (! isset($valid[$key])) {
                    $valid[$key] = true;
                    $validKeys[] = $key;
                }
            }
        }

        return $this->json->mutateArrayWithLock(
            $this->paths->orderFile($safeShortName),
            function (array $order) use ($valid, $validKeys): array {
                $cleaned = [];

                foreach ($order as $item) {
                    if (is_string($item) && isset($valid[$item]) && ! in_array($item, $cleaned, true)) {
                        $cleaned[] = $item;
                    }
                }

                foreach ($validKeys as $item) {
                    if (! in_array($item, $cleaned, true)) {
                        $cleaned[] = $item;
                    }
                }

                return $cleaned;
            }
        );
    }
}
