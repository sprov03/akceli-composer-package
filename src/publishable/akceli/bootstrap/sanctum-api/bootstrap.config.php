<?php

use Akceli\AkceliFileModifier;

return [
    'commands' => [
        // Realtime database
        'composer require akceli/laravel-realtime-database',
        'php artisan vendor:publish --provider="Akceli\RealtimeClientStoreSync\ServiceProvider"',

        // Sanctum
        'composer require laravel/sanctum',
        'php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"',
        'php artisan vendor:publish --tag=sanctum-migrations',
        'php artisan migrate',
    ],
    'string_replacements' => [
        'App\Models\User' => 'App\Models\User'
    ],
    'files_to_remove' => [
        'app/User.php'
    ],
    'file_modifiers' => [
        AkceliFileModifier::file('tests/TestCase.php')
            ->shouldUseTrait('Akceli\RealtimeClientStoreSync\Middleware\ClientStoreTestMiddlewareOverwrites'),

        AkceliFileModifier::file('app/Http/Kernel.php')
            ->addUseStatementToFile('Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful')
            ->addLineBelow('\'api\' => [', 'EnsureFrontendRequestsAreStateful::class,'),


//        AkceliFileModifier::file('app/Providers/AppServiceProvider.php')
//            ->addToMethod('register', 'Sanctum::ignoreMigrations;')
//            ->addUseStatementToFile('Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful')
    ],
];