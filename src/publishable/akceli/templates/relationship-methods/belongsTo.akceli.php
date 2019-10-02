<?php
/** @var  TemplateData $table */
use Akceli\TemplateData;?>
    /**
     * Relationship to the <?=$otherModel?> Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|<?=$otherModel . PHP_EOL?>
     */
    public function <?=camel_case($otherModel)?>()
    {
        return $this->belongsTo(<?=$otherModel?>::class, '<?=$relationship->COLUMN_NAME?>', '<?=$relationship->REFERENCED_COLUMN_NAME?>');
    }
