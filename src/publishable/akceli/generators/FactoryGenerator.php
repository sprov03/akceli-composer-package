<?php

namespace Akceli\Generators;

use Akceli\Akceli;
use Akceli\Console;

class FactoryGenerator extends AkceliGenerator
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
            Akceli::fileTemplate('model_factory', 'database/factories/[[ModelName]]Factory.php'),
        ];
    }

    public function inlineTemplates(): array
    {
        return [
            // Akceli::inlineTemplate('template_name', 'destination_path', 'identifier string')
        ];
    }

    public function completionMessage()
    {
        Console::info('Success');
    }
}
