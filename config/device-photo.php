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

    'deleted_photos_path' => 'app/device-photos-deleted',

    'order_path' => 'app/device-photos-order',

    'links_path' => 'app/device-photos-links',

    /*
    |--------------------------------------------------------------------------
    | Upload limits
    |--------------------------------------------------------------------------
    */

    'max_upload_bytes' => 10 * 1024 * 1024,

    'max_pixels' => 40000000,

    /*
    |--------------------------------------------------------------------------
    | Thumbnail size
    |--------------------------------------------------------------------------
    */

    'thumbnail_max_width' => 360,

    'thumbnail_max_height' => 360,

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