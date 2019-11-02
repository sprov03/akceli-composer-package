<?php

namespace Akceli\Generators\DefaultGenerators;

use Akceli\Generators\AkceliGenerator;

use Akceli\Akceli;
use Akceli\Console;

class DefaultSeederGenerator extends AkceliGenerator
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

    public function inlineTemplates(array $data): array
    {
        return [
            Akceli::insertInline(
                'database/seeds/DatabaseSeeder.php',
                '        /** Dont forget to add the Seeder to database/seeds/DatabaseSeeder.php */',
                '        $this->call([[ModelName]]Seeder::class);'
            ),
        ];
    }

    public function completionMessage(array $data)
    {
        Console::info('Success');
    }
}
