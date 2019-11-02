<?php

namespace Akceli\Generators\DefaultGenerators;

use Akceli\Generators\AkceliGenerator;

use Akceli\Akceli;
use Akceli\Console;

class DefaultControllerGenerator extends AkceliGenerator
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

    public function templates(): array
    {
        return [
            Akceli::fileTemplate('controller', 'app/Http/Controllers/[[ModelName]]Controller.php'),
            Akceli::fileTemplate('controller_test', 'tests/Http/Controllers/Api/[[ModelName]]ControllerTest.php'),
            Akceli::fileTemplate('store_model_request', 'app/Http/Requests/Store[[ModelName]]Request.php'),
            Akceli::fileTemplate('update_model_request', 'app/Http/Requests/Update[[ModelName]]Request.php'),
            Akceli::fileTemplate('views_create_page', 'resources/views/models/[[modelNames]]/create.blade.php'),
            Akceli::fileTemplate('views_create_page', 'resources/views/models/[[modelNames]]/show.blade.php'),
            Akceli::fileTemplate('views_edit_page', 'resources/views/models/[[modelNames]]/edit.blade.php'),
            Akceli::fileTemplate('views_index_page', 'resources/views/models/[[modelNames]]/index.blade.php'),
        ];
    }

    public function inlineTemplates(array $data): array
    {
        return [
            Akceli::inlineTemplate('route_resource', 'routes/web.php', '/** All Web controllers will go here */'),
        ];
    }

    public function completionMessage(array $data)
    {
        Console::info('Success');
    }
}
