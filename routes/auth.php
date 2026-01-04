<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware('guest')->group(function () {
    Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [AuthController::class, 'register'])->name('register.submit');
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.submit');
    
    // Password Reset
    Route::get('forgot-password', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
    
    // Redirect /reset-password (GET) to forgot-password to avoid 404
    Route::get('reset-password', function() {
        return redirect()->route('password.request');
    });
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout');
