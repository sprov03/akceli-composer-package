<?php
/** @var  TemplateData $table */
use Akceli\TemplateData;?>
    /**
     * Relationship to a <?=studly_case($interface)?> item
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany|<?=$otherModel . PHP_EOL?>
     */
    public function <?=camel_case(str_plural($otherModel))?>()
    {
        return $this->morphMany(<?=$otherModel?>::class, '<?=snake_case($relationship)?>');
    }
