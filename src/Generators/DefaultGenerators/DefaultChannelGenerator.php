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
            'Channel' => function () {
                return $data['arg1'] ?? Str::studly(Console::ask('What is the name of the Channel you want to create?'));
            },
        ];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::fileTemplate('channel', 'app/Broadcasting/[[Channel]]Channel.php'),
            Akceli::fileTemplate('channel_test', 'tests/Broadcasting/[[Channel]]ChannelTest.php'),
        ];
    }

    public function inlineTemplates(array $data): array
    {
        return [
            //Akceli::inlineTemplate('channel_register', 'routes/channels.php', '/** Dont forget to add the channel to the channels.php file */')
        ];
    }

    public function completionMessage(array $data)
    {
        Console::alert('Dont forget to register the Channel in routes/channels.php');
        Console::warn('Documentation: https://laravel.com/docs/5.8/broadcasting#defining-channel-classes');
    }
}
