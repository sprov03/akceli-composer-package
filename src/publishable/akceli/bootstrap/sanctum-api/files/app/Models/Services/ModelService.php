<?php

namespace App\Models\Services;

use Illuminate\Database\Eloquent\Model;

class ModelService
{
    protected Model $model;

    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @return self
     */
    public function fresh(): self
    {
        $this->model = $this->getModel()->fresh();

        return $this;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    public function patch(array $attributes)
    {
        return $this->getModel()->update($attributes);
    }

    /**
     * @return bool|null
     * @throws \Exception
     */
    public function delete()
    {
        return $this->getModel()->delete();
    }

    /**
     * @return bool|null
     */
    public function forceDelete()
    {
        return $this->getModel()->forceDelete();
    }
}
