<?php

namespace WizballEsy\LibreNmsDevicePhoto\Services;

class PhotoDateService
{
    public function data(string $path): array
    {
        $fileDate = is_file($path) ? @filemtime($path) : null;
        $takenDate = $this->photoTakenTimestamp($path);

        return [
            'photo_taken_display' => $this->display($takenDate),
            'photo_taken_iso' => $this->iso($takenDate),
            'file_date_display' => $this->display($fileDate ?: null),
            'file_date_iso' => $this->iso($fileDate ?: null),
        ];
    }

    private function photoTakenTimestamp(string $path): ?int
    {
        if (! is_file($path)) {
            return null;
        }

        $ext = strtolower((string) pathinfo($path, PATHINFO_EXTENSION));

        if (! in_array($ext, ['jpg', 'jpeg'], true)) {
            return null;
        }

        if (! function_exists('exif_read_data')) {
            return null;
        }

        $exif = @exif_read_data($path, 'EXIF', true);

        if (! is_array($exif)) {
            return null;
        }

        $value = $exif['EXIF']['DateTimeOriginal']
            ?? $exif['EXIF']['DateTimeDigitized']
            ?? $exif['IFD0']['DateTime']
            ?? null;

        return $this->parseExifDate(is_string($value) ? $value : null);
    }

    private function parseExifDate(?string $value): ?int
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        $formats = [
            'Y:m:d H:i:s',
            'Y-m-d H:i:s',
            'Y:m:d H:i:sP',
            'Y-m-d H:i:sP',
        ];

        foreach ($formats as $format) {
            $date = \DateTime::createFromFormat($format, $value);

            if ($date instanceof \DateTime) {
                return $date->getTimestamp();
            }
        }

        $timestamp = strtotime($value);

        return $timestamp === false ? null : $timestamp;
    }

    private function display(?int $timestamp): ?string
    {
        if (! $timestamp) {
            return null;
        }

        return date('Y-m-d H:i', $timestamp);
    }

    private function iso(?int $timestamp): ?string
    {
        if (! $timestamp) {
            return null;
        }

        return date(DATE_ATOM, $timestamp);
    }
}
