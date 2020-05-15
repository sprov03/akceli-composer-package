<?php

namespace Akceli\Generators\DefaultGenerators;

use Akceli\AkceliFileModifier;
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
            Akceli::template('policy', 'app/Policies/[[Policy]].php'),
        ];
    }

    public function fileModifiers(array $data): array
    {
        return [
            AkceliFileModifier::phpFile(app_path('Providers/AuthServiceProvider.php'))
                ->addUseStatementToFile("App\Models\\{$data['ModelName']}")
                ->addUseStatementToFile("App\Policies\\{$data['Policy']}")
                ->addToTopOfMethod('boot', "{$data['ModelName']}::class => {$data['Policy']}::class,"),
        ];
    }

    public function completionMessage(array $data)
    {
        Console::info('Documentation: https://laravel.com/docs/6.x/authorization');
    }
}
