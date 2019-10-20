<?php

use Akceli\Akceli;
use Akceli\Console;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use function Clue\StreamFilter\fun;

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
        'php_class_doc_type' => Akceli::columnSetting('string', 'integer', 'string', 'string', 'Carbon', 'boolean'),
        'casts' => Akceli::columnSetting(null, null, null, null, 'datetime', 'boolean'),
    ],

    'template-groups' => [

        'auth-not-doing-this-one' => [
            'templates' => []
        ],

        /**
         * Broadcast Channel Generator
         */
        'channel' => [
            'requires_table_name' => false,
            'data' => [
                'Channel' => function () {
                    return Str::studly(Console::ask('What is the name of the Channel you want to create?'));
                },
            ],
            'templates' => [
                Akceli::fileTemplate('channel', 'app/Broadcasting/[[Channel]]Channel.php'),
                Akceli::fileTemplate('channel_test', 'tests/Broadcasting/[[Channel]]ChannelTest.php'),
            ],
            'inline_templates' => [
                //Akceli::inlineTemplate('channel_register', 'routes/channels.php', '/** Dont forget to add the channel to the channels.php file */')
            ],
            'completion_message' => function () {
                Console::alert('Dont forget to register the Channel in routes/channels.php');
                Console::warn('Documentation: https://laravel.com/docs/5.8/broadcasting#defining-channel-classes');
            }
        ],

        /**
         * Command Generator
         */
        'command' => [
            'requires_table_name' => false,
            'data' => [
                'Command' => function() {
                    return Console::ask('What is the name of the Command?');
                },
                'Signature' => function() {
                    return Console::ask('What is the signature for the command?');
                }
            ],
            'templates' => [
                Akceli::fileTemplate('command', 'app/Console/Commands/[[Command]]Command.php'),
                Akceli::fileTemplate('command_test', 'tests/Console/Commands/[[Command]]CommandTest.php'),
            ]
        ],

        /**
         * This is the controller template set that is used
         */
        'controller' => [
            'templates' => [
                Akceli::fileTemplate('controller', 'app/Http/Controllers/[[ModelName]]Controller.php'),
                Akceli::fileTemplate('controller_test', 'tests/Http/Controllers/Api/[[ModelName]]ControllerTest.php'),
                Akceli::fileTemplate('store_model_request', 'app/Http/Requests/Store[[ModelName]]Request.php'),
                Akceli::fileTemplate('update_model_request', 'app/Http/Requests/Update[[ModelName]]Request.php'),
                Akceli::fileTemplate('views_create_page', 'resources/views/models/[[modelNames]]/create.blade.php'),
                Akceli::fileTemplate('views_create_page', 'resources/views/models/[[modelNames]]/show.blade.php'),
                Akceli::fileTemplate('views_edit_page', 'resources/views/models/[[modelNames]]/edit.blade.php'),
                Akceli::fileTemplate('views_index_page', 'resources/views/models/[[modelNames]]/index.blade.php'),
            ],
            'inline_templates' => [
                Akceli::inlineTemplate('route_resource', 'routes/web.php', '/** All Web controllers will go here */'),
            ],
        ],

        /**
         * Event Template set
         */
        'event' => [
            'requires_table_name' => false,
            'data' => [
                'Event' => function () {
                    return Console::ask('What is the name of the event?');
                }
            ],
            'templates' => [
                Akceli::fileTemplate('event', 'app/Events/[[Event]]Event.php'),
                Akceli::fileTemplate('event_test', 'tests/Events/[[Event]]EventTest.php')
            ],
            'completion_message' => function() {
                Console::alert('Dont forget to register the Event in app/Providers/EventServiceProvider.php');
                Console::warn('Documentation: https://laravel.com/docs/5.8/events#registering-events-and-listeners');
            }
        ],

        /**
         * Exception Template
         */
        'exception' => [
            'data' => [
                'Exception' => function() {
                    return Console::ask('What is the name of the Exception?');
                }
            ],
            'templates' => [
                Akceli::fileTemplate('exception', 'app/Exceptions/[[Exception]]Exception.php')
            ]
        ],

        /**
         * Generates a detailed custom factory
         */
        'factory' => [
            'templates' => [
                Akceli::fileTemplate('model_factory', 'database/factories/[[ModelName]]Factory.php'),
            ]
        ],

        /**
         * Job Template where you can prompt which queue the job should use
         */
        'job' => [
            'requires_table_name' => false,
            'data' => [
                'Job' => function () {
                    return Console::ask("What is the Class Name of the Job?\n Example: File will create a FileJob Class");
                },
                'Queue' => function () {
                    $queues = ['default', 'long-running'];
                    return Console::choice("What queue will this job be running in?", $queues, $queues[0]);
                }
            ],
            'templates' => [
                Akceli::fileTemplate('job', 'tests/Akceli/ActualFiles/app/Jobs/[[Job]]Job.php'),
            ]
        ],

        /**
         * Listener template
         */
        'listener' => [
            'requires_table_name' => false,
            'data' => [
                'Listener' => function() {
                    return Console::ask('What is the name of the Listener?');
                }
            ],
            'templates' => [
                Akceli::fileTemplate('listener', 'app/Listeners/[[Listener]]Listener.php'),
                Akceli::fileTemplate('listener_test', 'tests/Listeners/[[Listener]]ListenerTest.php'),
            ],
            'completion_message' => function() {
                Console::alert('Dont forget to register the Listener in app/Providers/EventServiceProvider.php');
                Console::warn('Documentation: https://laravel.com/docs/5.8/events#registering-events-and-listeners');
            }
        ],

        /**
         * Mail Template
         */
        'mail' => [
            'requires_table_name' => false,
            'data' => [
                'Mailable' => function() {
                    Console::info('Markdown Messages Documentation: https://laravel.com/docs/5.8/mail#writing-markdown-messages');
                    return Console::ask('What is the name of the Mailable?');
                },
                'mailable_type' => function() {
                    return Console::choice('Is [[Mailable]]Mailable using view or markdown?', ['markdown', 'view'], 'markdown');
                },
                'markdown_path' => function() {
                    return Console::ask('What is the path for the markdown file? example (example will be placed in resources/views/email/example)');
                },
            ],
            'templates' => [
                Akceli::fileTemplate('mailable', 'app/Mail/[[Mailable]]Mailable.php'),
                Akceli::fileTemplate('mailable_markdown', 'resources/views/emails/[[markdown_path]].blade.php'),
            ]
        ],

        /**
         * Middleware Template
         */
        'middleware' => [
            'requires_table_name' => false,
            'data' => [
                'Middleware' => function() {
                    return Console::ask('What is the name of the Middleware?');
                }
            ],
            'templates' => [
                Akceli::fileTemplate('middleware', 'app/Http/Middleware/[[Middleware]]Middleware.php'),
                Akceli::fileTemplate('middleware_test', 'tests/Http/Middleware/[[Middleware]]MiddlewareTest.php'),
            ]
        ],

        /**
         * Migration Template
         */
        'migration' => [
            'requires_table_name' => false,
            'data' => [
                'migration_timestamp' => function() {
                    return now()->format('Y_m_d_u');
                },
                'migration_name' => function() {
                    $response = Console::ask('What is the name of the migration?');
                    return Str::snake(str_replace(' ', '_', $response));
                },
                'migration_type' => function() {
                    return Console::choice('Is this a create or update migration?', ['create', 'update'], 'create');
                },
                'table_name' => function() {
                    return Console::ask('What is the name of the table being used in the migration?');
                }
            ],
            'templates' => [
                Akceli::fileTemplate('migration', 'database/migrations/[[migration_timestamp]]_[[migration_name]].php')
            ]
        ],

        /**
         * Generates Details Model
         */
        'model' => [
            'templates' => [
                Akceli::fileTemplate('model', 'app/[[namespace_path]]/[[ModelName]].php'),
                Akceli::fileTemplate('model_test', 'tests/[[namespace_path]]/[[ModelName]]Test.php'),
                Akceli::fileTemplate('model_factory', 'database/factories/[[ModelName]]Factory.php'),
            ]
        ],

        'notification' => [
            'requires_table_name' => false,
            'templates' => []
        ],
        'observer' => [
            'requires_table_name' => false,
            'templates' => []
        ],
        'policy' => [
            'requires_table_name' => false,
            'templates' => []
        ],
        'provider' => [
            'requires_table_name' => false,
            'templates' => []
        ],
        'request' => [
            'requires_table_name' => false,
            'templates' => []
        ],
        'resource' => [
            'requires_table_name' => false,
            'templates' => []
        ],
        'rule' => [
            'requires_table_name' => false,
            'templates' => []
        ],
        'seeder' => [
            'templates' => [
                Akceli::fileTemplate('model_seeder', 'database/seeds/[[ModelName]]Seeder.php'),
            ],
            'inline_templates' => [
                Akceli::inlineTemplate('seeder_reference', 'database/seeds/DatabaseSeeder.php', '        /** Dont forget to add the Seeder to database/seeds/DatabaseSeeder.php */'),
            ]
        ],
        'test' => [
            'requires_table_name' => false,
            'templates' => []
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
                Akceli::filetemplate('service', 'app/Services/[[Service]]Service/[[Service]]Service.php'),
                Akceli::filetemplate('service_test', 'tests/Services/[[Service]]Service/[[Service]]ServiceTest.php'),
                Akceli::filetemplate('service_stubs', 'tests/Services/[[Service]]Service/[[Service]]ServiceStubs.php'),
            ]
        ],

        /**
         * This is a simple example of a template that you can create
         */
        'repository_pattern' => [
            'templates' => [
                Akceli::fileTemplate('model', 'app/[[namespace_path]]/[[ModelName]].php'),
                Akceli::fileTemplate('model_test', 'tests/[[namespace_path]]/[[ModelName]]Test.php'),
                Akceli::fileTemplate('api_controller', 'app/Http/Controllers/Api/[[ModelName]]Controller.php'),
                Akceli::fileTemplate('api_controller_test', 'tests/Http/Controllers/Api/[[ModelName]]ControllerTest.php'),
                Akceli::fileTemplate('model_factory', 'database/factories/[[ModelName]]Factory.php'),
                Akceli::fileTemplate('create_model_request', 'app/Http/Requests/Store[[ModelName]]Request.php'),
                Akceli::fileTemplate('patch_model_request', 'app/Http/Requests/Update[[ModelName]]Request.php"'),
            ],
            'inline_templates' => [
                Akceli::inlineTemplate('route_resource','routes/web.php', '/** All Web controllers will go here */'),
            ],
        ],
    ],
];
