<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api', 'scope:profile:read', 'check.user.active', 'throttle:60,1'])
    ->get('/user', [UserController::class, 'show']);
