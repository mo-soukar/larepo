<?php

namespace Soukar\Larepo;

use Illuminate\Support\ServiceProvider;
use Soukar\Larepo\Console\Commands\MakeCrudCommand;
use Soukar\Larepo\Console\Commands\MakeDTOCommand;
use Soukar\Larepo\Console\Commands\MakeRepositoryCommand;

class LarepoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/Config/larepo.php',
            'larepo'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->commands(
            [
                MakeRepositoryCommand::class,
                MakeDTOCommand::class,
                MakeCrudCommand::class,
            ]
        );
        $this->provides([RepositoryServiceProvider::class]);
        $this->publishes(
            [
                __DIR__ . '/Config/larepo.php' => config_path('larepo.php'),
            ],
            'larepo'
        );
    }
}
