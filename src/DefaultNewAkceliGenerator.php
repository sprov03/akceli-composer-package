<?php

namespace Akceli\Generators\DefaultGenerators;

use Akceli\Generators\AkceliGenerator;

use Akceli\Akceli;
use Akceli\Console;
use Akceli\GeneratorService;
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
        $command = Str::snake(GeneratorService::getData()['GeneratorName']);
        return [
            Akceli::insertInline(
                'config/akceli.php',
                '/** New Generators Get Inserted Here */',
                "'{$command}' => [[Generator]]::class,"
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
        Console::info('You have successfully created the new Akceli Migration');
    }
}
