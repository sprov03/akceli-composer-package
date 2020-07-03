<?php

use Akceli\AkceliFileModifier;
use Akceli\Bootstrap\Bootstrap;
use Akceli\Console;

$sessionDomain = 'app.example.local';
$statefulDomains = 'app.example.local';

/**
 * these get processed in order so you can build this in any order that is required.
 */
return [
    /**
     * Base Akceli Project Setup
     */
    Bootstrap::globalStringReplace('App\User', 'App\Models\User'),
    Bootstrap::globalStringReplace('
        <server name="DB_CONNECTION" value="sqlite"/>
        <server name="DB_DATABASE" value=":memory:"/>', ''),
    Bootstrap::deleteFile('app/User.php'),
    Bootstrap::terminalCommand('php artisan akceli:generate model users'),
    Bootstrap::fileModifiers(fn() => [
        AkceliFileModifier::phpFile('tests/TestCase.php')
            ->shouldUseTrait('Illuminate\Foundation\Testing\DatabaseTransactions')
    ]),

    /**
     * Sanctum
     */
//    Bootstrap::terminalCommand('composer require laravel/sanctum laravel/ui:^2.0'),
//    Bootstrap::terminalCommand('php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"'),
//    Bootstrap::terminalCommand('php artisan vendor:publish --tag=sanctum-migrations'),
//
//    Bootstrap::fileModifiers(fn() => [
//        AkceliFileModifier::phpFile('app/Models/User.php')
//            ->shouldUseTrait('Laravel\Sanctum\HasApiTokens'),
//
//        AkceliFileModifier::phpFile('app/Http/Kernel.php')
//            ->addUseStatementToFile('Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful')
//            ->addLineBelow("'api' => [", '            EnsureFrontendRequestsAreStateful::class,'),
//
//        AkceliFileModifier::phpFile('app/Providers/AppServiceProvider.php')
//            ->addToTopOfMethod('register', 'Sanctum::ignoreMigrations();')
//            ->addUseStatementToFile('Laravel\Sanctum\Sanctum'),
//
//        AkceliFileModifier::phpFile('.env.example')
//            ->addLineBelow('APP_URL', 'SESSION_DOMAIN=' . $sessionDomain = Console::ask('Session Domains: (.example.local) will leave it open to all subdomains', $sessionDomain))
//            ->addLineBelow('APP_URL', 'SANCTUM_STATEFUL_DOMAINS=' . $statefulDomains = Console::ask('App Url: (comma separated)', $statefulDomains)),
//
//        AkceliFileModifier::phpFile('.env')
//            ->addLineBelow('APP_URL', 'SESSION_DOMAIN=' . $sessionDomain)
//            ->addLineBelow('APP_URL', 'SANCTUM_STATEFUL_DOMAINS=' . $statefulDomains),
//    ]),
//
//    Bootstrap::terminalCommand('php artisan migrate'),
//    Bootstrap::terminalCommand('php artisan ui vue --auth'),
];
