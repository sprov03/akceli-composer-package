<?php

namespace Akceli;

use Akceli\Console\Commands\AkceliBuildRelationshipsCommand;
use Akceli\Console\Commands\AkceliGenerateCommand;
use Illuminate\Support\ServiceProvider;

class AkceliServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            AkceliGenerateCommand::class,
            AkceliBuildRelationshipsCommand::class
        ]);
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/publishable/config' => base_path('config'),
            __DIR__ . '/publishable/akceli' => base_path('akceli'),
	    ]);
    }
}
