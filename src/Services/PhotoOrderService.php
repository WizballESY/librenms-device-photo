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

        return $this->json->writeArray($this->paths->orderFile($safeShortName), $order);
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
        $order = $this->load($safeShortName);

        $order = array_values(array_filter($order, function ($item) use ($filename) {
            return $item !== $filename;
        }));

        return $this->save($safeShortName, $order);
    }
}