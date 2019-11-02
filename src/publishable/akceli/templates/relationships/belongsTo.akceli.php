
    /**
     * Relationship to the <?=$otherModel?> Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|<?=$otherModel . PHP_EOL?>
     */
    public function <?=$belongsToMethodName?>()
    {
        return $this->belongsTo(<?=$otherModel?>::class, '<?=$relationship->COLUMN_NAME?>', '<?=$relationship->REFERENCED_COLUMN_NAME?>');
    }
