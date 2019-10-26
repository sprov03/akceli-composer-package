<?php

use Akceli\Akceli;
use Akceli\Generators\NewAkceliGenerator;
use Akceli\Generators\ChannelGenerator;
use Akceli\Generators\CommandGenerator;
use Akceli\Generators\ControllerGenerator;
use Akceli\Generators\EventGenerator;
use Akceli\Generators\ExceptionGenerator;
use Akceli\Generators\FactoryGenerator;
use Akceli\Generators\JobGenerator;
use Akceli\Generators\ListenerGenerator;
use Akceli\Generators\MailableGenerator;
use Akceli\Generators\MiddlewareGenerator;
use Akceli\Generators\MigrationGenerator;
use Akceli\Generators\ModelGenerator;
use Akceli\Generators\NotificationGenerator;
use Akceli\Generators\ObserverGenerator;
use Akceli\Generators\PolicyGenerator;
use Akceli\Generators\ProviderGenerator;
use Akceli\Generators\RequestGenerator;
use Akceli\Generators\ResourceGenerator;
use Akceli\Generators\RuleGenerator;
use Akceli\Generators\TestGenerator;
use Akceli\Generators\ApiControllerGenerator;
use Akceli\Generators\ServiceGenerator;
use Akceli\Generators\SeederGenerator;
/** auto import new commands */

/**
 * This is here to prevent this from running in production
 */
if (env('APP_ENV') !== 'local') {
    return [];
}

return [
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
        'new-command' => NewAkceliGenerator::class,
        'channel' => ChannelGenerator::class,
        'command' => CommandGenerator::class,
        'controller' => ControllerGenerator::class,
        'api_controller' => ApiControllerGenerator::class,
        'event' => EventGenerator::class,
        'exception' => ExceptionGenerator::class,
        'factory' => FactoryGenerator::class,
        'job' => JobGenerator::class,
        'listener' => ListenerGenerator::class,
        'mailable' => MailableGenerator::class,
        'middleware' => MiddlewareGenerator::class,
        'migration' => MigrationGenerator::class,
        'model' => ModelGenerator::class,
        'notification' => NotificationGenerator::class,
        'observer' => ObserverGenerator::class,
        'policy' => PolicyGenerator::class,
        'provider' => ProviderGenerator::class,
        'request' => RequestGenerator::class,
        'resource' => ResourceGenerator::class,
        'rule' => RuleGenerator::class,
        'test' => TestGenerator::class,
        'service' => ServiceGenerator::class,
        'seeder' => SeederGenerator::class,
        /** New Generators Get Inserted Here */
    ],
];
