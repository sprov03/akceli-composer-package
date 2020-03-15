<?php

namespace Akceli\Generators\DefaultGenerators;

use Akceli\Generators\AkceliGenerator;

use Akceli\Akceli;
use Akceli\Console;
use Illuminate\Support\Str;

class DefaultChannelGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return false;
    }

    public function dataPrompter(): array
    {
        return [
            'Channel' => function (array $data) {
                return $data['arg1'] ?? Console::ask('What is the name of the Channel you want to create?', 'ExampleChannel');
            },
        ];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::fileTemplate('channel', 'app/Broadcasting/[[Channel]].php'),
            Akceli::fileTemplate('channel_test', 'tests/Broadcasting/[[Channel]]Test.php'),
        ];
    }

    public function inlineTemplates(array $data): array
    {
        return [
            Akceli::insertInline('routes/channels.php', '/** Auto Import */', 'use App\Broadcasting\[[Channel]];'),
            Akceli::insertInline('routes/channels.php', '/** register channels here */', 'Broadcast::channel(\'[[Channel]].{[[Channel]]}\', [[Channel]]::class);'),
        ];
    }

    public function completionMessage(array $data)
    {
        Console::warn('Documentation: https://laravel.com/docs/6.x/broadcasting#defining-channel-classes');
    }
}
