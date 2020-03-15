<?php

namespace Akceli\Generators\DefaultGenerators;

use Akceli\Generators\AkceliGenerator;

use Akceli\Akceli;
use Akceli\Console;
use Illuminate\Support\Str;

class DefaultMigrationGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return false;
    }

    public function dataPrompter(): array
    {
        return [
            'migration_timestamp' => function (array $data) {
                return now()->format('Y_m_d_His');
            },
            'migration_type' => function (array $data) {
                return $data['arg1'] ?? Console::choice('Is this a create or update migration?', ['create', 'update'], 'create');
            },
            'table_name' => function (array $data) {
                return $data['arg2'] ?? Console::ask('What is the name of the table being used in the migration?');
            },
            'migration_name' => function($data) {
                if ($data['migration_type'] === 'update') {
                    $name = Console::ask('Describe the migration?', 'adding some data to the example table');
                } else {
                    $name = 'create_' . $data['table_name'] . '_table';
                }
                return Str::snake(str_replace(' ', '_', $name));
            },
        ];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::fileTemplate('migration', 'database/migrations/[[migration_timestamp]]_[[migration_name]].php')
        ];
    }

    public function inlineTemplates(array $data): array
    {
        return [
        ];
    }

    public function completionMessage(array $data)
    {
        Console::info('Success');
    }
}
