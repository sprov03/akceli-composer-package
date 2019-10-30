<?php

namespace Akceli\Generators\DefaultGenerators;

use Akceli\Generators\AkceliGenerator;

use Akceli\Akceli;
use Akceli\Console;

class DefaultCommandGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return false;
    }

    public function dataPrompter(): array
    {
        return [
            'Command' => function() {
                return Console::ask('What is the name of the Command?');
            },
            'Signature' => function() {
                return Console::ask('What is the signature for the command?');
            }
        ];
    }

    public function templates(): array
    {
        return [
            Akceli::fileTemplate('command', 'app/Console/Commands/[[Command]]Command.php'),
            Akceli::fileTemplate('command_test', 'tests/Console/Commands/[[Command]]CommandTest.php'),
        ];
    }

    public function inlineTemplates(): array
    {
        return [
            // Akceli::inlineTemplate('template_name', 'destination_path', 'identifier string')
        ];
    }

    public function completionMessage()
    {
        Console::info('Success');
    }
}
