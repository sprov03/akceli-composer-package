<?php

namespace Akceli;

use Akceli\Console\Commands\AkceliGenerateCommand;
use Akceli\Console\Commands\RelationshipGeneratorCommand;
use Akceli\Console\Commands\TemplateGeneratorCommand;
use Illuminate\Support\ServiceProvider;

class AkceliServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([AkceliGenerateCommand::class]);
        $this->commands([RelationshipGeneratorCommand::class]);
        $this->commands([TemplateGeneratorCommand::class]);
    }

    public function boot()
    {
        $this->publishes([
	        __DIR__ . '/Templates' => base_path('akceli/templates'),
            __DIR__ . '/Config' => base_path('config')
	    ]);
    }
}
