<?php

namespace WizballEsy\LibreNmsDevicePhoto\Services;

class PhotoMetadataService
{
    public function __construct(
        private readonly PhotoImageService $images,
    ) {
    }

    public function exiftoolAvailable(): bool
    {
        return $this->images->findBinary('exiftool') !== null;
    }

    public function parsePhotoTakenInput(string $value): ?int
    {
        $value = trim($value);

        if ($value === '') {
            return null;
        }

        $timestamp = strtotime($value);

        return $timestamp === false ? null : $timestamp;
    }

    public function writePhotoTakenExif(string $path, int $timestamp): bool
    {
        $exiftool = $this->images->findBinary('exiftool');

        if (! $exiftool || ! is_file($path) || ! is_writable($path)) {
            return false;
        }

        $date = date('Y:m:d H:i:s', $timestamp);

        $cmd = escapeshellarg($exiftool)
            . ' -overwrite_original'
            . ' ' . escapeshellarg('-DateTimeOriginal=' . $date)
            . ' ' . escapeshellarg('-CreateDate=' . $date)
            . ' ' . escapeshellarg('-ModifyDate=' . $date)
            . ' ' . escapeshellarg($path)
            . ' 2>&1';

        $output = [];
        $returnCode = 1;

        @exec($cmd, $output, $returnCode);

        return $returnCode === 0;
    }
}