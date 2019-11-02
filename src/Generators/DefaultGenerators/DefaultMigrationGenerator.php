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
            'table_name' => function() {
                return Console::ask('What is the name of the table being used in the migration?');
            },
            'migration_timestamp' => function() {
                return now()->format('Y_m_d_His');
            },
            'migration_type' => function() {
                return Console::choice('Is this a create or update migration?', ['create', 'update'], 'create');
            },
            'migration_name' => function($data) {
                if ($data['migration_type'] === 'update') {
                    $name = Console::ask('What is the name of the migration?');
                } else {
                    $name = 'create_' . $data['table_name'] . '_table';
                }
                return Str::snake(str_replace(' ', '_', $name));
            },
        ];
    }

    public function templates(): array
    {
        return [
            Akceli::fileTemplate('migration', 'database/migrations/[[migration_timestamp]]_[[migration_name]].php')
        ];
    }

    public function inlineTemplates(array $data): array
    {
        return [
            // Akceli::inlineTemplate('template_name', 'destination_path', 'identifier string')
        ];
    }

    public function completionMessage(array $data)
    {
        Console::info('Success');
    }
}
