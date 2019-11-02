<?php

namespace Akceli\Console\Commands;

use Akceli\AkceliServiceProvider;
use Akceli\FileService;
use Akceli\Generators\AkceliGenerator;
use Akceli\GeneratorService;
use Akceli\Console;
use Akceli\Modifiers\Builders\Builder;
use Akceli\Modifiers\Builders\BuilderInterface;
use Akceli\Schema\SchemaFactory;
use Akceli\TemplateData;
use Akceli\Parser;
use Illuminate\Console\Command;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class AkceliBuildRelationshipsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'akceli:relationships {table} {arg1?} {arg2?} {arg3?} {arg4?} {arg5?} {arg6?} {arg7?} {arg8?} {arg9?} {arg10?} {--dump} {--force}';

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
        FileService::setRootDirectory(app_path());
        $config = config('akceli');
        $relationships = $config['relationships'] ?? [];
        $schema = SchemaFactory::resolve($this->argument('table'));
        $classParser = new Parser(base_path('akceli/templates/relationships'), 'akceli.php');
        $classParser->addData([]);


        Console::info('    ****************************************');
        Console::info('    *                                      *');
        Console::info('    *                Akceli                *');
        Console::info('    *                                      *');
        Console::info('    ****************************************');

        
        foreach ($relationships as $relationship => $builder) {
            /** @var BuilderInterface $builder */
            $builder = new $builder($classParser, $schema, $relationships, $this->option('force'));
            $builder->build();
        }
    }
}
