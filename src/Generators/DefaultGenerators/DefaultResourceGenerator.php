<?php

namespace Akceli\Generators\DefaultGenerators;

use Akceli\Generators\AkceliGenerator;

use Akceli\Akceli;
use Akceli\Console;

class DefaultResourceGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return true;
    }

    public function dataPrompter(): array
    {
        return [
            "Resource" => function (array $data) {
                return $data['arg2'] ?: Console::ask('What is the name of the Resource?', $data['ModelName'] . 'Resource');
            }
        ];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::template('resource', 'app/Http/Resources/[[Resource]].php'),
        ];
    }

    public function fileModifiers(array $data): array
    {
        return [];
    }

    public function completionMessage(array $data)
    {
        Console::info('Success');
    }
}
