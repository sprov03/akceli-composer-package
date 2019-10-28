<?php

namespace Akceli\Generators;

use Akceli\Akceli;
use Akceli\Console;

class SeederGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return true;
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
            Akceli::insertInline(
                'database/seeds/DatabaseSeeder.php',
                '        /** Dont forget to add the Seeder to database/seeds/DatabaseSeeder.php */',
                '        $this->call([[ModelName]]Seeder::class);'
            ),
        ];
    }

    public function completionMessage(): void
    {
        Console::info('Success');
    }
}
