<?php

namespace Akceli\Console\DataPrompter;

class DataPrompter
{
    public static function prompt($templateSet, $initialData = [], $args = [])
    {
        foreach ($data as $key => $dataPrompt) {
            $initialData[$key] = $dataPrompt($initialData);
            $index++;
        }

        return $initialData;
    }
}
