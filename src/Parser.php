<?php

namespace Akceli;

use League\Plates\Engine;

class Parser extends Engine
{
    public function render($nameOrContent, array $data = [])
    {
        $data = array_merge($this->getData(), $data);

        $content = StringParser::renderWithData($nameOrContent, $data);

        try {
            $content = parent::render($content, $data);
        } catch (\LogicException $exception) {
        }

        return StringParser::renderWithData($content, $data);
    }

}
