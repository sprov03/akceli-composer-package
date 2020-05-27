<?php

namespace Akceli;

use Akceli\Schema\PortalSchema;
use Akceli\Schema\SchemaFactory;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class Generator
{
    /**
     * Execute the console command.
     *
     * @param array $templates
     * @param array $template_data
     * @param string $model_path
     * @param array $schema_info
     * @param array $file_modifiers
     * @param bool $force
     * @return mixed
     * @throws \Throwable
     */
    public function handle(array $templates, array $template_data, string $model_path, array $schema_info, array $file_modifiers, bool $force = false)
    {
        $model_path = app_path($model_path);
        FileService::setRootDirectory($model_path);

        /**
         * Setup Model Data if Required
         */
        if (isset($schema_info['columns'])) {
            $schema = new PortalSchema($schema_info['table_name']);
            $schema->setSchemaInfo($schema_info);
            $columns = $schema->getColumns();
            $template_data['table_name'] = $schema->getTable();
            $template_data['columns'] = $columns;
            $template_data['primaryKey'] = $schema_info['primary_key']['field'] ?? null;
//            $template_data['primaryKey'] =  ($columns->firstWhere('Key', '==', 'PRI')) ? $columns->firstWhere('Key', '==', 'PRI')->Field : null;

            /**
             * If the table is present, then we will build the model aliases
             * This could be a request or response data
             */
            if ($table_name = $schema->getTable()) {
                $model_name = Str::studly(Str::singular($table_name));
                if ($modelFile = FileService::findByTableName($table_name)) {
                    $model_name = FileService::getClassNameOfFile($modelFile);
                }
                $template_data = array_merge($template_data, TemplateData::buildModelAliases($model_name));
            }
        } else {
            $columns = collect([]);
        }

        GeneratorService::setData($template_data);
        GeneratorService::setFileTemplates($templates);
        GeneratorService::setFileModifiers($file_modifiers);

        /**
         * Parses the template data and updated all the shortcodes
         */
        $templateData = new TemplateData($template_data, $columns);
        $generator = new GeneratorService($templateData);
        $generator->generate($force);
    }
}
