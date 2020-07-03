<?php

namespace App\Columns;

class FloatColumn extends Column
{
    public function getCast()
    {
        return 'float';
    }
}