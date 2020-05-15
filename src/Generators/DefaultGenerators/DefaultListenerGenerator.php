<?php

namespace Akceli\Generators\DefaultGenerators;

use Akceli\Generators\AkceliGenerator;

use Akceli\Akceli;
use Akceli\Console;

class DefaultListenerGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return false;
    }

    public function dataPrompter(): array
    {
        return [
            'Listener' => function (array $data) {
                return $data['arg1'] ?? Console::ask('What is the name of the Listener?', 'ExampleListener');
            },
            'Event' => function (array $data) {
                $event = str_replace('Listener', 'Event', $data['Listener']);
                return $data['arg2'] ?? Console::ask('What is the name of the Event you are listening to??', $event);
            },
        ];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::fileTemplate('listener', 'app/Listeners/[[Listener]].php'),
            Akceli::fileTemplate('listener_test', 'tests/Listeners/[[Listener]]Test.php'),
        ];
    }

    public function fileModifiers(array $data): array
    {
        return [];
    }

    public function completionMessage(array $data)
    {
        Console::alert('Dont forget to register the Listener in app/Providers/EventServiceProvider.php');

        Console::info('Documentation: https://laravel.com/docs/6.x/events#registering-events-and-listeners');
    }
}
