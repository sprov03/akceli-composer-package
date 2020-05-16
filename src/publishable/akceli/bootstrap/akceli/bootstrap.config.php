<?php

use Akceli\AkceliFileModifier;

/**
 * these get processed in order so you can build this in any order that is required.
 */
return [
    [
        'type' => 'string_replacements',
        'actions' => [
            'App\User' => 'App\Models\User',
            '
        <server name="DB_CONNECTION" value="sqlite"/>
        <server name="DB_DATABASE" value=":memory:"/>' => ''
        ]
    ],
    [
        'type' => 'files_to_remove',
        'actions' => [
            'app/User.php'
        ]
    ],
    [
        'type' => 'file_modifiers',
        'actions' => function () {
            return [
            ];
        }
    ],
    [
        'type' => 'commands',
        'actions' => [
            /** Generate All the User Model Files */
            'php artisan akceli:generate model users',
        ]
    ],
];
