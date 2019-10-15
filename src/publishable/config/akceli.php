<?php

use Akceli\Console;

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

    /**
     * Options: 'auto-complete' or 'multiple-choice'
     * will default to 'multiple-choice' if this is missing or set to an invalid option
     */
    'select-template-behavior' => 'auto-complete',

    'column-settings' => [
        'php_class_doc_type' => [
            'ignore_patterns' => [
//                '^created_at$',
//                '^updated_at$',
//                '^deleted_at$'
            ],
            'default' => 'string',
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
            'default' => null,
            'integer' => null,
            'string' => null,
            'enum' => null,
            'timestamp' => 'datetime',
            'boolean' => 'boolean'
        ]
    ],

    'template-groups' => [
        /**
         * This is the default template set that is used
         */
        'default_laravel_blade' => [
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
                    'name' => 'blade_controller',
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
                    'name' => 'views_create_page',
                    'path' => "resources/views/models/[[modelNames]]/show.blade.php",
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
         * Job Template where you can prompt which queue the job should use
         */
        'job' => [
            'requires_table_name' => false,
            'data' => [
                'Job' => [
                    'type' => 'ask',
                    'question' => "What is the Class Name of the Job?\n Example: File will create a FileJob Class",
                ],
                'Queue' => [
                    'type' => 'choice',
                    'question' => "What queue will this job be running in?",
                    'choices' => ['default', 'long-running']
                ],
            ],
            'templates' => [
                [
                    'name' => 'job',
                    'path' => "tests/Akceli/ActualFiles/app/Jobs/[[Job]]Job.php"
                ],
            ]
        ],

        /**
         * This is a simple example of a template that you can create
         */
        'service' => [
            'requires_table_name' => false,
            'data' => [
                'Service' => function () {
                    return Console::ask("What is the Class Name of the Service?\n Example: File will create a FileService Class", 'Dummy Data');
                }
            ],
            'templates' => [
                [
                    'name' => 'service',
                    'path' => "app/Services/[[Service]]Service/[[Service]]Service.php"
                ],
                [
                    'name' => 'service_test',
                    'path' => "tests/Services/[[Service]]Service/[[Service]]ServiceTest.php"
                ],
                [
                    'name' => 'service_stubs',
                    'path' => "tests/Services/[[Service]]Service/[[Service]]ServiceStubs.php"
                ],
            ]
        ],

        /**
         * This is a simple example of a template that you can create
         */
        'repository_pattern' => [
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
                    'name' => 'api_controller',
                    'path' => "app/Http/Controllers/Api/[[ModelName]]Controller.php"
                ],
                [
                    'name' => 'api_controller_test',
                    'path' => "tests/Http/Controllers/Api/[[ModelName]]ControllerTest.php"
                ],
                [
                    'name' => 'model_factory_pro',
                    'path' => "database/factories/[[ModelName]]Factory.php"
                ],
                [
                    'name' => 'create_model_request',
                    'path' => "app/Http/Requests/Store[[ModelName]]Request.php"
                ],
                [
                    'name' => 'patch_model_request',
                    'path' => "app/Http/Requests/Update[[ModelName]]Request.php",
                ],
            ],
            'inline_templates' => [
                [
                    'name' => 'route_resource',
                    'identifier' => '/** All Web controllers will go here */',
                    'path' => 'routes/web.php'
                ],
            ],
        ],
    ],
];
