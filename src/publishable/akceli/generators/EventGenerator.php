<?php

namespace Akceli\Generators;

use Akceli\Akceli;
use Akceli\Console;

class EventGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return false;
    }

    public function dataPrompter(): array
    {
        return [
            'Event' => function () {
                return Console::ask('What is the name of the event?');
            }
        ];
    }

    public function templates(): array
    {
        return [
            Akceli::fileTemplate('event', 'app/Events/[[Event]]Event.php'),
            Akceli::fileTemplate('event_test', 'tests/Events/[[Event]]EventTest.php')
        ];
    }

    public function inlineTemplates(): array
    {
        return [
            // Akceli::inlineTemplate('template_name', 'destination_path', 'identifier string')
        ];
    }

    public function completionMessage(): void
    {
        Console::alert('Dont forget to register the Event in app/Providers/EventServiceProvider.php');
        Console::warn('Documentation: https://laravel.com/docs/5.8/events#registering-events-and-listeners');
    }
}
