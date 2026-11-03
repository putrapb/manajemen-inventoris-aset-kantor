<?php

use App\Http\Controllers\Api\AssetApiController;
use Illuminate\Support\Facades\Route;

Route::get('/assets', [AssetApiController::class, 'index'])->name('api.assets.index');
