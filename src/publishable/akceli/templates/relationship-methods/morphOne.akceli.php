<?php
/** @var  TemplateData $table */
use Akceli\TemplateData;?>
    /**
     * Relationship to a <?=studly_case($interface)?> item
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne|<?=$otherModel . PHP_EOL?>
     */
    public function <?=camel_case(str_singular($otherModel))?>()
    {
        return $this->morphOne(<?=$otherModel?>::class, '<?=snake_case($relationship)?>');
    }
