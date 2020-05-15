<?php

namespace Akceli;

use Akceli\Akceli;
use Akceli\Console;
use Akceli\Generators\AkceliGenerator;
use Illuminate\Support\Str;

class DefaultNewAkceliGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return false;
    }

    public function dataPrompter(): array
    {
        return [
            'Generator' => function(array $data) {
                return $data['arg1'] ?? Console::ask('What is the name of the new Generator?', 'ExampleGenerator');
            },
            'Command' => function (array $data) {
                return $data['arg2'] ?? Console::ask('What is the command you want to user for the Generator?', 'example');
            }
        ];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::fileTemplate('akceli_generator', 'akceli/generators/[[Generator]].php'),
        ];
    }

    public function inlineTemplates(array $data): array
    {
        return [
            Akceli::insertInline(
                'config/akceli.php',
                '/** New Generators Get Inserted Here */',
                "'[[Command]]' => [[Generator]]::class,"
            ),
            Akceli::insertInline(
                'config/akceli.php',
                '/** auto import new commands */',
                'use Akceli\Generators\[[Generator]];'
            )
        ];
    }

    public function completionMessage(array $data)
    {
        Console::info('Success');
    }
}
