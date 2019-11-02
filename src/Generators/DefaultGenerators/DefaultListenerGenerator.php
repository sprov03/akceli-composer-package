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
            'Listener' => function() {
                return Console::ask('What is the name of the Listener?');
            }
        ];
    }

    public function templates(): array
    {
        return [
            Akceli::fileTemplate('listener', 'app/Listeners/[[Listener]]Listener.php'),
            Akceli::fileTemplate('listener_test', 'tests/Listeners/[[Listener]]ListenerTest.php'),
        ];
    }

    public function inlineTemplates(array $data): array
    {
        return [
            // Akceli::inlineTemplate('template_name', 'destination_path', 'identifier string')
        ];
    }

    public function completionMessage(array $data)
    {
        Console::alert('Dont forget to register the Listener in app/Providers/EventServiceProvider.php');
        Console::warn('Documentation: https://laravel.com/docs/5.8/events#registering-events-and-listeners');
    }
}
