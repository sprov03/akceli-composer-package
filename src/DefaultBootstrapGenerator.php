<?php

namespace Akceli;

use Akceli\Console;
use Akceli\Generators\AkceliGenerator;
use Illuminate\Support\Facades\File;
use RecursiveCallbackFilterIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

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
                $bootstrapOptions = scandir(base_path('akceli/bootstrap'));
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
                return $data['arg2'] ?? './';
            }
        ];
    }

    public function completionMessage(array $data)
    {
        $backup = file_get_contents(base_path("akceli/bootstrap/{$data['Bootstrap']}/bootstrap.config.php"));
        try {
            $config = require(base_path("akceli/bootstrap/{$data['Bootstrap']}/bootstrap.config.php"));
            File::copyDirectory(base_path("akceli/bootstrap/{$data['Bootstrap']}/files"), $data['BasePath']);

            foreach ($config as $command_set) {
                switch ($command_set['type']) {
                    case 'commands': {
                        $this->runComposerCommands($command_set['actions'] ?? []);
                        break;
                    }
                    case 'string_replacements': {
                        $this->replaceStrings($command_set['actions'] ?? [], $data['BasePath']);
                        break;
                    }
                    case 'files_to_remove': {
                        $this->removeFiles($command_set['actions'] ?? [], $data['BasePath']);
                        break;
                    }
                    case 'file_modifiers': {
                        $modifiers = $command_set['actions'] ?? function () {return [];};
                        foreach ($modifiers() as $fileModifier) {
                            $fileModifier->saveChanges();
                        }
                        break;
                    }
                }

            }

            Console::info('Success');
        } finally {
            file_put_contents(base_path("akceli/bootstrap/{$data['Bootstrap']}/bootstrap.config.php"), $backup);
        }
    }

    private function replaceStrings(array $strings, $path)
    {
        /**
         * Dont wast time processing this if there are no string replacements
         */
        if (count($strings) === 0) {
            return;
        }

        $directory = base_path($path);
        $exclude = ['vendor', 'node_modules', '.git', '.idea'];

        /**
         * @param SplFileInfo $file
         * @param mixed $key
         * @param RecursiveCallbackFilterIterator $iterator
         * @return bool True if you need to recurse or if the item is acceptable
         */
        $filter = function ($file, $key, $iterator) use ($exclude) {
            if ($iterator->hasChildren() && !in_array($file->getFilename(), $exclude)) {
                return true;
            }
            return $file->isFile();
        };

        $innerIterator = new RecursiveDirectoryIterator(
            $directory,
            RecursiveDirectoryIterator::SKIP_DOTS
        );
        $iterator = new RecursiveIteratorIterator(
            new RecursiveCallbackFilterIterator($innerIterator, $filter)
        );

        /** @var SplFileInfo $fileInfo */
        foreach ($iterator as $pathname => $fileInfo) {
            foreach ($strings as $old => $new) {
                $file_contents = file_get_contents($fileInfo->getPathName());
                $file_contents = str_replace($old, $new, $file_contents);
                file_put_contents($fileInfo->getPathName(), $file_contents);
            }
        }
    }

    private function removeFiles(array $filesToRemove, $basePath)
    {
        foreach ($filesToRemove as $file) {
            try {
                unlink(base_path($basePath . $file));
            } catch (\Throwable $throwable) {
                dump($throwable->getMessage());
            }
        }
    }
    
    private function runComposerCommands(array $commands)
    {
        foreach ($commands as $command) {
            shell_exec(escapeshellcmd($command));
        }
    }
}
