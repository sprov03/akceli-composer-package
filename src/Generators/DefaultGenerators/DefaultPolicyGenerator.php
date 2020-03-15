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
        return [
            "Policy" => function (array $data) {
                return $data['arg2'] ?: Console::ask('What is the name of the Policy?', $data['ModelName'] . 'Policy');
            }
        ];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::fileTemplate('policy', 'app/Policies/[[Policy]].php'),
        ];
    }

    public function inlineTemplates(array $data): array
    {
        return [
            Akceli::insertInline('app/Providers/AuthServiceProvider.php', '/** Register Policies Here */', '[[ModelName]]::class => [[Policy]]::class,'),
            Akceli::insertInline('app/Providers/AuthServiceProvider.php', '/** Auto Import */', 'use App\\Models\\[[ModelName]];'),
            Akceli::insertInline('app/Providers/AuthServiceProvider.php', '/** Auto Import */', 'use App\\Policies\\[[Policy]];'),
        ];
    }

    public function completionMessage(array $data)
    {
        Console::info('Documentation: https://laravel.com/docs/6.x/authorization');
    }
}
