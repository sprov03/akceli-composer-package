<?php

namespace Akceli;

use Akceli\Akceli;
use Akceli\Console;
use Akceli\Generators\AkceliGenerator;
use Akceli\Schema\Relationships\BelongsToRelationship;
use App\Models\BaseModelTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;

class DefaultSchemaSyncGenerator extends AkceliGenerator
{
    public function dataPrompter(): array
    {
        return [
            'migration_name' => function (array $data) {
                return $data['arg1'] ?? Console::ask('What do you wan to call these migrations', 'Auto Generated Migration');
            }
        ];
    }

    public function templates(array $data): array
    {
        return [];
    }

    public function inlineTemplates(array $data): array
    {
        return [];
    }

    public function completionMessage(array $data)
    {
        /** Get a collection of all the models defined in the Models directory. */
        FileService::setRootDirectory(app_path(config('akceli.model_directory')));
        $modelsFiles = FileService::getFilesThatUseTrait(config('akceli.akceliSchemaTrait'));
        $sortedModelsFiles = $this->sortModelFiles($modelsFiles);

        /** for each model run the schema-migration command. */
        foreach ($sortedModelsFiles as $index => $modelsFile) {
            $modelName = $modelsFile->getBasename('.php');
            Artisan::call('akceli:generate schema-migration ' . $modelName . ' \'' . $data['migration_name'] . '_' . $index .  '\' --no-interaction');
        }

        /** prompt for the option to migrate when complete, default to no */
        $shouldMigrate = Console::choice('Do you want to run migrate the changes?', ['yes', 'no'], 'no');
        if ($shouldMigrate === 'yes') {
            Artisan::call('migrate');
        }

        Console::info('Success');
    }


    /**
     * @param Collection|\SplFileInfo[] $modelFiles
     * @return \Illuminate\Support\HigherOrderCollectionProxy|mixed
     */
    public function sortModelFiles(Collection $modelFiles)
    {
        $sortedModelFiles = collect();

        do {
            $foundAccentModel = false;
            foreach ($modelFiles as $modelFile) {
                $modelName = $modelFile->getBasename('.php');
                $fullyQualifiedModelName = 'App\\Models\\' . $modelName;
                /** @var BaseModelTrait $model */
                $model = new $fullyQualifiedModelName();

                foreach ($model->getHydratedSchema() as $name => $schemaItem) {
                    if ($schemaItem instanceof BelongsToRelationship) {
                        $relatedModelNamespace = get_class($schemaItem->getRelatedModel());
                        foreach ($modelFiles as $fileInfo) {
                            $namespace = FileService::getExpectedNamespaceOfFile($fileInfo);
                            if ($namespace === $relatedModelNamespace) {
                                // Found A Parent, so save this for the next iteration
                                continue 3;
                            }
                        }
                    }
                }

                // No Parent, so this is the most Ancient Model left in the collection
                $sortedModelFiles->push($modelFile);
                $modelFiles = $modelFiles->filter(fn(\SplFileInfo $fileInfo) => $fileInfo->getRealPath() !== $modelFile->getRealPath());
                $foundAccentModel = true;
            }

        } while ($foundAccentModel);

        return $sortedModelFiles;
    }
}
