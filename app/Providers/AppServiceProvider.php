<?php

namespace App\Providers;

use App\Services\Modules\ModuleGate;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Blade Directive @module('slug') ... @endmodule
        Blade::if('module', function (string $slug) {
            return ModuleGate::isEnabled($slug);
        });
    }
}
