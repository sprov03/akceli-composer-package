<?php

namespace Akceli\Generators\DefaultGenerators;

use Akceli\AkceliFileModifier;
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
                return $data['arg1'] ?? Console::ask('What is the name of the Channel?', 'ExampleChannel');
            },
        ];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::template('channel', 'app/Broadcasting/[[Channel]].php'),
            Akceli::template('channel_test', 'tests/Broadcasting/[[Channel]]Test.php'),
        ];
    }

    public function fileModifiers(array $data): array
    {
        return [
            AkceliFileModifier::phpFile(base_path('routes/channels.php'))
                ->addUseStatementToFile("App\Broadcasting\\{$data['Channel']}")
                ->addLineAbove('Broadcast::channel', "Broadcast::channel('{$data['Channel']}.{{$data['Channel']}}', {$data['Channel']}::class);")
        ];
    }

    public function completionMessage(array $data)
    {
        Console::warn('Documentation: https://laravel.com/docs/6.x/broadcasting#defining-channel-classes');
    }
}
