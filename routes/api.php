<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api', 'scope:profile:read', 'check.user.active'])
    ->get('/user', [UserController::class, 'show']);
