<?php

namespace Akceli\Generators\DefaultGenerators;

use Akceli\Generators\AkceliGenerator;

use Akceli\Akceli;
use Akceli\Console;

class DefaultObserverGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return false;
    }

    public function dataPrompter(): array
    {
        return [
            "Observer" => function(array $data) {
                return $data['arg1'] ?? Console::ask('What is the name of the Observer?');
            },
            "Model" => function(array $data) {
                return $data['arg2'] ?? Console::ask('What is the name of the Model you want to observe?');
            },
        ];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::fileTemplate('observer', 'app/Observers/[[Observer]]Observer.php'),
        ];
    }

    public function inlineTemplates(array $data): array
    {
        return [
            Akceli::insertInline('app/Providers/AppServiceProvider.php', '/** register observers here */', '[[Model]]::observe([[Observer]]Observer::class);'),
            Akceli::insertInline('app/Providers/AppServiceProvider.php', '/** Auto Import */', 'use App\Models\[[Model]];'),
            Akceli::insertInline('app/Providers/AppServiceProvider.php', '/** Auto Import */', 'use App\Observers\[[Observer]]Observer;'),
        ];
    }

    public function completionMessage(array $data)
    {
        Console::info('Success');
    }
}
