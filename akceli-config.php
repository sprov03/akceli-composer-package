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
        'base_model' => 'BaseModel',
        'ignored_relationships' => 'belongsTo:createdBy,belongsTo:updatedBy,belongsTo:importedBy,' .
            'belongsTo:deletedBy',
    ],

    /**
     * This is the default template set that is used
     */
    'default' => [
        'templates' => [
            [
                'name' => 'model',
                'path' => "app/Models/[[ModelName]].php"
            ],
            [
                'name' => 'model_factory',
                'path' => "database/factories/[[ModelName]]Factory.php"
            ],
            [
                'name' => 'client_model',
                'path' => "client/src/models/[[modelName]].ts"
            ],
            [
                'name' => 'api_crud_controller',
                'path' => "app/Http/Controllers/Api/[[ModelName]]Controller.php"
            ],
            [
                'name' => 'api_crud_controller_test',
                'path' => "tests/Http/Controllers/Api/[[ModelName]]ControllerTest.php"
            ],
            [
                'name' => 'create_form_request',
                'path' => "app/Http/Requests/Create[[ModelName]]Request.php"
            ],
            [
                'name' => 'patch_form_request',
                'path' => "app/Http/Requests/Patch[[ModelName]]Request.php",
            ],
            [
                'name' => 'model_service',
                'path' => "app/Models/Services/[[ModelName]]Service.php"
            ],
            [
                'name' => 'model_resource',
                'path' => "app/Resources/[[ModelName]]Resource.php"
            ],
        ],
        'inline_templates' => [
            [
                'name' => 'route_resource',
                'identifier' => '/** Routes File Marker: Do Not Remove Being Used Buy Code Generator */',
                'path' => 'routes/api.php'
            ],
        ],

        /**
         * highest Priority options can only be overwritten form the command
         *      line using the --other-variables option
         */
        'options' => [
//            'namespace' => 'Models'
        ],
    ],

    'api-crud-controller-only' => [
        'templates' => [
            [
                'name' => 'api_crud_controller_test',
                'path' => "tests/Http/Controllers/Api/[[ModelName]]ControllerTest.php"
            ],
            [
                'name' => 'api_crud_controller',
                'path' => "app/Http/Controllers/Api/[[ModelName]]Controller.php"
            ],
            [
                'name' => 'create_form_request',
                'path' => "app/Http/Requests/Create[[ModelName]]Request.php"
            ],
            [
                'name' => 'patch_form_request',
                'path' => "app/Http/Requests/Patch[[ModelName]]Request.php",
            ],
            [
                'name' => 'model_resource',
                'path' => "app/Resources/[[ModelName]]Resource.php"
            ],
            [
                'name' => 'client_api_service',
                'path' => "client/src/api/[[modelNames]].ts"
            ],
            [
                'name' => 'client_model',
                'path' => "client/src/models/[[modelName]].ts"
            ],
        ],
        'inline_templates' => [
            [
                'name' => 'route_resource',
                'identifier' => '/** Routes File Marker: Do Not Remove Being Used Buy Code Generator */',
                'path' => 'routes/api.php'
            ],
        ],
        'options' => [],
    ],

    'model-only' => [
        'templates' => [
            [
                'name' => 'model',
                'path' => "app/Models/[[ModelName]].php"
            ],
            [
                'name' => 'model_factory',
                'path' => "database/factories/[[ModelName]]Factory.php"
            ],
            [
                'name' => 'model_resource',
                'path' => "app/Resources/[[ModelName]]Resource.php"
            ],
            [
                'name' => 'client_model',
                'path' => "client/src/models/[[modelName]].ts"
            ],
            [
                'name' => 'model_service',
                'path' => "app/Models/Services/[[ModelName]]Service.php"
            ],
        ],
        'inline_templates' => [],
        'options' => [],
    ],

    'resource-only' => [
        'templates' => [
            [
                'name' => 'model_resource',
                'path' => "app/Resources/[[ModelName]]Resource.php"
            ],
        ],
        'inline_templates' => [],
        'options' => [],
    ]
];
