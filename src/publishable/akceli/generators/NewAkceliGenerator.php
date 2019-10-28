<?php

namespace Akceli\Generators;

use Akceli\Akceli;
use Akceli\Console;
use Akceli\GeneratorService;
use Illuminate\Support\Str;

class NewAkceliGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return false;
    }

    public function dataPrompter(): array
    {
        return [
            'GeneratorName' => function() {
                return Console::ask('What is the name of the new Generator?');
            },
        ];
    }

    public function templates(): array
    {
        return [
            Akceli::fileTemplate('akceli_generator', 'akceli/generators/[[GeneratorName]]Generator.php'),
        ];
    }

    public function inlineTemplates(): array
    {
        $command = Str::snake(GeneratorService::getData()['GeneratorName']);
        return [
            Akceli::insertInline(
                'config/akceli.php',
                '        /** New Generators Get Inserted Here */',
                "        '{$command}' => [[GeneratorName]]Generator::class,"
            ),
            Akceli::insertInline(
                'config/akceli.php',
                '/** auto import new commands */',
                'use Akceli\Generators\[[GeneratorName]]Generator;'
            )
        ];
    }

    public function completionMessage()
    {
        Console::info('You have successfully created the new Akceli Migration');
    }
}
