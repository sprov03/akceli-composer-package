<?php

namespace Akceli;

use Akceli\Akceli;
use Akceli\Console;
use Akceli\Generators\AkceliGenerator;
use Akceli\Schema\ColumnInterface;
use Akceli\Schema\Columns\Column;
use Akceli\Schema\Relationships\AkceliRelationship;
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
            return [
                Akceli::fileTemplate('schema-migration', 'database/migrations/[[migration_timestamp]]_[[migration_name]].php')
            ];
        }
        if (count($data['removedColumns'])) {
            return [
                Akceli::fileTemplate('schema-migration', 'database/migrations/[[migration_timestamp]]_[[migration_name]].php')
            ];
        }

        Console::info('There are no changes to the ' . $data['ModelName']);
        return [];
    }

    public function fileModifiers(array $data): array
    {
        $modelModifier = AkceliFileModifier::phpFile(app_path(config('akceli.model_directory') . '/' . $data['ModelName'] . '.php'));
        
        /** @var Column $column */
        foreach($data['newColumns']->reverse() as $column) {
//        foreach($data['schemaColumns']->reverse() as $column) {
            $modelModifier->addClassPropertyDocToFile($column->data_type,  $column->column_name, 'Database Fields');
        }
        /** @var ColumnInterface $column */
        foreach($data['removedColumns']->reverse() as $column) {
            // Todo: add this method
            // $modelModifier->removeClassPropertyDocFromFile($column->data_type,  $column->column_name);
        }
        /** @var AkceliRelationship $relationship */
        foreach($data['schemaRelationships']->reverse() as $relationship) {
            $relationship->addToModel($modelModifier);
        }

        return [
            $modelModifier,
        ];
    }

    public function completionMessage(array $data)
    {
        Console::info('Documentation: https://laravel.com/docs/6.x/migrations#creating-columns');
    }
}
