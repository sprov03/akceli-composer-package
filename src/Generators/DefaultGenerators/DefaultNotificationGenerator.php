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
                return Console::ask('What is the name of the Notification');
            }
        ];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::fileTemplate('notification', 'app/Notifications/[[Notification]]Notification.php'),
        ];
    }

    public function inlineTemplates(array $data): array
    {
        return [
        ];
    }

    public function completionMessage(array $data)
    {
        Console::info('Success');

    }
}
