<?php

namespace Akceli\Generators\DefaultGenerators;

use Akceli\Generators\AkceliGenerator;

use Akceli\Akceli;
use Akceli\Console;

class DefaultRuleGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return false;
    }

    public function dataPrompter(): array
    {
        return [
            "Rule" => function (array $data) {
                return $data['arg1'] ?: Console::ask('What is the name of the Rule?', 'ExampleRule');
            }
        ];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::fileTemplate('rule', 'app/Rules/[[Rule]].php'),
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
