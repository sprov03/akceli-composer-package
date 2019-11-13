
    /**
     * Relationship to a [[OtherModel]]
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne|[[OtherModel]]
     */
    public function [[otherModels]]()
    {
        return $this->morphOne([[OtherModel]]::class, '[[relationship]]');
    }
