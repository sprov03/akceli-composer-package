<?php

namespace Akceli\Generators;

use Akceli\Akceli;
use Akceli\Console;

class SeederGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return false;
    }

    public function dataPrompter(): array
    {
        return [];
    }

    public function templates(): array
    {
        return [
            Akceli::fileTemplate('model_seeder', 'database/seeds/[[ModelName]]Seeder.php'),
        ];
    }

    public function inlineTemplates(): array
    {
        return [
            Akceli::inlineTemplate('seeder_reference', 'database/seeds/DatabaseSeeder.php', '        /** Dont forget to add the Seeder to database/seeds/DatabaseSeeder.php */'),
        ];
    }

    public function completionMessage(): void
    {
        Console::info('Success');
    }
}
