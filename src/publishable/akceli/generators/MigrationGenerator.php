<?php

namespace Akceli\Generators;

use Akceli\Akceli;
use Akceli\Console;
use Illuminate\Support\Str;

class MigrationGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return false;
    }

    public function dataPrompter(): array
    {
        return [
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
        ];
    }

    public function templates(): array
    {
        return [
            Akceli::fileTemplate('migration', 'database/migrations/[[migration_timestamp]]_[[migration_name]].php')
        ];
    }

    public function inlineTemplates(): array
    {
        return [
            // Akceli::inlineTemplate('template_name', 'destination_path', 'identifier string')
        ];
    }

    public function completionMessage(): void
    {
        Console::info('Success');
    }
}
