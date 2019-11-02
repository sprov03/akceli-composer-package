
    /**
     * Relationship to the <?=$otherModel?> Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|<?=$otherModel . PHP_EOL?>
     */
    public function <?=$relationship->hasOneMethodName?>()
    {
        return $this->hasOne(<?=$otherModel?>::class, '<?=$relationship->COLUMN_NAME?>', '<?=$relationship->REFERENCED_COLUMN_NAME?>');
    }
