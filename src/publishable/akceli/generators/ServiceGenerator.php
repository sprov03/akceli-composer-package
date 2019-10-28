<?php

namespace Akceli\Generators;

use Akceli\Akceli;
use Akceli\Console;

class ServiceGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return false;
    }

    public function dataPrompter(): array
    {
        return [
            'Service' => function () {
                return Console::ask("What is the Class Name of the Service?\n Example: File will create a FileService Class", 'Dummy Data');
            }
        ];
    }

    public function templates(): array
    {
        return [
            Akceli::filetemplate('service', 'app/Services/[[Service]]Service/[[Service]]Service.php'),
            Akceli::filetemplate('service_test', 'tests/Services/[[Service]]Service/[[Service]]ServiceTest.php'),
            Akceli::filetemplate('service_stubs', 'tests/Services/[[Service]]Service/[[Service]]ServiceStubs.php'),
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
