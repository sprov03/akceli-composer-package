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
        ];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::fileTemplate('model_factory', 'database/factories/[[ModelName]]Factory.php'),
        ];
    }

    public function inlineTemplates(array $data): array
    {
        return [
            // Akceli::inlineTemplate('template_name', 'destination_path', 'identifier string')
        ];
    }

    public function completionMessage(array $data)
    {
        Console::info('Success');
    }
}
