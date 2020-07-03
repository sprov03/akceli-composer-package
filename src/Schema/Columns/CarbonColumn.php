<?php

namespace App\Columns;

class CarbonColumn extends Column
{
    public function getCast()
    {
        return 'datetime';
    }
}