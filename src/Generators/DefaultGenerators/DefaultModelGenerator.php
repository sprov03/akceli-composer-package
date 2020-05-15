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
            Akceli::template('model', 'app/Models/[[ModelName]].php'),
            Akceli::template('model_test', 'tests/Models/[[ModelName]]Test.php'),
            Akceli::template('model_factory', 'database/factories/[[Factory]].php'),
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
