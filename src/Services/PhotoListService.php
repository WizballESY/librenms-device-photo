<?php

namespace WizballEsy\LibreNmsDevicePhoto\Services;

class PhotoListService
{
    public function __construct(
        private readonly PhotoPathService $paths,
        private readonly PhotoOrderService $order,
    ) {
    }

    public function safeDevicePrefix(int $deviceId): string
    {
        return 'device-' . $deviceId;
    }

    public function listFilenamesForDevice(int $deviceId): array
    {
        $photoDir = $this->paths->photosDir();
        $safeShortName = $this->safeDevicePrefix($deviceId);

        $photos = [];

        foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
            foreach (glob($photoDir . '/' . $safeShortName . '-*.' . $ext) ?: [] as $path) {
                if (is_file($path)) {
                    $filename = basename($path);
                    $photos[$filename] = $filename;
                }
            }
        }

        ksort($photos, SORT_NATURAL | SORT_FLAG_CASE);

        return $this->order->apply(array_values($photos), $safeShortName);
    }
}