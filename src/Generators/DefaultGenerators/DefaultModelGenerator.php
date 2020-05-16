<?php

namespace Akceli\Generators\DefaultGenerators;

use Akceli\Generators\AkceliGenerator;

use Akceli\Akceli;
use Akceli\Console;
use Illuminate\Support\Facades\Artisan;

class DefaultModelGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return true;
    }

    public function dataPrompter(): array
    {
        return [
            "Factory" => function (array $data) {
                return $data['ModelName'] . 'Factory';
            }
        ];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::fileTemplate('models/model', 'app/Models/[[ModelName]].php'),
            Akceli::fileTemplate('models/test', 'tests/Models/[[ModelName]]Test.php'),
            Akceli::fileTemplate('models/factory', 'database/factories/[[ModelName]]Factory.php'),
            Akceli::fileTemplate('models/service', 'app/Models/Services/[[ModelName]]Service.php'),
        ];
    }

    public function fileModifiers(array $data): array
    {
        return [];
    }

    public function completionMessage(array $data)
    {
        Artisan::call('akceli:relationships '.$data['table_name'].' --no-interaction');
        Console::info('Success');
    }
}
