<?php

namespace Ferrl\Paywall\Providers;

use Ferrl\Paywall\Paywall;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class PaywallServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot()
    {
        $this->publishFiles();

        Blade::if('paywall', [app('paywall'), 'allows']);
    }

    /**
     * Publish package files.
     */
    private function publishFiles()
    {
        $this->publishes([
            __DIR__.'/../../stub/config/paywall.php.stub' => config_path('paywall.php'),
            __DIR__.'/../../stub/migrations/2018_01_01_000000_create_paywall_causers_table.php.stub' => base_path('database/migrations/2018_01_01_000000_create_paywall_causers_table.php'),
            __DIR__.'/../../stub/migrations/2018_01_01_000001_create_paywall_subjects_table.php.stub' => base_path('database/migrations/2018_01_01_000001_create_paywall_subjects_table.php'),
            __DIR__.'/../../stub/migrations/2018_01_01_000002_create_paywall_events_table.php.stub' => base_path('database/migrations/2018_01_01_000002_create_paywall_events_table.php'),
        ], 'paywall');
    }

    /**
     * Register services.
     */
    public function register()
    {
        $this->app->bind('paywall', Paywall::class);
    }
}
