<?php

namespace Akceli\Generators\DefaultGenerators;

use Akceli\AkceliFileModifier;
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
            Akceli::fileTemplate('api_with_service/client_apis', "resources/client/src/api/[[modelNames]].js"),
            Akceli::fileTemplate('api_with_service/client_form', "resources/client/src/forms/[[ModelName]]Form.vue"),
        ];
    }

    public function fileModifiers(array $data): array
    {
        return [
            AkceliFileModifier::phpFile(base_path('routes/api.php'))
                ->addLineAbove('Route::middleware([\'auth:sanctum\'])->group(function () {', "{$data['ModelName']}Controller::apiRoutes();")
                ->addUseStatementToFile("App\Http\Controllers\Api\\{$data['ModelName']}Controller"),
        ];
    }

    public function completionMessage(array $data)
    {
        Console::info('Success');
    }
}