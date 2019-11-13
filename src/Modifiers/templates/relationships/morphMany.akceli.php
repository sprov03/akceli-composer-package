
    /**
     * Relationship to a [[OtherModel]]
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany|[[OtherModel]]
     */
    public function [[otherModels]]()
    {
        return $this->morphMany([[OtherModel]]::class, '[[relationship]]');
    }
