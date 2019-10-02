
    /**
     * Relationship to a <?=studly_case($interface)?> item
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo|<?=$interface . 'Interface' . PHP_EOL?>
     */
    public function <?=camel_case(str_singular($relationship))?>()
    {
        return $this->morphTo();
    }
