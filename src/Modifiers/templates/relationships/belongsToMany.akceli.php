
    /**
     * Relationship to the <?=$otherModel?> Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|<?=$otherModel . PHP_EOL?>
     */
    public function <?=Str::camel(Str::plural($otherModel))?>()
    {
        return $this->belongsToMany(<?=$otherModel?>::class, '<?=$table_name?>');
    }
