<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::middleware('role:admin')->group(function () {
        Route::get('/assets/create', [AssetController::class, 'create'])->name('assets.create');
        Route::post('/assets', [AssetController::class, 'store'])->name('assets.store');
        Route::get('/assets/{asset}/edit', [AssetController::class, 'edit'])->name('assets.edit');
        Route::put('/assets/{asset}', [AssetController::class, 'update'])->name('assets.update');
        Route::patch('/assets/{asset}', [AssetController::class, 'update']);
        Route::delete('/assets/{asset}', [AssetController::class, 'destroy'])->name('assets.destroy');
    });
});
