<?php

namespace CrudGenerator;

use CrudGenerator\Console\Commands\CrudGeneratorCommand;
use CrudGenerator\Console\Commands\RelationshipGeneratorCommand;
use CrudGenerator\Console\Commands\TemplateGeneratorCommand;
use Illuminate\Support\ServiceProvider;

class CrudGeneratorServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([CrudGeneratorCommand::class]);
        $this->commands([RelationshipGeneratorCommand::class]);
        $this->commands([TemplateGeneratorCommand::class]);
    }

    public function boot()
    {
        $this->publishes([
	        __DIR__ . '/Templates' => base_path('resources/templates'),
            __DIR__ . '/Config' => base_path('config')
	    ]);
    }
}
