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
                return $data['arg1'] ?? Console::ask('What is the name of the event?');
            }
        ];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::fileTemplate('event', 'app/Events/[[Event]]Event.php'),
            Akceli::fileTemplate('event_test', 'tests/Events/[[Event]]EventTest.php')
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
        Console::alert('Dont forget to register the Event in app/Providers/EventServiceProvider.php');
        Console::warn('Documentation: https://laravel.com/docs/5.8/events#registering-events-and-listeners');
    }
}
