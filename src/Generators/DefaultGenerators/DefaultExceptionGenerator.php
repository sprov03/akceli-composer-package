<?php

namespace Akceli\Generators\DefaultGenerators;

use Akceli\AkceliFileModifier;
use Akceli\Generators\AkceliGenerator;

use Akceli\Akceli;
use Akceli\Console;

class DefaultExceptionGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return false;
    }

    public function dataPrompter(): array
    {
        return [
            'Exception' => function (array $data) {
                return $data['arg1'] ?? Console::ask('What is the name of the Exception?', 'ExampleException');
            }
        ];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::template('exception', 'app/Exceptions/[[Exception]].php')
        ];
    }

    public function fileModifiers(array $data): array
    {
        return [];
    }

    public function completionMessage(array $data)
    {
        Console::info('Documentation: https://laravel.com/docs/6.x/errors#renderable-exceptions');
    }
}
