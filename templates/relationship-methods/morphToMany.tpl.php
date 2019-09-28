
    /**
     * Relationship to this <?=studly_case($interface)?> items
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany|<?=$otherModel . PHP_EOL?>
     */
    public function <?=str_plural(camel_case($otherModel))?>()
    {
        return $this->morphToMany(<?=$otherModel?>::class, '<?=$interface?>');
    }
