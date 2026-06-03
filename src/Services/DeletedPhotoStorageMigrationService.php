<?php

namespace WizballEsy\LibreNmsDevicePhoto\Services;

class DeletedPhotoStorageMigrationService
{
    public function __construct(
        private readonly PhotoPathService $paths,
    ) {
    }

    public function legacyStatus(): array
    {
        $legacyPhotoCount = $this->countFiles($this->paths->legacyDeletedDir());
        $legacyThumbnailCount = $this->countFiles($this->paths->legacyDeletedThumbsDir());

        return [
            'legacy_deleted_photo_count' => $legacyPhotoCount,
            'legacy_deleted_thumbnail_count' => $legacyThumbnailCount,
            'legacy_deleted_storage_detected' => ($legacyPhotoCount + $legacyThumbnailCount) > 0,
            'legacy_deleted_dir' => $this->paths->legacyDeletedDir(),
            'legacy_deleted_thumbs_dir' => $this->paths->legacyDeletedThumbsDir(),
            'deleted_dir' => $this->paths->deletedDir(),
            'deleted_thumbs_dir' => $this->paths->deletedThumbsDir(),
        ];
    }

    public function migrate(): array
    {
        $result = [
            'moved_photos' => 0,
            'moved_thumbnails' => 0,
            'skipped_existing' => 0,
            'skipped_unexpected' => 0,
            'failed' => 0,
            'removed_legacy_dirs' => [],
            'legacy_deleted_photo_count_before' => $this->countFiles($this->paths->legacyDeletedDir()),
            'legacy_deleted_thumbnail_count_before' => $this->countFiles($this->paths->legacyDeletedThumbsDir()),
        ];

        $this->ensureDirectory($this->paths->deletedDir());
        $this->ensureDirectory($this->paths->deletedThumbsDir());

        $photoResult = $this->moveFiles(
            $this->paths->legacyDeletedDir(),
            $this->paths->deletedDir()
        );

        $thumbResult = $this->moveFiles(
            $this->paths->legacyDeletedThumbsDir(),
            $this->paths->deletedThumbsDir()
        );

        $result['moved_photos'] = $photoResult['moved'];
        $result['moved_thumbnails'] = $thumbResult['moved'];
        $result['skipped_existing'] = $photoResult['skipped_existing'] + $thumbResult['skipped_existing'];
        $result['skipped_unexpected'] = $photoResult['skipped_unexpected'] + $thumbResult['skipped_unexpected'];
        $result['failed'] = $photoResult['failed'] + $thumbResult['failed'];

        /*
         * Remove old legacy directories only when they are empty.
         * Thumbs must be removed before the parent deleted directory.
         */
        foreach ([
            $this->paths->legacyDeletedThumbsDir(),
            $this->paths->legacyDeletedDir(),
        ] as $dir) {
            if ($this->removeDirectoryIfEmpty($dir)) {
                $result['removed_legacy_dirs'][] = $dir;
            }
        }

        $result['legacy_deleted_photo_count_after'] = $this->countFiles($this->paths->legacyDeletedDir());
        $result['legacy_deleted_thumbnail_count_after'] = $this->countFiles($this->paths->legacyDeletedThumbsDir());
        $result['legacy_deleted_storage_detected_after'] =
            ($result['legacy_deleted_photo_count_after'] + $result['legacy_deleted_thumbnail_count_after']) > 0;

        return $result;
    }

    private function moveFiles(string $sourceDir, string $targetDir): array
    {
        $result = [
            'moved' => 0,
            'skipped_existing' => 0,
            'skipped_unexpected' => 0,
            'failed' => 0,
        ];

        if (! is_dir($sourceDir)) {
            return $result;
        }

        $this->ensureDirectory($targetDir);

        foreach (glob($sourceDir . '/*') ?: [] as $sourcePath) {
            if (! is_file($sourcePath)) {
                continue;
            }

            $filename = basename($sourcePath);

            if (! $this->isExpectedDeletedFilename($filename)) {
                $result['skipped_unexpected']++;
                continue;
            }

            $targetPath = $targetDir . '/' . $filename;

            if (is_file($targetPath)) {
                $result['skipped_existing']++;
                continue;
            }

            if ($this->moveFileWithoutOverwrite($sourcePath, $targetPath)) {
                @chmod($targetPath, 0664);
                $result['moved']++;
                continue;
            }

            $result['failed']++;
        }

        return $result;
    }

    private function moveFileWithoutOverwrite(string $sourcePath, string $targetPath): bool
    {
        if (! is_file($sourcePath) || $targetPath === '' || is_file($targetPath)) {
            return false;
        }

        /*
         * Use link()+unlink() instead of rename() so an unexpected target file
         * can never be overwritten. link() fails if the target already exists.
         */
        if (! @link($sourcePath, $targetPath)) {
            return false;
        }

        if (! is_file($targetPath)) {
            return false;
        }

        if (! @unlink($sourcePath)) {
            @unlink($targetPath);
            return false;
        }

        return true;
    }

    private function isExpectedDeletedFilename(string $filename): bool
    {
        return (bool) preg_match('/^device-\d+-\d+\.deleted-\d{8}-\d{6}(?:-\d{1,3})?\.(jpg|jpeg|png|webp)$/i', $filename);
    }

    private function countFiles(string $dir): int
    {
        if (! is_dir($dir)) {
            return 0;
        }

        $count = 0;

        foreach (glob($dir . '/*') ?: [] as $path) {
            if (is_file($path)) {
                $count++;
            }
        }

        return $count;
    }

    private function ensureDirectory(string $dir): void
    {
        if (! is_dir($dir)) {
            @mkdir($dir, 02775, true);
        }

        if (is_dir($dir)) {
            @chmod($dir, 02775);
        }
    }

    private function removeDirectoryIfEmpty(string $dir): bool
    {
        if (! is_dir($dir)) {
            return false;
        }

        $items = array_diff(scandir($dir) ?: [], ['.', '..']);

        if (! empty($items)) {
            return false;
        }

        return @rmdir($dir);
    }
}
