<?php

namespace Akceli\Generators;

use Akceli\Akceli;
use Akceli\Console;
use Illuminate\Support\Str;

class ChannelGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return false;
    }

    public function dataPrompter(): array
    {
        return [
            'Channel' => function () {
                return Str::studly(Console::ask('What is the name of the Channel you want to create?'));
            },
        ];
    }

    public function templates(): array
    {
        return [
            Akceli::fileTemplate('channel', 'app/Broadcasting/[[Channel]]Channel.php'),
            Akceli::fileTemplate('channel_test', 'tests/Broadcasting/[[Channel]]ChannelTest.php'),
        ];
    }

    public function inlineTemplates(): array
    {
        return [
            //Akceli::inlineTemplate('channel_register', 'routes/channels.php', '/** Dont forget to add the channel to the channels.php file */')
        ];
    }

    public function completionMessage(): void
    {
        Console::alert('Dont forget to register the Channel in routes/channels.php');
        Console::warn('Documentation: https://laravel.com/docs/5.8/broadcasting#defining-channel-classes');
    }
}
