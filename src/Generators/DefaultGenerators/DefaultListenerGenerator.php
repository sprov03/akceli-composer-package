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
                return $data['arg1'] ?? Console::ask('What is the name of the Listener?');
            }
        ];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::fileTemplate('listener', 'app/Listeners/[[Listener]]Listener.php'),
            Akceli::fileTemplate('listener_test', 'tests/Listeners/[[Listener]]ListenerTest.php'),
        ];
    }

    public function inlineTemplates(array $data): array
    {
        return [
        ];
    }

    public function completionMessage(array $data)
    {
        Console::alert('Dont forget to register the Listener in app/Providers/EventServiceProvider.php');
        Console::warn('Documentation: https://laravel.com/docs/6.x/events#registering-events-and-listeners');
    }
}
