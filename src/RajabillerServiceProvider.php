<?php

namespace Kevinpurwito\LaravelRajabiller;

use Illuminate\Support\ServiceProvider;
use Kevinpurwito\LaravelRajabiller\Contracts\ItemContract;
use Kevinpurwito\LaravelRajabiller\Contracts\OrderContract;
use Kevinpurwito\LaravelRajabiller\Models\RbItem;
use Kevinpurwito\LaravelRajabiller\Models\RbOrder;

class RajabillerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->offerPublishing();

        $this->registerModelBindings();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/kp_rajabiller.php', 'kp_rajabiller');

        $this->app->singleton('Rajabiller', function () {
            return new Rajabiller(
                config('kp_rajabiller.env', env('KP_RB_ENV', 'dev')),
                config('kp_rajabiller.uid', env('KP_RB_UID', '')),
                config('kp_rajabiller.pin', env('KP_RB_PIN', ''))
            );
        });
    }

    protected function offerPublishing()
    {
        if (! function_exists('config_path')) {
            // function not available and 'publish' not relevant in Lumen
            return;
        }

        // config
        $this->publishes([
            __DIR__ . '/../config/kp_rajabiller.php' => config_path('kp_rajabiller.php'),
        ], ['rajabiller', 'rb-config']);

        // migrations
        $this->publishes([
            __DIR__ . '/../database/migrations/01_create_rb_groups_table.php' => database_path('migrations/' . date('Y_m_d_Hi', time()) . '01_create_rb_groups_table.php'),
        ], ['rajabiller', 'rb-migrations']);

        $this->publishes([
            __DIR__ . '/../database/migrations/02_create_rb_items_table.php' => database_path('migrations/' . date('Y_m_d_Hi', time()) . '02_create_rb_items_table.php'),
        ], ['rajabiller', 'rb-migrations']);

        $this->publishes([
            __DIR__ . '/../database/migrations/03_create_rb_orders_table.php' => database_path('migrations/' . date('Y_m_d_Hi', time()) . '03_create_rb_orders_table.php'),
        ], ['rajabiller', 'rb-migrations']);

        // seeders
        $this->publishes([
            __DIR__ . '/../database/seeders/RbSeeder.php' => database_path('seeders/ItemSeeder.php'),
        ], ['rajabiller', 'rb-seeders']);
    }

    protected function registerModelBindings()
    {
        $this->app->bind(ItemContract::class, RbItem::class);
        $this->app->bind(OrderContract::class, RbOrder::class);
    }
}
