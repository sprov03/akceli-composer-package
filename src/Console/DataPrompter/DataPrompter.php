<?php

namespace Akceli\Console\DataPrompter;

class DataPrompter
{
    public static function prompt($templateSet, $initialData = [], $args = [])
    {
        $data = $templateSet['data'];
        $index = ($templateSet['requires_table_name']) ? 2 : 1;
        foreach ($data as $key => $dataPrompt) {
            $initialData[$key] = $args['arg' . $index] ?? $dataPrompt($initialData);
            $index++;
        }

        return $initialData;
    }
}
