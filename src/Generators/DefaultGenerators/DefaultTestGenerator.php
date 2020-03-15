<?php

namespace Akceli\Generators\DefaultGenerators;

use Akceli\Akceli;
use Akceli\Console;
use Akceli\Generators\AkceliGenerator;

class DefaultTestGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return false;
    }

    public function dataPrompter(): array
    {
        return [
            'Test' => function (array $data) {
                return $data['arg1'] ?? Console::ask('What is the name of the Test you want to create?', 'ExampleTest');
            },
        ];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::fileTemplate('test', 'tests/Feature/[[Test]].php'),
        ];
    }

    public function inlineTemplates(array $data): array
    {
        return [];
    }

    public function completionMessage(array $data)
    {
        Console::info('Success');
    }
}
