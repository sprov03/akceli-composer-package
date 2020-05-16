<?php

use Akceli\Akceli;
use Akceli\DefaultNewAkceliGenerator;
use Akceli\DefaultBootstrapGenerator;
use Akceli\Generators\AllGenerator;
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
use Akceli\Generators\SeederGenerator;
use Akceli\Generators\ApiControllerGenerator;
use Akceli\Modifiers\Builders\Relationships\BelongsToBuilder;
use Akceli\Modifiers\Builders\Relationships\BelongsToManyBuilder;
use Akceli\Modifiers\Builders\Relationships\HasManyBuilder;
use Akceli\Modifiers\Builders\Relationships\HasOneBuilder;
use Akceli\Modifiers\Builders\Relationships\MorphOneBuilder;
use Akceli\Modifiers\Builders\Relationships\MorphManyBuilder;
use Akceli\Modifiers\Builders\Relationships\MorphToBuilder;
use Akceli\Modifiers\Builders\Relationships\MorphToManyBuilder;

/** auto import new commands */

/**
 * This is here to prevent generators form being active in production
 */
if (env('APP_ENV') !== 'local') {
    return [];
}

return [
    /**
     * Model Directory
     *
     *      Akceli has two way that it can find a file Model
     *
     *          First:  Akceli will try to find a file with the $table attribute set to the same as your model.
     *                  If that property is set, you will have no issues finding the correct file for your model.
     *
     *          Second: If the first lookup fails, Akceli will try to find a file with the Laravel naming conventions for the table,
     *                  This can cause it to find a file that is not a model.  If all of your files are in a model directory
     *                  Or you do not have Multiple Classes that are the same name as the Expected Model, then there will be no issues
     *                  finding the correct file.
     *
     *          Note:  The possible failures of the second lookup is why Akceli models get generated with the $table property set.
     *                 If your app uses the Models directory, I suggest you set it here.  It will speed up the Model lookups.
     *                 To Insure that the model lookups always resolve correctly simply add the $table attribute to the models.
     */
    'model_directory' =>  'Models',

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
     * tables that will be skipped when using the Generate All Generator
     */
    'all-generator-blacklist' => [
        'failed_jobs',
        'migrations',
        'password_resets',
    ],

    /**
     * Generators that generate relationships
     *
     * If you have a generator that generates relationships, then add it here so that the all generator will flesh out the belongs to many
     * Relationships during the process, example models.  So all relationships are added to the models.
     */
    'generators_that_generate_relationships' => ['model'],

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

        /*
         * Usage: <?=$column->getColumnSetting('php_method_doc_type', 'string')?>
         */
        'php_method_doc_type' => Akceli::columnSetting('string', 'int', 'string', 'string', 'Carbon', 'bool'),

        /*
         * Usage: <?=$column->getColumnSetting('nova_field_type', 'Text')?>
         */
        'nova_field_type' => Akceli::columnSetting('Text', 'Number', 'Text', 'Text', 'DateTime', 'Boolean'),

        /*
         * Usage: <?=$column->getColumnSetting('apid_example', 'string')?>
         */
        'apid_example' => Akceli::columnSetting('examples string', '123', 'example string', 'example string', '2016-07-01T15:11:09.553Z', 'true'),

        /*
         * Usage: <?=$column->getColumnSetting('apid_type', 'string')?>
         */
        'apid_type' => Akceli::columnSetting('string', 'number', 'string', 'string', 'string', 'boolean'),

        /*
         * Usage: <?=$column->getColumnSetting('apid_default_description', 'string')?>
         */
        'apid_default_description' => Akceli::columnSetting('Is a String', 'Is a Number', 'Is A String', 'Is an Enum', 'ISO Date/time string', 'Is a Boolean'),

    ],

    /**
     * This is where all the magic happens!!
     *
     * To create a new Generator: php artisan akceli:generate generator
     * It will register the Generator in the following list for you and build out the boiler plate of the Generator class.
     */
    'generators' => [
        'generator' => DefaultNewAkceliGenerator::class,
        'all' => AllGenerator::class,
        'channel' => ChannelGenerator::class,
        'command' => CommandGenerator::class,
        'controller' => ControllerGenerator::class,
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
        'seeder' => SeederGenerator::class,
        'api_controller' => ApiControllerGenerator::class,
        'bootstrap' => DefaultBootstrapGenerator::class,
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
        'morphMany' => MorphManyBuilder::class,
        'morphOne' => MorphOneBuilder::class,
        'morphTo' => MorphToBuilder::class,
//        'morphToMany' => MorphToManyBuilder::class,
    ]
];
