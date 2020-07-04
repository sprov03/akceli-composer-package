<?php

namespace Akceli;

use Akceli\Akceli;
use Akceli\Console;
use Akceli\Generators\AkceliGenerator;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;

class DefaultSchemaSyncGenerator extends AkceliGenerator
{
    public function dataPrompter(): array
    {
        return [
            'migration_name' => function (array $data) {
                return $data['arg1'] ?? Console::ask('What do you wan to call these migrations', 'Auto Generated Migration');
            }
        ];
    }

    public function templates(array $data): array
    {
        return [];
    }

    public function inlineTemplates(array $data): array
    {
        return [];
    }

    public function completionMessage(array $data)
    {
        /** Get a collection of all the models defined in the Models directory. */
        FileService::setRootDirectory(app_path(config('akceli.model_directory')));
        $modelsFiles = FileService::getFilesThatUseTrait(config('akceli.akceliSchemaTrait'));

        /** for each model run the schema-migration command. */
        foreach ($modelsFiles as $index => $modelsFile) {
            $modelName = $modelsFile->getBasename('.php');
            Artisan::call('akceli:generate schema-migration ' . $modelName . ' \'' . $data['migration_name'] . '_' . $index .  '\' --no-interaction');
//            Artisan::call('akceli:generate schema-migration ' . $modelName . ' \'' . $data['migration_name'] . '_' . $index .  '\'');
        }

        /** prompt for the option to migrate when complete, default to no */
        $shouldMigrate = Console::choice('Do you want to run migrate the changes?', ['yes', 'no'], 'no');
        if ($shouldMigrate === 'yes') {
            Artisan::call('migrate');
        }

        Console::info('Success');
    }
}
