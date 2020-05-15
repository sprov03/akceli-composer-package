<?php

namespace Akceli\Generators\DefaultGenerators;

use Akceli\AkceliFileModifier;
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

    public function templates(array $data): array
    {
        return [
            Akceli::template('model_seeder', 'database/seeds/[[ModelName]]Seeder.php'),
        ];
    }

    public function fileModifiers(array $data): array
    {
        return [
            AkceliFileModifier::phpFile(base_path('database/seeds/DatabaseSeeder.php'))
                ->addToTopOfMethod('run', "\$this->call({$data['ModelName']}Seeder::class);"),

//            AkceliFileModifier::phpFile(base_path('database/seeds/DatabaseSeeder.php'))
//                ->addLineAbove('/** Register Seeders Here */', "\$this->call({$data['ModelName']}Seeder::class);"),
        ];
    }

    public function completionMessage(array $data)
    {
        Console::info('Success');
    }
}
