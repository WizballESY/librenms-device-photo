<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Device Photo Storage
    |--------------------------------------------------------------------------
    |
    | These paths are relative to LibreNMS storage_path().
    | They match the current legacy/local plugin storage layout.
    |
    */

    'photos_path' => 'app/device-photos',

    'order_path' => 'app/device-photos-order',

    'links_path' => 'app/device-photos-links',

    /*
    |--------------------------------------------------------------------------
    | Upload limits
    |--------------------------------------------------------------------------
    */

    'max_upload_bytes' => 10 * 1024 * 1024,

    'max_pixels' => 25000000,

    /*
    |--------------------------------------------------------------------------
    | Allowed image extensions
    |--------------------------------------------------------------------------
    */

    'allowed_extensions' => [
        'jpg',
        'jpeg',
        'png',
        'webp',
        'heic',
        'heif',
    ],
];