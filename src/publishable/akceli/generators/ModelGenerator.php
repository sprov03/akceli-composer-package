<?php

namespace Akceli\Generators;

use Akceli\Akceli;
use Akceli\Console;

class ModelGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return true;
    }

    public function dataPrompter(): array
    {
        return [
        ];
    }

    public function templates(): array
    {
        return [
            Akceli::fileTemplate('model', 'app/Models/[[ModelName]].php'),
            Akceli::fileTemplate('model_test', 'tests/Models/[[ModelName]]Test.php'),
            Akceli::fileTemplate('model_factory', 'database/factories/[[ModelName]]Factory.php'),
        ];
    }

    public function inlineTemplates(): array
    {
        return [
            // Akceli::inlineTemplate('template_name', 'destination_path', 'identifier string')
        ];
    }

    public function completionMessage(): void
    {
        Console::info('Success');
    }
}
