<?php

namespace Akceli\Generators\DefaultGenerators;

use Akceli\Console;
use Akceli\Schema\SchemaFactory;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Akceli\Generators\AkceliGenerator;

class DefaultAllGenerator extends AkceliGenerator
{
    private $blackList = [
        'failed_jobs',
        'migrations',
        'password_resets',
    ];

    public function requiresTable(): bool
    {
        return false;
    }

    public function dataPrompter(): array
    {
        return [];
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
        Console::info('Only Generators that require Database tables are available for this command');
        Console::info('This command can take up to 2 seconds per model');

        $tables = DB::select('SHOW TABLES');
        $tables = array_filter($tables, function ($table) {
            return !in_array($table->Tables_in_demo, $this->blackList);
        });


        $generator = $data['arg1'];
        $generators = config('akceli.generators');
        $generators = array_filter($generators, function ($generator) {
            return (new $generator())->requiresTable();
        });
        $generators = array_keys($generators);

        if (is_null($generator)) {
            $generator = Console::anticipate('What template set do you want to use? (Press enter to see list of options)', $generators);
        }

        if (is_null($generator)) {
            $generator = Console::choice('What template set do you want to use?', $generators);
        }

        foreach ($tables as $table) {
            $schema = SchemaFactory::resolve($table->Tables_in_demo);

            if ($schema->getBelongsToManyRelationships()->count() === 2) {
                Artisan::call("akceli:relationships {$table->Tables_in_demo}");
                /** Dont generate a Many to Many Pivot table */
                continue;
            }

            Artisan::call("akceli:generate {$generator} {$table->Tables_in_demo}");
        }

        Console::info('Success');
    }
}
