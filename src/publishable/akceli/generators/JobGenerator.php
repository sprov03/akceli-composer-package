<?php

namespace Akceli\Generators;

use Akceli\Akceli;
use Akceli\Console;

class JobGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return false;
    }

    public function dataPrompter(): array
    {
        return [
            'Job' => function () {
                return Console::ask("What is the Class Name of the Job?\n Example: File will create a FileJob Class");
            },
            'Queue' => function () {
                $queues = ['default', 'long-running'];
                return Console::choice("What queue will this job be running in?", $queues, $queues[0]);
            }
        ];
    }

    public function templates(): array
    {
        return [
            Akceli::fileTemplate('job', 'tests/Akceli/ActualFiles/app/Jobs/[[Job]]Job.php'),
        ];
    }

    public function inlineTemplates(): array
    {
        return [
            // Akceli::inlineTemplate('template_name', 'destination_path', 'identifier string')
        ];
    }

    public function completionMessage()
    {
        Console::info('Success');
    }
}
