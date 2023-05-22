<?php

namespace Bfg\Doc;

use Bfg\Doc\Commands\MakeDocsCommand;
use Bfg\Doc\Core\DataForGenerate;
use Bfg\Doc\Core\Listeners\UpdateClassDoc;
use Bfg\Doc\Core\Listeners\UpdateClassHelpers;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

/**
 * Class ServiceProvider
 * @package Bfg\Doc
 */
class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Register route settings.
     * @return void
     */
    public function register(): void
    {
        /**
         * Merge config from having by default
         */
        $this->mergeConfigFrom(
            __DIR__.'/../config/doc.php', 'doc'
        );

        /**
         * Register publisher scaffold configs
         */
        $this->publishes([
            __DIR__.'/../config/doc.php' => config_path('doc.php'),
        ], 'bfg-doc');
    }

    /**
     * Bootstrap services.
     * @return void
     */
    public function boot(): void
    {
        $this->commands([
            MakeDocsCommand::class
        ]);

        \Event::listen(DataForGenerate::class, UpdateClassDoc::class);

        \Event::listen(DataForGenerate::class, UpdateClassHelpers::class);
    }
}
