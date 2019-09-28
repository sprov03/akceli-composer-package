<?php

namespace Akceli;

use Akceli\Console\Commands\AkceliGenerateCommand;
use Akceli\Console\Commands\AkceliGenerateRelationshipsOnlyCommand;
use Akceli\Console\Commands\AkceliGenerateTemplatesOnlyCommand;
use Illuminate\Support\ServiceProvider;

class AkceliServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([AkceliGenerateCommand::class]);
//        $this->commands([AkceliGenerateRelationshipsOnlyCommand::class]);
//        $this->commands([AkceliGenerateTemplatesOnlyCommand::class]);
    }

    public function boot()
    {
        $this->publishes([
	        __DIR__ . '/Templates' => base_path('akceli/templates'),
            __DIR__ . '/Config' => base_path('config')
	    ]);
    }
}
