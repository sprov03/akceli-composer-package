<?php
/** @var  TemplateData $table */
use Akceli\TemplateData;?>
    /**
     * Relationship to the <?=$otherModel?> Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|<?=$otherModel . PHP_EOL?>
     */
    public function <?=camel_case(str_singular($otherModel))?>()
    {
        return $this->hasOne(<?=$otherModel?>::class, '<?=$relationship->COLUMN_NAME?>', '<?=$relationship->REFERENCED_COLUMN_NAME?>');
    }
