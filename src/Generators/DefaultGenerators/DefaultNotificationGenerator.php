<?php

namespace Akceli\Generators\DefaultGenerators;

use Akceli\Generators\AkceliGenerator;

use Akceli\Akceli;
use Akceli\Console;

class DefaultNotificationGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return false;
    }

    public function dataPrompter(): array
    {
        return [
            "Notification" => function (array $data) {
                return $data['arg1'] ?? Console::ask('What is the name of the Notification', 'ExampleNotification');
            }
        ];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::fileTemplate('notification', 'app/Notifications/[[Notification]].php'),
        ];
    }

    public function fileModifiers(array $data): array
    {
        return [];
    }

    public function completionMessage(array $data)
    {
        Console::info('Documentation: https://laravel.com/docs/6.x/notifications#introduction');

    }
}
