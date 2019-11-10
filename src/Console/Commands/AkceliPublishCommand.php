<?php

namespace Akceli\Console\Commands;

use Akceli\AkceliServiceProvider;
use Akceli\FileService;
use Akceli\Generators\AkceliGenerator;
use Akceli\GeneratorService;
use Akceli\Console;
use Akceli\Schema\SchemaFactory;
use Akceli\TemplateData;
use Illuminate\Console\Command;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class AkceliPublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'akceli:publish {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Akceli https://docs.akceli.io';

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        /**
         * Setup Global Classes
         */
        Console::setLogger($this);

        Console::info('    ****************************************');
        Console::info('    *                                      *');
        Console::info('    *                Akceli                *');
        Console::info('    *                                      *');
        Console::info('    ****************************************');

        $exitCode = Artisan::call('vendor:publish', [
            '--provider' => AkceliServiceProvider::class,
            '--force' => $this->option('force')
        ]);

        /**
         * Add the Trait to the composer json
         */
        $composerJson = json_decode(file_get_contents(base_path('composer.json')), true);
        $composerJson['autoload-dev'] = $composerJson['autoload-dev'] ?? [];
        $composerJson['autoload-dev']['files'] = $composerJson['autoload-dev']['files'] ?? [];
        $composerJson['autoload-dev']['psr-4']['Akceli\\Generators\\'] = "akceli/generators/";
        $composerJson['autoload-dev']['psr-4']['Factories\\'] = "database/factories/";
        array_push($composerJson['autoload-dev']['files'], "akceli/AkceliTableDataTrait.php");
        array_push($composerJson['autoload-dev']['files'], "akceli/AkceliColumnTrait.php");
        $newComposerJson = json_encode($composerJson, JSON_PRETTY_PRINT + JSON_UNESCAPED_SLASHES);
        file_put_contents(base_path('composer.json'), $newComposerJson);

        /**
         * Publish Generators
         */
        FileService::setRootDirectory(base_path('vendor/akceli/laravel-code-generator/src/Generators/DefaultGenerators'));
        $files = FileService::getAppFiles();
        foreach ($files as $file) {
            $content = file_get_contents($file->getPathname());
            $content = str_replace('namespace Akceli\Generators\DefaultGenerators;', 'namespace Akceli\Generators;', $content);
            $content = str_replace('class Default', 'class ', $content);
            $className = str_replace('Default', '', FileService::getClassNameOfFile($file));
            if ($className === '.') {
                continue;
            }
            file_put_contents(base_path('akceli/generators/' . $className . '.php'), $content);
        }


        if ($exitCode) {
            Console::error('');
            Console::error('There was an error publishing the config file: Try running the following command for more details:');
            Console::error('php artisan vendor:publish --provider=' . AkceliServiceProvider::class);
            Console::error('');
            return;
        } else {
            Console::info('The akceli.php config file we published to /config/akceli.php');
            Console::info('akceli/AkceliTableDataTrait.php was published');
            Console::info('akceli/AkceliColumnTrait.php was published');
            Console::info('akceli/generators was published');

            shell_exec('composer dump-autoload');
            return;
        }
    }
}
