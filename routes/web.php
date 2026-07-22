<?php

use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\InviteController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Dashboard\ApplicationController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\SessionController;
use App\Http\Controllers\Dashboard\UserManagementController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Public — always accessible, navbar changes based on auth state
Route::get('/', [HomeController::class, 'index'])->name('home');

// Guest-only
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:5,1');

    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    Route::get('/register/invite', [InviteController::class, 'show'])->name('invite.show');
    Route::post('/register/invite', [InviteController::class, 'store'])->name('invite.store');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'show'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email')->middleware('throttle:3,1');
    Route::get('/reset-password', [ResetPasswordController::class, 'show'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'store'])->name('password.update');
});

// Authenticated
Route::middleware('auth')->group(function () {
    Route::match(['get', 'post'], '/logout', [LogoutController::class, 'destroy'])->name('logout');

    // My Account
    Route::prefix('account')->name('account.')->group(function () {
        Route::get('/', [AccountController::class, 'show'])->name('show');
        Route::post('/password', [AccountController::class, 'changePassword'])->name('password');
        Route::get('/sessions', [AccountController::class, 'sessions'])->name('sessions');
        Route::delete('/sessions/{tokenId}', [AccountController::class, 'revokeSession'])->name('sessions.revoke');
        Route::delete('/sessions', [AccountController::class, 'revokeAllSessions'])->name('sessions.revoke-all');
    });

    // Dashboard — superadmin only
    Route::middleware('superadmin')->prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');

        Route::prefix('applications')->name('applications.')->group(function () {
            Route::get('/', [ApplicationController::class, 'index'])->name('index');
            Route::get('/create', [ApplicationController::class, 'create'])->name('create');
            Route::post('/', [ApplicationController::class, 'store'])->name('store');
            Route::get('/{application}', [ApplicationController::class, 'show'])->name('show');
            Route::patch('/{application}', [ApplicationController::class, 'update'])->name('update');
            Route::delete('/{application}', [ApplicationController::class, 'destroy'])->name('destroy');
        });

        Route::get('/sessions', [SessionController::class, 'index'])->name('sessions.index');
        Route::delete('/sessions/{id}', [SessionController::class, 'destroy'])->name('sessions.destroy');

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserManagementController::class, 'index'])->name('index');
            Route::patch('/{user}/toggle-active', [UserManagementController::class, 'toggleActive'])->name('toggle-active');
            Route::patch('/{user}/role', [UserManagementController::class, 'assignRole'])->name('role');
            Route::get('/invite', [UserManagementController::class, 'invite'])->name('invite');
            Route::post('/invite', [UserManagementController::class, 'sendInvite'])->name('send-invite');
        });
    });
});
