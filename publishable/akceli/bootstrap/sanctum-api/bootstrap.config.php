<?php

use Akceli\AkceliFileModifier;
use Akceli\Bootstrap\Bootstrap;

return [
    Bootstrap::terminalCommand('composer require laravel/sanctum'),
    Bootstrap::terminalCommand('php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"'),
    Bootstrap::terminalCommand('php artisan vendor:publish --tag=sanctum-migrations'),
    
    Bootstrap::fileModifiers(fn() => [
        AkceliFileModifier::phpFile('app/Models/User.php')
            ->shouldUseTrait('Laravel\Sanctum\HasApiTokens'),
        
        AkceliFileModifier::phpFile('app/Http/Kernel.php')
            ->addUseStatementToFile('Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful')
            ->addLineBelow("'api' => [", '            EnsureFrontendRequestsAreStateful::class,'),
        
        AkceliFileModifier::phpFile('app/Providers/AppServiceProvider.php')
            ->addToTopOfMethod('register', 'Sanctum::ignoreMigrations();')
            ->addUseStatementToFile('Laravel\Sanctum\Sanctum'),
        
        AkceliFileModifier::phpFile('.env.example')
            ->addLineBelow('APP_URL', 'SESSION_DOMAIN=localhost')
            ->addLineBelow('APP_URL', 'SANCTUM_STATEFUL_DOMAINS=localhost')
            ->addLineBelow('APP_URL', 'MIX_CLIENT_STORE_URL="${CLIENT_STORE_URL}"')
            ->addLineBelow('APP_URL', 'CLIENT_STORE_URL=api/client-store'),

        AkceliFileModifier::phpFile('.env')
            ->addLineBelow('APP_URL', 'SESSION_DOMAIN=localhost')
            ->addLineBelow('APP_URL', 'SANCTUM_STATEFUL_DOMAINS=localhost')
            ->addLineBelow('APP_URL', 'MIX_CLIENT_STORE_URL="${CLIENT_STORE_URL}"')
            ->addLineBelow('APP_URL', 'CLIENT_STORE_URL=api/client-store'),
    ]),
    
    Bootstrap::terminalCommand('php artisan migrate'),
];
