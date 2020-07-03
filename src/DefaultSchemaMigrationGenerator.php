<?php

namespace Akceli;

use Akceli\Akceli;
use Akceli\Console;
use Akceli\Generators\AkceliGenerator;
use Illuminate\Support\Str;

class DefaultSchemaMigrationGenerator extends AkceliGenerator
{
    public bool $requiresModel = true;

    public function dataPrompter(): array
    {
        return [
            'migration_timestamp' => function (array $data) {
                return now()->format('Y_m_d_His');
            },
            'schema_type' => function (array $data) {
                return count($data['databaseColumns']) ? 'table' : 'create';
            },
            'migration_name' => function($data) {
                if ($data['arg2'] ?? false) {
                    return str::snake(str_replace(' ', '_', $data['arg2']));
                }

                if ($data['schema_type'] === 'table') {
                    $name = Console::ask('Describe the migration?', 'adding some data to the example table');
                } else {
                    $name = 'create_' . $data['table_name'] . '_table';
                }

                return str::snake(str_replace(' ', '_', $name));
            },
        ];
    }

    public function templates(array $data): array
    {
        if (count($data['newColumns'])) {
            /**
             * only generate a new migration if there are new columns
             */
            return [
                Akceli::fileTemplate('schema-migration', 'database/migrations/[[migration_timestamp]]_[[migration_name]].php')
            ];
        }

        Console::info('There are no changes to the ' . $data['ModelName']);
        return [];
    }

    public function fileModifiers(array $data): array
    {
        return [];
    }

    public function completionMessage(array $data)
    {
        Console::info('Documentation: https://laravel.com/docs/6.x/migrations#creating-columns');
    }
}
