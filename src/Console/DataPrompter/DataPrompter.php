<?php

namespace Akceli\Console\DataPrompter;

class DataPrompter
{
    public static function prompt($data, $initialData = [])
    {
        foreach ($data as $key => $dataPrompt) {
            $initialData[$key] = $dataPrompt($initialData);
        }

        return $initialData;
    }
}
