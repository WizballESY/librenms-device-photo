<?php

namespace WizballEsy\LibreNmsDevicePhoto\Services;

class PhotoLinkService
{
    public function __construct(
        private readonly PhotoPathService $paths,
        private readonly JsonFileService $json,
    ) {
    }

    public function load(int $targetDeviceId): array
    {
        $links = $this->json->readArray($this->paths->linksFile($targetDeviceId));

        return array_values(array_filter($links, function ($link) {
            return is_array($link);
        }));
    }

    public function save(int $targetDeviceId, array $links): bool
    {
        $cleanLinks = [];

        foreach ($links as $link) {
            if (! is_array($link)) {
                continue;
            }

            $ownerDeviceId = (int) ($link['owner_device_id'] ?? 0);
            $filename = basename((string) ($link['filename'] ?? ''));

            if ($ownerDeviceId < 1 || $filename === '') {
                continue;
            }

            $cleanLinks[] = [
                'owner_device_id' => $ownerDeviceId,
                'filename' => $filename,
            ];
        }

        return $this->json->deleteIfEmptyArray($this->paths->linksFile($targetDeviceId), $cleanLinks);
    }

    public function add(int $targetDeviceId, int $ownerDeviceId, string $filename): bool
    {
        $filename = basename($filename);
        $links = $this->load($targetDeviceId);

        foreach ($links as $link) {
            if (
                (int) ($link['owner_device_id'] ?? 0) === $ownerDeviceId
                && basename((string) ($link['filename'] ?? '')) === $filename
            ) {
                return true;
            }
        }

        $links[] = [
            'owner_device_id' => $ownerDeviceId,
            'filename' => $filename,
        ];

        return $this->save($targetDeviceId, $links);
    }

    public function remove(int $targetDeviceId, int $ownerDeviceId, string $filename): bool
    {
        $filename = basename($filename);

        $links = array_values(array_filter($this->load($targetDeviceId), function ($link) use ($ownerDeviceId, $filename) {
            return ! (
                (int) ($link['owner_device_id'] ?? 0) === $ownerDeviceId
                && basename((string) ($link['filename'] ?? '')) === $filename
            );
        }));

        return $this->save($targetDeviceId, $links);
    }

    public function removeAllForFilename(string $filename): void
    {
        $filename = basename($filename);

        foreach (glob($this->paths->linksDir() . '/device-*.json') ?: [] as $linkFile) {
            $targetDeviceId = (int) preg_replace('/[^0-9]/', '', basename($linkFile, '.json'));

            if ($targetDeviceId < 1) {
                continue;
            }

            $links = array_values(array_filter($this->load($targetDeviceId), function ($link) use ($filename) {
                return basename((string) ($link['filename'] ?? '')) !== $filename;
            }));

            $this->save($targetDeviceId, $links);
        }
    }

    public function updateFilenameReferences(string $oldFilename, int $newOwnerDeviceId, string $newFilename): void
    {
        $oldFilename = basename($oldFilename);
        $newFilename = basename($newFilename);

        foreach (glob($this->paths->linksDir() . '/device-*.json') ?: [] as $linkFile) {
            $targetDeviceId = (int) preg_replace('/[^0-9]/', '', basename($linkFile, '.json'));

            if ($targetDeviceId < 1) {
                continue;
            }

            $links = $this->load($targetDeviceId);
            $changed = false;

            foreach ($links as $index => $link) {
                if (basename((string) ($link['filename'] ?? '')) === $oldFilename) {
                    $links[$index]['owner_device_id'] = $newOwnerDeviceId;
                    $links[$index]['filename'] = $newFilename;
                    $changed = true;
                }
            }

            if ($changed) {
                $this->save($targetDeviceId, $links);
            }
        }
    }
}