<?php

namespace Akceli\Generators;

use Akceli\Akceli;
use Akceli\Console;

class ApiControllerGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return true;
    }

    public function dataPrompter(): array
    {
        return [];
    }

    public function templates(): array
    {
        return [
            Akceli::fileTemplate('api_controller', 'app/Http/Controllers/Api/[[ModelName]]Controller.php'),
            Akceli::fileTemplate('api_controller_test', 'tests/Http/Controllers/Api/[[ModelName]]ControllerTest.php'),
            Akceli::fileTemplate('create_model_request', 'app/Http/Requests/Store[[ModelName]]Request.php'),
            Akceli::fileTemplate('patch_model_request', 'app/Http/Requests/Update[[ModelName]]Request.php"'),
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
