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

    public function exists(int $targetDeviceId, int $ownerDeviceId, string $filename): bool
    {
        $filename = basename($filename);

        foreach ($this->load($targetDeviceId) as $link) {
            if (
                (int) ($link['owner_device_id'] ?? 0) === $ownerDeviceId
                && basename((string) ($link['filename'] ?? '')) === $filename
            ) {
                return true;
            }
        }

        return false;
    }

    public function add(int $targetDeviceId, int $ownerDeviceId, string $filename): bool
    {
        $filename = basename($filename);

        if ($targetDeviceId < 1 || $ownerDeviceId < 1 || $filename === '') {
            return false;
        }

        return $this->json->mutateArrayWithLock(
            $this->paths->linksFile($targetDeviceId),
            function (array $links) use ($ownerDeviceId, $filename): array {
                $cleanLinks = [];
                $exists = false;

                foreach ($links as $link) {
                    if (! is_array($link)) {
                        continue;
                    }

                    $existingOwnerDeviceId = (int) ($link['owner_device_id'] ?? 0);
                    $existingFilename = basename((string) ($link['filename'] ?? ''));

                    if ($existingOwnerDeviceId < 1 || $existingFilename === '') {
                        continue;
                    }

                    if ($existingOwnerDeviceId === $ownerDeviceId && $existingFilename === $filename) {
                        $exists = true;
                    }

                    $cleanLinks[] = [
                        'owner_device_id' => $existingOwnerDeviceId,
                        'filename' => $existingFilename,
                    ];
                }

                if (! $exists) {
                    $cleanLinks[] = [
                        'owner_device_id' => $ownerDeviceId,
                        'filename' => $filename,
                    ];
                }

                return $cleanLinks;
            },
            true
        );
    }

    public function remove(int $targetDeviceId, int $ownerDeviceId, string $filename): bool
    {
        $filename = basename($filename);

        if ($targetDeviceId < 1 || $ownerDeviceId < 1 || $filename === '') {
            return false;
        }

        return $this->json->mutateArrayWithLock(
            $this->paths->linksFile($targetDeviceId),
            function (array $links) use ($ownerDeviceId, $filename): array {
                $cleanLinks = [];

                foreach ($links as $link) {
                    if (! is_array($link)) {
                        continue;
                    }

                    $existingOwnerDeviceId = (int) ($link['owner_device_id'] ?? 0);
                    $existingFilename = basename((string) ($link['filename'] ?? ''));

                    if ($existingOwnerDeviceId < 1 || $existingFilename === '') {
                        continue;
                    }

                    if ($existingOwnerDeviceId === $ownerDeviceId && $existingFilename === $filename) {
                        continue;
                    }

                    $cleanLinks[] = [
                        'owner_device_id' => $existingOwnerDeviceId,
                        'filename' => $existingFilename,
                    ];
                }

                return $cleanLinks;
            },
            true
        );
    }

    public function removeAllForFilename(string $filename): void
    {
        $filename = basename($filename);

        if ($filename === '') {
            return;
        }

        foreach (glob($this->paths->linksDir() . '/device-*.json') ?: [] as $linkFile) {
            $targetDeviceId = (int) preg_replace('/[^0-9]/', '', basename($linkFile, '.json'));

            if ($targetDeviceId < 1) {
                continue;
            }

            $this->json->mutateArrayWithLock(
                $this->paths->linksFile($targetDeviceId),
                function (array $links) use ($filename): array {
                    $cleanLinks = [];

                    foreach ($links as $link) {
                        if (! is_array($link)) {
                            continue;
                        }

                        $ownerDeviceId = (int) ($link['owner_device_id'] ?? 0);
                        $existingFilename = basename((string) ($link['filename'] ?? ''));

                        if ($ownerDeviceId < 1 || $existingFilename === '') {
                            continue;
                        }

                        if ($existingFilename === $filename) {
                            continue;
                        }

                        $cleanLinks[] = [
                            'owner_device_id' => $ownerDeviceId,
                            'filename' => $existingFilename,
                        ];
                    }

                    return $cleanLinks;
                },
                true
            );
        }
    }

    public function updateFilenameReferences(string $oldFilename, int $newOwnerDeviceId, string $newFilename): void
    {
        $oldFilename = basename($oldFilename);
        $newFilename = basename($newFilename);

        if ($oldFilename === '' || $newOwnerDeviceId < 1 || $newFilename === '') {
            return;
        }

        foreach (glob($this->paths->linksDir() . '/device-*.json') ?: [] as $linkFile) {
            $targetDeviceId = (int) preg_replace('/[^0-9]/', '', basename($linkFile, '.json'));

            if ($targetDeviceId < 1) {
                continue;
            }

            $this->json->mutateArrayWithLock(
                $this->paths->linksFile($targetDeviceId),
                function (array $links) use ($oldFilename, $newOwnerDeviceId, $newFilename): array {
                    $cleanLinks = [];

                    foreach ($links as $link) {
                        if (! is_array($link)) {
                            continue;
                        }

                        $ownerDeviceId = (int) ($link['owner_device_id'] ?? 0);
                        $existingFilename = basename((string) ($link['filename'] ?? ''));

                        if ($ownerDeviceId < 1 || $existingFilename === '') {
                            continue;
                        }

                        if ($existingFilename === $oldFilename) {
                            $ownerDeviceId = $newOwnerDeviceId;
                            $existingFilename = $newFilename;
                        }

                        $cleanLinks[] = [
                            'owner_device_id' => $ownerDeviceId,
                            'filename' => $existingFilename,
                        ];
                    }

                    return $cleanLinks;
                },
                true
            );
        }
    }
}
