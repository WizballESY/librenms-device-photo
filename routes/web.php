<?php

use Illuminate\Support\Facades\Route;
use WizballEsy\LibreNmsDevicePhoto\Http\Controllers\ActionController;
use WizballEsy\LibreNmsDevicePhoto\Http\Controllers\PhotoController;

Route::middleware(['web'])
    ->get('plugin/device-photo-package/image', [PhotoController::class, 'show'])
    ->name('device-photo.image');

Route::middleware(['web'])
    ->post('plugin/device-photo-package/action', [ActionController::class, 'handle'])
    ->name('device-photo.action');