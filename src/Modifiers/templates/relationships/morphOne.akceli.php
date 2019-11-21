
    /**
     * Relationship to a <?=$OtherModel . PHP_EOL?>
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne|<?=$OtherModel . PHP_EOL?>
     */
    public function <?=$reverseRelationshipName?>()
    {
        return $this->morphOne(<?=$OtherModel?>::class, '<?=$relationship?>');
    }
