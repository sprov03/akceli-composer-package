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
        'users',
    ];

    public function requiresTable(): bool
    {
        return false;
    }

    public function dataPrompter(): array
    {
        return [
            'generator' => function (array $data) {
                Console::info('Only Generators that require Database tables are available for this command');
                
                if ($generator = $data['arg1'] ?? null) {
                    return $generator;
                }

                $generators = config('akceli.generators');
                $generators = array_filter($generators, function ($generator) {
                    return (new $generator())->requiresTable();
                });
                $generators = array_keys($generators);
                $generator = Console::anticipate('What template set do you want to use? (Press enter to see list of options)', $generators);

                if (is_null($generator)) {
                    $generator = Console::choice('What template set do you want to use?', $generators);
                }
                
                return $generator;
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
        $tables = DB::select('SHOW TABLES');
        $tables = array_filter($tables, function ($table) {
            return !in_array($table->Tables_in_demo, $this->blackList);
        });

        $generator = $data['arg1'] ?? 'model';

        foreach ($tables as $table) {
            $schema = SchemaFactory::resolve($table->Tables_in_demo);

            if ($schema->getBelongsToManyRelationships()->count() === 2) {
                /** Dont generate a Many to Many Pivot table */
                continue;
            }

            Artisan::call("akceli:generate {$generator} {$table->Tables_in_demo}");
        }

        Console::info('Success');
    }
}
