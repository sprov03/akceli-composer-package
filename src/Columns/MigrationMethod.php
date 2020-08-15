<?php

namespace Akceli\Schema\Columns;

class MigrationMethod
{
    public string $name;
    public array $args;

    public function __construct(string $name, array $args = [])
    {
        $this->name = $name;
        $this->args = $args;
    }

    public function __toString()
    {
       return $this->name . '(' . $this->formatArgs($this->args) . ')';
    }

    public function formatArgs($args = [])
    {
        $formatedArgs = [];
        foreach ($args as $arg) {
            if (is_bool($arg)) {
                array_push($formatedArgs, ($arg) ? 'true' : 'false');
            } elseif (is_string($arg)) {
                array_push($formatedArgs, '"' . $arg . '"');
            } elseif (is_array($arg)) {
                array_push($formatedArgs, '[' . $this->formatArgs($arg) . ']');
            } else {
                array_push($formatedArgs, $arg);
            }
        }

        return implode(', ', $formatedArgs);
    }

    public function setColumnName(string $colum_name)
    {
        array_unshift($this->args,  $colum_name);
    }
}