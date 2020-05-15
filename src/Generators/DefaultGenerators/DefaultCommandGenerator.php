<?php

namespace Akceli\Generators\DefaultGenerators;

use Akceli\Generators\AkceliGenerator;

use Akceli\Akceli;
use Akceli\Console;
use Illuminate\Support\Str;

class DefaultCommandGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return false;
    }

    public function dataPrompter(): array
    {
        return [
            'Command' => function (array $data) {
                return $data['arg1'] ?? Console::ask('What is the name of the Command?', 'ExampleCommand');
            },
            'Signature' => function (array $data) {
                $command = Str::kebab($data['Command']);
                return $data['arg2'] ?? Console::ask('What is the signature for the command?', 'acme:' . $command);
            }
        ];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::fileTemplate('command', 'app/Console/Commands/[[Command]].php'),
            Akceli::fileTemplate('command_test', 'tests/Console/Commands/[[Command]]Test.php'),
        ];
    }

    public function fileModifiers(array $data): array
    {
        return [];
    }

    public function completionMessage(array $data)
    {
        Console::info('Documentation: https://laravel.com/docs/6.x/artisan#writing-commands');
    }
}
