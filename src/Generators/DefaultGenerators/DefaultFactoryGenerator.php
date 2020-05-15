<?php

namespace Akceli\Generators\DefaultGenerators;

use Akceli\Generators\AkceliGenerator;

use Akceli\Akceli;
use Akceli\Console;

class DefaultFactoryGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return true;
    }

    public function dataPrompter(): array
    {
        return [
            "Factory" => function (array $data) {
                return $data['arg2'] ?: Console::ask('What is the name of the Factory?', $data['ModelName'] . 'Factory');
            }
        ];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::template('model_factory', 'database/factories/[[Factory]].php'),
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
