<?php

use Illuminate\Support\Facades\Route;
use WizballEsy\LibreNmsDevicePhoto\Http\Controllers\PhotoController;

Route::middleware(['web'])
    ->get('plugin/device-photo-package/image', [PhotoController::class, 'show'])
    ->name('device-photo.image');