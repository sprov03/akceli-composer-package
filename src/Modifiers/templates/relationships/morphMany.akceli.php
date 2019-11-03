
    /**
     * Relationship to a <?=Str::studly($interface)?> item
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany|<?=$otherModel . PHP_EOL?>
     */
    public function <?=Str::camel(Str::plural($otherModel))?>()
    {
        return $this->morphMany(<?=$otherModel?>::class, '<?=snake_case($relationship)?>');
    }
