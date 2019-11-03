<?php

use Akceli\Akceli;
use Akceli\Generators\DefaultGenerators\DefaultAllGenerator;
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
use Akceli\Modifiers\Builders\Relationships\BelongsToBuilder;
use Akceli\Modifiers\Builders\Relationships\BelongsToManyBuilder;
use Akceli\Modifiers\Builders\Relationships\HasManyBuilder;
use Akceli\Modifiers\Builders\Relationships\HasOneBuilder;
use Akceli\Modifiers\Builders\Relationships\MorphOneBuilder;
use Akceli\Modifiers\Builders\Relationships\MorphToBuilder;
use Akceli\Modifiers\Builders\Relationships\MorphToManyBuilder;

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
     *
     * This controls how you chose your templates.
     * (auto-complete): Is useful if you know what templates you have, you can just type the first few letters
     *      of the template name and when it is selected just press enter.  When you do not know what your options are
     *      you can simply press enter with nothing selected and it will switch to multiple-choice selection allowing
     *      for you to see the full lis of templates you want.
     */
    'select-template-behavior' => 'auto-complete',

    /**
     * This is for documenting what values you want to be show based on a given data type.
     */
    'column-settings' => [
        /**
         * Usage: <?=$column->getColumnSetting('php_class_doc_type', 'string')?>
         *
         * Outputs based on column analysis:
         *    Integer: 'integer'
         *    String: 'string'
         *    Enum: 'string'
         *    Timestamp: 'Carbon'
         *    Boolean: 'boolean'
         */
        'php_class_doc_type' => Akceli::columnSetting('string', 'integer', 'string', 'string', 'Carbon', 'boolean'),

        /**
         * Usage: <?=$column->getColumnSetting('casts', 'string')?>
         *
         * Outputs based on column analysis:
         *    Integer: null
         *    String: null
         *    Enum: null
         *    Timestamp: 'datetime'
         *    Boolean: 'boolean'
         */
        'casts' => Akceli::columnSetting(null, null, null, null, 'datetime', 'boolean'),
    ],

    /**
     * This is where all the magic happens!!
     *
     * To make a new command: php artisan akceli new-command
     * It will register the command in the following list for you and build out the boiler plate of the command class.
     */
    'generators' => [
        'all' => DefaultAllGenerator::class,
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

    /**
     * This mapping is used to build relationships
     * Can be used to add custom relationship and build a Custom RelationshipBuilder
     */
    'relationships' => [
        'belongsToMany' => BelongsToManyBuilder::class,
        'belongsTo' => BelongsToBuilder::class,
        'hasOne' => HasOneBuilder::class,
        'hasMany' => HasManyBuilder::class,
//        'morphMany' => MorphToManyBuilder::class,
//        'morphOne' => MorphOneBuilder::class,
//        'morphTo' => MorphToBuilder::class,
//        'morphToMany' => MorphToManyBuilder::class,
    ]
];
