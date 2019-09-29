<?php

namespace Akceli\Modifiers\Builders\Relationships;

use Akceli\FileService;
use Akceli\Modifiers\Builders\Builder;
use Akceli\Modifiers\Builders\BuilderInterface;
use Akceli\GeneratorService;

class BelongsToBuilder extends Builder implements BuilderInterface
{
    /**
     * Build sections of files and place them in the files
     *
     * @param \SplFileInfo $fileInfo
     * @param \SplFileInfo $otherFileInfo
     * @param $relationship
     *
     * @return void
     */
    public function updateFiles(
        \SplFileInfo $fileInfo,
        \SplFileInfo $otherFileInfo,
        $relationship
    ) {
        $otherModel = str_singular(studly_case($relationship->REFERENCED_TABLE_NAME));

       $this->addMethodToFile(
            $fileInfo,
            camel_case($otherModel),
            $this->parser->render('belongsTo', compact('relationship', 'otherModel'))
        );

        $this->addUseStatementToFile($fileInfo, $otherFileInfo);
        $this->addClassPropertyDocToFile($fileInfo, $otherModel, camel_case($otherModel));
    }

    public function analise($relationship, $interface = null)
    {
        $file = new FileService(app_path());

        $fileInfo = $file->findByTableName($this->schema->getTable());
        $otherFileInfo = $file->findByTableName($relationship->REFERENCED_TABLE_NAME);

        if (is_null($otherFileInfo)) {
            (new GeneratorService($relationship->REFERENCED_TABLE_NAME, $otherFileInfo->getFilename()))
                ->generate(false, false, true, true);
            $otherFileInfo = $file->findByTableName($relationship->REFERENCED_TABLE_NAME, true);
        }

        $this->updateFiles($fileInfo, $otherFileInfo, $relationship);
    }
}
