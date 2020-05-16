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
         * @var AkceliFileModifier[] $fileModifiers
         *
         * Getting the File Modifier from the Closure
         */
        $fileModifiers = $closure();
        foreach ($fileModifiers as $modifier) {
            $modifier->saveChanges();
        }
    }
}