
    /**
     * Relationship to the <?=$otherModel?> Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|<?=$otherModel . PHP_EOL?>
     */
    public function <?=camel_case(str_plural($otherModel))?>()
    {
        return $this->hasMany(<?=$otherModel?>::class, '<?=$relationship->COLUMN_NAME?>', '<?=$relationship->REFERENCED_COLUMN_NAME?>');
    }
