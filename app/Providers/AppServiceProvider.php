<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        app('filament-shield')->enforcePolicies();

        Blade::component('components.icon', 'icon');

        Paginator::defaultView('components.pagination');
    }
}
