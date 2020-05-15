<?php

namespace Akceli\Generators\DefaultGenerators;

use Akceli\Generators\AkceliGenerator;

use Akceli\Akceli;
use Akceli\Console;
use Illuminate\Support\Str;

class DefaultRequestGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        $choice = Console::choice('Is this going to be based off of a model?', ['yes', 'no'], 'yes');

        return $choice === 'yes';
    }

    public function dataPrompter(): array
    {
        return [
            "Request" => function (array $data) {
                $request = (isset($data['table_name'])) ? $data['arg2'] : $data['arg1'];
                $example = (isset($data['table_name'])) ? Str::studly($data['table_name']) : 'Example';

                return $request ?? Console::ask('What is the name of the Request?', $example . 'Request');
            }
        ];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::fileTemplate('form_request', "app/Http/Requests/[[Request]].php"),
        ];
    }

    public function fileModifiers(array $data): array
    {
        return [];
    }

    public function completionMessage(array $data)
    {
        Console::info('Documentation: https://laravel.com/docs/6.x/validation#available-validation-rules');
    }
}
