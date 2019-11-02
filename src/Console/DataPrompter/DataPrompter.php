<?php

namespace Akceli\Console\DataPrompter;

class DataPrompter
{
    public static function prompt($generator, $initialData = [], $args = [])
    {
        $data = $generator['data'];
        foreach ($data as $key => $dataPrompt) {
            $initialData[$key] = $dataPrompt($initialData);
        }

        return $initialData;
    }
}
