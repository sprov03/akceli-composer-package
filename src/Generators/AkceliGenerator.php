<?php

namespace Akceli\Generators;


use Akceli\GeneratorService;

abstract class AkceliGenerator implements AkceliGeneratorInterface, \ArrayAccess
{

    /**
     * Can be used to add or overwrite data at any time
     */
    public function addData(array $data)
    {
        return GeneratorService::$paser->addData($data);
    }

    /**
     * Can be used to update and use data at the same time.
     */
    public function set(string $key, $value)
    {
        GeneratorService::$paser->addData([$key, $value]);

        return $value;
    }

    public function requires_table_name() {
        return $this->requiresTable();
    }

    public function data()
    {
        return $this->dataPrompter(GeneratorService::getData());
    }

    public function inline_templates()
    {
        return $this->inlineTemplates(GeneratorService::getData());
    }
    
    public function completion_message()
    {
        $this->completionMessage(GeneratorService::getData());
    }

    /**
     * Whether a offset exists
     * @link https://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return in_array($offset, [
            'templates',
            'requires_table_name',
            'data',
            'inline_templates',
            'completion_message'
        ]);
    }

    /**
     * Offset to retrieve
     * @link https://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->{$offset}(GeneratorService::getData());
    }

    /**
     * Offset to set
     * @link https://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this[$offset] = $value;
    }

    /**
     * Offset to unset
     * @link https://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
    }
}