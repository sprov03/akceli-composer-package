
    /**
     * Relationship to a <?=$OtherModel . PHP_EOL?>
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany|<?=$OtherModel . PHP_EOL?>
     */
    public function <?=$reverseRelationshipName?>()
    {
        return $this->morphMany(<?=$OtherModel?>::class, '<?=$relationship?>');
    }
