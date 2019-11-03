
    /**
     * Relationship to a <?=Str::studly($interface)?> item
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne|<?=$otherModel . PHP_EOL?>
     */
    public function <?=Str::camel(Str::singular($otherModel))?>()
    {
        return $this->morphOne(<?=$otherModel?>::class, '<?=snake_case($relationship)?>');
    }
