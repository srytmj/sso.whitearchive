<?php

namespace App\Providers;

use App\Services\Dashboard\SettingsService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (!Schema::hasTable('settings')) {
            return;
        }

        app(SettingsService::class)->applyToRuntimeConfig();
    }
}
