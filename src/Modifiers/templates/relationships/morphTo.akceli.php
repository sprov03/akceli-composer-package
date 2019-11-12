
    /**
     * Relationship to a <?=Str::studly($interface)?> item
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo|<?=$interface . PHP_EOL?>
     */
    public function <?=Str::camel(Str::singular($relationship))?>()
    {
        return $this->morphTo();
    }
