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
        'App\Models\User' => 'App\Models\User'
    ],
    'files_to_remove' => [
        'app/User.php'
    ],
    'file_modifiers' => function () {
        return [
            AkceliFileModifier::file('tests/TestCase.php')
                ->shouldUseTrait('Akceli\RealtimeClientStoreSync\Middleware\ClientStoreTestMiddlewareOverwrites'),

            AkceliFileModifier::file('app/Models/User.php')
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

            AkceliFileModifier::file('app/Http/Kernel.php')
                ->addUseStatementToFile('Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful')
                ->addUseStatementToFile('Akceli\RealtimeClientStoreSync\Middleware\FlushClientStoreChangesMiddleware')
                ->addLineBelow("'api' => [", '            EnsureFrontendRequestsAreStateful::class,')
                ->addLineBelow('protected $routeMiddleware = [', '        \'client-store\' => FlushClientStoreChangesMiddleware::class,'),

            AkceliFileModifier::file('app/Providers/AppServiceProvider.php')
                ->addToTopOfMethod('register', 'Sanctum::ignoreMigrations();')
                ->addUseStatementToFile('Laravel\Sanctum\Sanctum'),

            AkceliFileModifier::file('.env.example')
                ->addLineBelow('APP_URL', 'MIX_CLIENT_STORE_URL="${CLIENT_STORE_URL}"')
                ->addLineBelow('APP_URL', 'CLIENT_STORE_URL=api/client-store'),

            AkceliFileModifier::file('.env')
                ->addLineBelow('APP_URL', 'MIX_CLIENT_STORE_URL="${CLIENT_STORE_URL}"')
                ->addLineBelow('APP_URL', 'CLIENT_STORE_URL=api/client-store'),
        ];
    },
];
