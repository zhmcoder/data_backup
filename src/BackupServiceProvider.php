<?php

namespace Andruby\Data\Backup;

use Andruby\Data\Backup\Console\BackupCommand;
use Andruby\Data\Backup\Console\InstallCommand;
use Illuminate\Support\ServiceProvider;

class BackupServiceProvider extends ServiceProvider
{

    protected $routeMiddleware = [

    ];

    protected $commands = [
        BackupCommand::class,
        InstallCommand::class
    ];

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
            $this->publishes([
                __DIR__ . '/../config/data_backup.php' => config_path('data_backup.php'),
            ]);
        }

        $this->loadRoutesFrom(__DIR__ . '/../routes/route.php');

    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/data_backup.php', 'data_backup');

        $this->registerRouteMiddleware();

        $this->commands($this->commands);
    }


    protected function registerRouteMiddleware()
    {
        // register route middleware.
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }

    }
}
