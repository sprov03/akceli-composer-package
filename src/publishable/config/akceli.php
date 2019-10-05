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
     * Make sure that this is set to include all models, but if they are in a Models directory
     * Then set this to that directory, there will be a performance boost by limiting the files that need to be
     * Scanned during file lookups.
     */
    'root_model_path' => 'app',

    'column-settings' => [
        'php_class_doc_type' => [
            'ignore_patterns' => [
//                '^created_at$',
//                '^updated_at$',
//                '^deleted_at$'
            ],
            'integer' => 'integer',
            'string' => 'string',
            'enum' => 'string',
            'timestamp' => 'Carbon',
            'boolean' => 'boolean'
        ],
        'casts' => [
            'ignore_patterns' => [
//                '^created_at$',
//                '^updated_at$',
//                '^deleted_at$'
            ],
            'integer' => null,
            'string' => null,
            'enum' => null,
            'timestamp' => 'datetime',
            'boolean' => 'boolean'
        ]
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
                'path' => "app/Http/Controllers/[[ModelName]]Controller.php"
            ],
            [
                'name' => 'model_controller_test',
                'path' => "tests/Http/Controllers/Api/[[ModelName]]ControllerTest.php"
            ],
            [
                'name' => 'model_factory_standard',
                'path' => "database/factories/[[ModelName]]Factory.php"
            ],
            [
                'name' => 'model_factory_pro',
                'path' => "database/factories/[[ModelName]]Factory.php"
            ],
            [
                'name' => 'model_seeder',
                'path' => "database/seeds/[[ModelName]]Seeder.php"
            ],
            [
                'name' => 'store_model_request',
                'path' => "app/Http/Requests/Store[[ModelName]]Request.php"
            ],
            [
                'name' => 'update_model_request',
                'path' => "app/Http/Requests/Update[[ModelName]]Request.php",
            ],
            [
                'name' => 'views_create_page',
                'path' => "resources/views/models/[[modelNames]]/create.blade.php",
            ],
            [
                'name' => 'views_edit_page',
                'path' => "resources/views/models/[[modelNames]]/edit.blade.php",
            ],
            [
                'name' => 'views_index_page',
                'path' => "resources/views/models/[[modelNames]]/index.blade.php",
            ],
        ],
        'inline_templates' => [
            [
                'name' => 'route_resource',
                'identifier' => '/** All Web controllers will go here */',
                'path' => 'routes/web.php'
            ],
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
