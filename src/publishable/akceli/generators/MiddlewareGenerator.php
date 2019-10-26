<?php

namespace Akceli\Generators;

use Akceli\Akceli;
use Akceli\Console;

class MiddlewareGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return false;
    }

    public function dataPrompter(): array
    {
        return [
            'Middleware' => function() {
                return Console::ask('What is the name of the Middleware?');
            }
        ];
    }

    public function templates(): array
    {
        return [
            Akceli::fileTemplate('middleware', 'app/Http/Middleware/[[Middleware]]Middleware.php'),
            Akceli::fileTemplate('middleware_test', 'tests/Http/Middleware/[[Middleware]]MiddlewareTest.php'),
        ];
    }

    public function inlineTemplates(): array
    {
        return [
            // Akceli::inlineTemplate('template_name', 'destination_path', 'identifier string')
        ];
    }

    public function completionMessage(): void
    {
        Console::info('Success');
    }
}
