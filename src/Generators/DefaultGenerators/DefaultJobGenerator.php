<?php

namespace Akceli\Generators\DefaultGenerators;

use Akceli\Generators\AkceliGenerator;

use Akceli\Akceli;
use Akceli\Console;

class DefaultJobGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return false;
    }

    public function dataPrompter(): array
    {
        return [
            'Job' => function (array $data) {
                return $data['arg1'] ?? Console::ask("What is the Class Name of the Job?\n Example: File will create a FileJob Class");
            },
        ];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::fileTemplate('job', 'app/Jobs/[[Job]]Job.php'),
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
