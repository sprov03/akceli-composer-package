<?php

use Akceli\Akceli;
use Akceli\Generators\DefaultGenerators\DefaultNewAkceliGenerator;
use Akceli\Generators\DefaultGenerators\DefaultChannelGenerator;
use Akceli\Generators\DefaultGenerators\DefaultCommandGenerator;
use Akceli\Generators\DefaultGenerators\DefaultControllerGenerator;
use Akceli\Generators\DefaultGenerators\DefaultEventGenerator;
use Akceli\Generators\DefaultGenerators\DefaultExceptionGenerator;
use Akceli\Generators\DefaultGenerators\DefaultFactoryGenerator;
use Akceli\Generators\DefaultGenerators\DefaultJobGenerator;
use Akceli\Generators\DefaultGenerators\DefaultListenerGenerator;
use Akceli\Generators\DefaultGenerators\DefaultMailableGenerator;
use Akceli\Generators\DefaultGenerators\DefaultMiddlewareGenerator;
use Akceli\Generators\DefaultGenerators\DefaultMigrationGenerator;
use Akceli\Generators\DefaultGenerators\DefaultModelGenerator;
use Akceli\Generators\DefaultGenerators\DefaultNotificationGenerator;
use Akceli\Generators\DefaultGenerators\DefaultObserverGenerator;
use Akceli\Generators\DefaultGenerators\DefaultPolicyGenerator;
use Akceli\Generators\DefaultGenerators\DefaultProviderGenerator;
use Akceli\Generators\DefaultGenerators\DefaultRequestGenerator;
use Akceli\Generators\DefaultGenerators\DefaultResourceGenerator;
use Akceli\Generators\DefaultGenerators\DefaultRuleGenerator;
use Akceli\Generators\DefaultGenerators\DefaultTestGenerator;
use Akceli\Generators\DefaultGenerators\DefaultSeederGenerator;
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

    'generators' => [
        'new-command' => DefaultNewAkceliGenerator::class,
        'channel' => DefaultChannelGenerator::class,
        'command' => DefaultCommandGenerator::class,
        'controller' => DefaultControllerGenerator::class,
        'event' => DefaultEventGenerator::class,
        'exception' => DefaultExceptionGenerator::class,
        'factory' => DefaultFactoryGenerator::class,
        'job' => DefaultJobGenerator::class,
        'listener' => DefaultListenerGenerator::class,
        'mailable' => DefaultMailableGenerator::class,
        'middleware' => DefaultMiddlewareGenerator::class,
        'migration' => DefaultMigrationGenerator::class,
        'model' => DefaultModelGenerator::class,
        'notification' => DefaultNotificationGenerator::class,
        'observer' => DefaultObserverGenerator::class,
        'policy' => DefaultPolicyGenerator::class,
        'provider' => DefaultProviderGenerator::class,
        'request' => DefaultRequestGenerator::class,
        'resource' => DefaultResourceGenerator::class,
        'rule' => DefaultRuleGenerator::class,
        'test' => DefaultTestGenerator::class,
        'seeder' => DefaultSeederGenerator::class,
        /** New Generators Get Inserted Here */
    ],
];
