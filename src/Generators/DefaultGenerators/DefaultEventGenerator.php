<?php

namespace Akceli\Generators\DefaultGenerators;

use Akceli\Generators\AkceliGenerator;

use Akceli\Akceli;
use Akceli\Console;

class DefaultEventGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return false;
    }

    public function dataPrompter(): array
    {
        return [
            'Event' => function (array $data) {
                return $data['arg1'] ?? Console::ask('What is the name of the Event you want to create?', 'ExampleEvent');
            },
        ];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::template('event', 'app/Events/[[Event]].php'),
            Akceli::template('event_test', 'tests/Events/[[Event]]Test.php')
        ];
    }

    public function fileModifiers(array $data): array
    {
        return [];
    }

    public function completionMessage(array $data)
    {
        Console::alert('Dont forget to register the Event in app/Providers/EventServiceProvider.php');
        Console::info('Documentation: https://laravel.com/docs/6.x/events#registering-events-and-listeners');
    }
}
