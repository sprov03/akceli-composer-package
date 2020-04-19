<?php

namespace Akceli\Generators\DefaultGenerators;

use Akceli\Generators\AkceliGenerator;

use Akceli\Akceli;
use Akceli\Console;
use Illuminate\Support\Str;

class DefaultCronJobGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return false;
    }

    public function dataPrompter(): array
    {
        return [
            'Command' => function (array $data) {
                return $data['arg1'] ?? Console::ask('What is the name of the Command?', 'ExampleCommand');
            },
            'Signature' => function (array $data) {
                $command = Str::kebab($data['Command']);
                return $data['arg2'] ?? Console::ask('What is the signature for the command?', 'gittask:' . $command);
            },
            'Schedule' => function (array $data) {
                $schedule = Console::choice('Which of the following schedules makes the most since?', [
                    'everyMinute',
                    'everyFiveMinutes',
                    'everyTenMinutes',
                    'everyFifteenMinutes',
                    'hourly',
                    'daily',
                    'weekly',
                    'monthly',
                    'quarterly',
                    'yearly',
                    'custom',
                ]);

                if ($schedule === 'custom') {
                    Console::alert('Documentation for scheduling options: https://laravel.com/docs/6.x/scheduling#schedule-frequency-options');
                    do {
                        $schedule = Console::ask('Put the schedule in here in the followowing format:', 'dailyAt(\'13:00\')');
                    } while($schedule === '');
                } else {
                    $schedule = $schedule . '()';
                }

                return $schedule;
            },
        ];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::fileTemplate('command', 'app/Console/Commands/[[Command]].php'),
            Akceli::fileTemplate('command_test', 'tests/Console/Commands/[[Command]]Test.php'),
        ];
    }

    public function inlineTemplates(array $data): array
    {
        return [
            Akceli::insertInline('app/Console/Kernel.php', '/** Register Commands Here */', '$schedule->command(\'[[Signature]]\')->[[Schedule]];'),
        ];
    }

    public function completionMessage(array $data)
    {
        Console::alert('Documentation: https://laravel.com/docs/6.x/artisan#writing-commands');
    }
}
