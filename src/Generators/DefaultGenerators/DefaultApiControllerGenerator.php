<?php

namespace Akceli\Generators\DefaultGenerators;

use Akceli\Generators\AkceliGenerator;

use Akceli\Akceli;
use Akceli\Console;

class DefaultApiControllerGenerator extends AkceliGenerator
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
            Akceli::fileTemplate('api_with_service/controller', "app/Http/Controllers/Api/[[ModelName]]Controller.php"),
            Akceli::fileTemplate('api_with_service/controller_test', "tests/Http/Controllers/Api/[[ModelName]]ControllerTest.php"),
            Akceli::fileTemplate('api_with_service/form_request_create', "app/Http/Requests/Create[[ModelName]]Request.php"),
            Akceli::fileTemplate('api_with_service/form_request_update', "app/Http/Requests/Update[[ModelName]]Request.php"),
            Akceli::fileTemplate('resource/model_resource', "app/Resources/[[ModelName]]Resource.php"),
            Akceli::fileTemplate('api_with_service/client_apis', "../gittask-client/src/api/[[modelNames]].js"),
            Akceli::fileTemplate('api_with_service/client_form', "../gittask-client/src/forms/[[ModelName]]Form.vue"),
        ];
    }

    public function inlineTemplates(array $data): array
    {
        return [
            Akceli::insertInline('routes/api.php', '/** Import Api routes */', "[[ModelName]]Controller::apiRoutes();"),
            Akceli::insertInline('routes/api.php', '/** Auto Import */', "use App\Http\Controllers\Api\[[ModelName]]Controller;"),
            Akceli::insertInline('../gittask-client/src/api/index.js', '/** Auto Import */', "import * as [[modelNames]] from './[[modelNames]]'"),
            Akceli::insertInline('../gittask-client/src/api/index.js', 'auth,', "[[modelNames]],"),
        ];
    }

    public function completionMessage(array $data)
    {
        Console::info('Success');
    }
}
