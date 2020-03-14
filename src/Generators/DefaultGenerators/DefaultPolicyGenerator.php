<?php

namespace Akceli\Generators\DefaultGenerators;

use Akceli\Generators\AkceliGenerator;

use Akceli\Akceli;
use Akceli\Console;

class DefaultPolicyGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return false;
    }

    public function dataPrompter(): array
    {
        return [
            "Model" => function(array $data) {
                return $data['arg1'] ?? Console::ask('What is the name of the Model you want to make a Policy for?');
            },
        ];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::fileTemplate('policy', 'app/Policies/[[Model]]Policy.php'),
        ];
    }

    public function inlineTemplates(array $data): array
    {
        return [
            Akceli::insertInline('app/Providers/AuthServiceProvider.php', '/** Register Policies Here */', '[[Model]]::class => [[Model]]Policy::class,'),
            Akceli::insertInline('app/Providers/AuthServiceProvider.php', '/** Auto Import */', 'use App\\Models\\[[Model]];'),
            Akceli::insertInline('app/Providers/AuthServiceProvider.php', '/** Auto Import */', 'use App\\Policies\\[[Model]]Policy;'),
        ];
    }

    public function completionMessage(array $data)
    {
        Console::info('Success');
    }
}
