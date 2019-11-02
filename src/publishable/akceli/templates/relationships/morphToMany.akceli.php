
    /**
     * Relationship to this <?=Str::studly($interface)?> items
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany|<?=$otherModel . PHP_EOL?>
     */
    public function <?=Str::plural(Str::camel($otherModel))?>()
    {
        return $this->morphToMany(<?=$otherModel?>::class, '<?=$interface?>');
    }
