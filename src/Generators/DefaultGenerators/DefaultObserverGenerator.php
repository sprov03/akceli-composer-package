<?php

namespace Akceli\Generators\DefaultGenerators;

use Akceli\AkceliFileModifier;
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
                return $data['arg1'] ?? Console::ask('What is the name of the Observer?', 'ExampleObserver');
            },
            "Model" => function(array $data) {
                return $data['arg2'] ?? Console::ask('What is the name of the Model you want to observe?', 'Example');
            },
        ];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::fileTemplate('observer', 'app/Observers/[[Observer]].php'),
        ];
    }

    public function fileModifiers(array $data): array
    {
        return [
            AkceliFileModifier::phpFile(app_path('Providers/AppServiceProvider.php'))
                ->addUseStatementToFile("App\Models\\{$data['Model']}")
                ->addUseStatementToFile("App\Observers\\{$data['Observer']}")
                ->addToTopOfMethod('boot', "{$data['Model']}::observe({$data['Observer']}::class);"),
        ];
    }

    public function completionMessage(array $data)
    {
        Console::alert('Register the Observers in the boot method.');
        Console::info('Documentation: https://laravel.com/docs/6.x/eloquent#observers');
    }
}
