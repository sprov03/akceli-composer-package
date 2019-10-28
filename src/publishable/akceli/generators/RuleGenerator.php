<?php

namespace Akceli\Generators;

use Akceli\Akceli;
use Akceli\Console;

class RuleGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return false;
    }

    public function dataPrompter(): array
    {
        return [];
    }

    public function templates(): array
    {
        return [
            // Akceli::fileTemplate('akceli_generator', 'akceli/generators/RuleGenerator.php'),
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
