<?php

return [
    /**
     * Global Setting You can over wright these in specific template options or
     *      by passing others in thew the commandline
     *      Anything you add here will be accessible in your templates
     *
     *      Example:  'key' => 'value'
     *      In Template get value by <?=$key?> or [[key]]
     */
    'options' => [
        'namespace' => 'App\Models',
        'namespace_path' => 'Models',
        'fully_qualified_base_model_name' => '\Illuminate\Database\Eloquent\Model',
        'base_model' => 'Model'
    ],

    /**
     * This is the default template set that is used
     */
    'default' => [
        'templates' => [
            [
                'name' => 'model',
                'path' => "app/[[namespace_path]]/[[ModelName]].php"
            ],
            [
                'name' => 'model_test',
                'path' => "tests/[[namespace_path]]/[[ModelName]]Test.php"
            ],
            [
                'name' => 'model_controller',
                'path' => "app/Http/Controllers/Api/[[ModelName]]Controller.php"
            ],
            [
                'name' => 'model_controller_test',
                'path' => "tests/Http/Controllers/Api/[[ModelName]]ControllerTest.php"
            ],
//            [
//                'name' => 'model_factory',
//                'path' => "database/factories/[[namespace_path]]/[[ModelName]]Factory.php"
//            ],
//            [
//                'name' => 'model_seeder',
//                'path' => "database/seeds/[[ModelName]]Seeder.php"
//            ],
        ],
        'inline_templates' => [
//            [
//                'name' => 'route_resource',
//                'identifier' => '/** Routes File Marker: Do Not Remove Being Used Buy Code Generator */',
//                'path' => 'app/Http/routes.php'
//            ],
//            [
//                'name' => 'seeder_reference',
//                'identifier' => '        /** Seeder File Marker: Do Not Remove Being Used Buy Code Generator */',
//                'path' => 'database/seeds/DatabaseSeeder.php'
//            ]
        ],

        /**
         * highest Priority options can only be overwritten form the command
         *      line using the --other-variables option
         */
        'options' => [
//            'namespace' => 'Models'
        ],
    ],

    /**
     * This is a simple example of a template that you can create
     */
    'admin' => [
        'templates' => [
            [
                'name' => 'model',
                'path' => "app/[[namespace_path]]/[[ModelName]].php"
            ],
            [
                'name' => 'model_test',
                'path' => "tests/app/[[namespace_path]]/[[ModelName]]Test.php"
            ],
            [
                'name' => 'model_controller',
                'path' => "app/Http/Controllers/[[ModelName]]Controller.php"
            ],
            [
                'name' => 'model_controller_test',
                'path' => "tests/app/Http/Controllers/[[ModelName]]ControllerTest.php"
            ],
            [
                'name' => 'model_factory',
                'path' => "database/factories/[[namespace_path]]/[[ModelName]]Factory.php"
            ],
            [
                'name' => 'model_seeder',
                'path' => "database/seeds/[[ModelName]]Seeder.php"
            ],
        ],
        'inline_templates' => [
            [
                'name' => 'route_resource',
                'identifier' => '/** Routes File Marker: Do Not Remove Being Used Buy Code Generator */',
                'path' => 'app/Http/routes.php'
            ]
        ],

        /**
         * highest Priority options can only be overwritten form the command
         *      line using the --other-variables option
         */
        'options' => [
            'namespace' => 'Admin'
        ],
    ],
];
