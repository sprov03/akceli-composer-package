<?php

namespace App\Columns;

class BooleanColumn extends Column
{
    public function getCast()
    {
        return 'boolean';
    }
}