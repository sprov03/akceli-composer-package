<?php

namespace App\Columns;

class ArrayColumn extends Column
{
    public function getCast()
    {
        return 'array';
    }
}