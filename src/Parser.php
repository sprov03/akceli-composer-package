<?php

namespace CrudGenerator;

use League\Plates\Engine;

class Parser extends Engine
{
    public function render($name, array $data = [])
    {
        $data = array_merge($this->getData(), $data);

        $content = StringParser::renderWithData($name, $data);

        try {
            $content = parent::render($content, $data);
        } catch (\LogicException $exception) {
        }

        return StringParser::renderWithData($content, $data);
    }

}
