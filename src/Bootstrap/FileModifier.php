<?php

namespace Akceli\Bootstrap;

use Akceli\AkceliFileModifier;

class FileModifier extends AkceliBootstrap
{
    private \Closure $modifierClosure;

    /**
     * FileModifier constructor.
     * @param \Closure $modifierClosure
     */
    public function __construct(\Closure $modifierClosure)
    {
        $this->modifierClosure = $modifierClosure;
    }

    function process()
    {
        // Set the closure so that it is no callable instead of referencing a method that dose not exist on this class.
        $closure = $this->modifierClosure;

        /**
         * @var AkceliFileModifier $fileModifier
         *
         * Getting the File Modifier from the Closure
         */
        $fileModifier = $closure();
        $fileModifier->saveChanges();
    }
}