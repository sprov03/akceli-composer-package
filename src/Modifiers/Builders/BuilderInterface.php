<?php

namespace CrudGenerator\Modifiers\Builders;

interface BuilderInterface
{
    public function analise($relationship, $interface = null);
}