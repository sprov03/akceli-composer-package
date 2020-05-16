<?php

use Akceli\AkceliFileModifier;
use Akceli\Bootstrap\Bootstrap;

/**
 * these get processed in order so you can build this in any order that is required.
 */
return [
    Bootstrap::globalStringReplace('App\User', 'App\Models\User'),
    Bootstrap::globalStringReplace('
        <server name="DB_CONNECTION" value="sqlite"/>
        <server name="DB_DATABASE" value=":memory:"/>', ''),
    Bootstrap::deleteFile('app/User.php'),
//    Bootstrap::fileModifier(fn() =>
//        AkceliFileModifier::phpFile('')
//    ),
    Bootstrap::terminalCommand('php artisan akceli:generate model users'),
];
