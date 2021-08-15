<?php

namespace Bfg\Doc;

use Bfg\Doc\Commands\MakeDocsCommand;
use Bfg\Doc\Core\DataForGenerate;
use Bfg\Doc\Core\Listeners\UpdateClassDoc;
use Bfg\Doc\Core\Listeners\UpdateClassHelpers;
use Bfg\Installer\Processor\DumpAutoloadProcessor;
use Bfg\Installer\Providers\InstalledProvider;

/**
 * Class ServiceProvider
 * @package Bfg\Doc
 */
class ServiceProvider extends InstalledProvider
{
    /**
     * Set as installed by default.
     * @var bool
     */
    public bool $installed = true;

    /**
     * Executed when the provider is registered
     * and the extension is installed.
     * @return void
     */
    function installed(): void
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
     * Executed when the provider run method
     * "boot" and the extension is installed.
     * @return void
     */
    function run(): void
    {
        $this->commands([
            MakeDocsCommand::class
        ]);

        \Event::listen(DataForGenerate::class, UpdateClassDoc::class);

        \Event::listen(DataForGenerate::class, UpdateClassHelpers::class);
    }

    /**
     * Run on dump extension.
     * @param  DumpAutoloadProcessor  $processor
     */
    public function dump(DumpAutoloadProcessor $processor)
    {
        parent::dump($processor);

        \Doc::generate();
    }
}
