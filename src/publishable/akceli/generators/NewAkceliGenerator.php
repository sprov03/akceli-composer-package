<?php

namespace Akceli\Generators;

use Akceli\Akceli;
use Akceli\Console;

class NewAkceliGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return false;
    }

    public function dataPrompter(): array
    {
        return [
            'GeneratorName' => function() {
                return Console::ask('What is the name of the new Generator?');
            },

            /**
             * Used in the import Template
             */
            'ImportNamespace' => function(array $data) {
                $generatorName = $data['GeneratorName'];
                return 'Akceli\Generators\\' . $generatorName . 'Generator';
            }
        ];
    }

    public function templates(): array
    {
        return [
            Akceli::fileTemplate('akceli_generator', 'akceli/generators/[[GeneratorName]]Generator.php'),
        ];
    }

    public function inlineTemplates(): array
    {
        return [
            Akceli::inlineTemplate(
                'akceli_generator_register',
                'config/akceli.php',
                '        /** New Generators Get Inserted Here */'
            ),
             Akceli::inlineTemplate(
                 'import',
                 'config/akceli.php',
                 '/** auto import new commands */'
             )
        ];
    }

    public function completionMessage()
    {
        Console::info('You have successfully created the new Akceli Migration');
    }
}
