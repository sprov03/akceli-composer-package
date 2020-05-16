<?php

use Akceli\AkceliFileModifier;

return [
    'commands' => [
        // Realtime database
        'composer require akceli/laravel-realtime-database dev-master',
        'php artisan vendor:publish --provider="Akceli\RealtimeClientStoreSync\ServiceProvider"',

        // Sanctum
        'composer require laravel/sanctum',
        'php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"',
        'php artisan vendor:publish --tag=sanctum-migrations',
        'php artisan migrate',
    ],
    'string_replacements' => [
        'App\User' => 'App\Models\User'
    ],
    'files_to_remove' => [
        'app/User.php'
    ],
    'file_modifiers' => function () {
        return [
            AkceliFileModifier::phpFile('tests/TestCase.php')
                ->shouldUseTrait('Akceli\RealtimeClientStoreSync\Middleware\ClientStoreTestMiddlewareOverwrites'),

            AkceliFileModifier::phpFile('app/Models/User.php')
                ->shouldUseTrait('Akceli\RealtimeClientStoreSync\ClientStore\ClientStoreModelTrait')
                ->shouldUseTrait('App\ClientStores\UsersStore')
                ->addMethodToFile('getStoreProperties', <<<'EOF'
    public function getStoreProperties()
    {
        return [
            UsersStore::usersProperty($this->id),
        ];
    }
EOF
                ),

            AkceliFileModifier::phpFile('app/Http/Kernel.php')
                ->addUseStatementToFile('Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful')
                ->addUseStatementToFile('Akceli\RealtimeClientStoreSync\Middleware\FlushClientStoreChangesMiddleware')
                ->addLineBelow("'api' => [", '            EnsureFrontendRequestsAreStateful::class,')
                ->addLineBelow('protected $routeMiddleware = [', '        \'client-store\' => FlushClientStoreChangesMiddleware::class,'),

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
        ];
    },
];
