<?php

namespace Akceli\Generators\DefaultGenerators;

use Akceli\Generators\AkceliGenerator;

use Akceli\Akceli;
use Akceli\Console;

class DefaultMiddlewareGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return false;
    }

    public function dataPrompter(): array
    {
        return [
            'Middleware' => function (array $data) {
                return $data['arg1'] ?? Console::ask('What is the name of the Middleware?', 'ExampleMiddleware');
            }
        ];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::template('middleware', 'app/Http/Middleware/[[Middleware]].php'),
            Akceli::template('middleware_test', 'tests/Http/Middleware/[[Middleware]]Test.php'),
        ];
    }

    public function fileModifiers(array $data): array
    {
        return [];
    }

    public function completionMessage(array $data)
    {
        Console::info('Documentation: https://laravel.com/docs/6.x/middleware#defining-middleware');
    }
}
