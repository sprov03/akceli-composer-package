<?php

namespace Akceli\Generators\DefaultGenerators;

use Akceli\Generators\AkceliGenerator;

use Akceli\Akceli;
use Akceli\Console;

class DefaultPolicyGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return true;
    }

    public function dataPrompter(): array
    {
        return [];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::fileTemplate('policy', 'app/Policies/[[ModelName]]Policy.php'),
        ];
    }

    public function inlineTemplates(array $data): array
    {
        return [
            Akceli::insertInline('app/Providers/AuthServiceProvider.php', '/** Register Policies Here */', '[[ModelName]]::class => [[ModelName]]Policy::class,'),
            Akceli::insertInline('app/Providers/AuthServiceProvider.php', '/** Auto Import */', 'use App\\Models\\[[ModelName]];'),
            Akceli::insertInline('app/Providers/AuthServiceProvider.php', '/** Auto Import */', 'use App\\Policies\\[[ModelName]]Policy;'),
        ];
    }

    public function completionMessage(array $data)
    {
        Console::info('Success');
    }
}
