<?php

namespace Akceli\Generators\DefaultGenerators;

use Akceli\AkceliFileModifier;
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
            "Controller" => function (array $data) {
                return $data['arg2'] ?: Console::ask('What is the name of the Controller?', $data['ModelName'] . 'Controller');
            }
        ];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::fileTemplate('controller', 'app/Http/Controllers/[[Controller]].php'),
            Akceli::fileTemplate('controller_test', 'tests/Http/Controllers/[[Controller]]Test.php'),
            Akceli::fileTemplate('form_request_store', 'app/Http/Requests/Store[[ModelName]]Request.php'),
            Akceli::fileTemplate('form_request_update', 'app/Http/Requests/Update[[ModelName]]Request.php'),
            Akceli::fileTemplate('views_create_page', 'resources/views/models/[[modelNames]]/create.blade.php'),
            Akceli::fileTemplate('views_create_page', 'resources/views/models/[[modelNames]]/show.blade.php'),
            Akceli::fileTemplate('views_edit_page', 'resources/views/models/[[modelNames]]/edit.blade.php'),
            Akceli::fileTemplate('views_index_page', 'resources/views/models/[[modelNames]]/index.blade.php'),
        ];
    }

    public function fileModifiers(array $data): array
    {
        return [
            AkceliFileModifier::phpFile(base_path('routes/web.php'))
                ->addLineAbove('Route::resource', "Route::resource('{$data['model_names']}', '{$data['Controller']}'")
        ];
    }

    public function completionMessage(array $data)
    {
        Console::info('Documentation: https://laravel.com/docs/6.x/controllers#introduction');
    }
}
