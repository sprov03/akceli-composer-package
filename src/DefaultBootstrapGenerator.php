<?php

namespace Akceli;

use Akceli\Bootstrap\AkceliBootstrap;
use Akceli\Console;
use Akceli\Generators\AkceliGenerator;
use Illuminate\Support\Facades\File;

class DefaultBootstrapGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return false;
    }

    public function dataPrompter(): array
    {
        return [
            'Bootstrap' => function(array $data) {
                $bootstrap_path = __DIR__ . "/Bootstrap";
                $bootstrapOptions = scandir($bootstrap_path);
                /**
                 * TODO: Better Solution for Windows Compatibility
                 * Removing the files ['.', '..']
                 */
                array_shift($bootstrapOptions);
                array_shift($bootstrapOptions);

                if (isset($data['arg1']) && array_flip($bootstrapOptions)[$data['arg1']]) {
                    return $data['arg1'];
                }

                $choice = $data['arg1'] ?? Console::choice('What would you like to bootstrap?', $bootstrapOptions);

                if (!in_array($choice, $bootstrapOptions)) {
                    Console::error("\n\n  [ERROR] Value \"{$choice}\" is invalid\n" );
                    $choice = Console::choice('What would you like to bootstrap?', $bootstrapOptions);
                }

                return $choice;
            },
            'BasePath' => function(array $data) {
                if (isset($data['arg2'])) {
                    return base_path($data['arg2']);
                }

                return base_path();
            }
        ];
    }

    public function completionMessage(array $data)
    {
        $bootstrap_path = __DIR__ . "/Bootstrap/" . $data['Bootstrap'];
        /** @var AkceliBootstrap[]  $config */
        $config = require($bootstrap_path . "/bootstrap.config.php");

        File::copyDirectory($bootstrap_path . "/files", $data['BasePath']);

        /**
         * Sets the base path for getting the Akceli modifiable Files
         */
        chdir($data['BasePath']);
        AkceliBootstrap::setBasePath($data['BasePath']);
        AkceliFileModifier::setBasePath($data['BasePath']);

        foreach ($config as $bootstrap) {
            $bootstrap->process();
        }

        Console::info('Success');
    }
}
