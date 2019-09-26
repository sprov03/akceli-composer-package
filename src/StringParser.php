<?php

namespace CrudGenerator;

class StringParser
{
    function __construct($data)
    {
        $this->data = $data;
    }

    public function render($string) {
        $string = self::renderVariables($string, $this->data);

        return $string;
    }

    public static function renderWithData($string, $data) {
        $string = self::renderVariables($string, $data);

        return $string;
    }

    protected static function renderVariables($template, $data) {
        $callback = function ($matches) use($data) {
            if(array_key_exists($matches[1], $data)) {
                return $data[$matches[1]];
            }

            return $matches[0];
        };

        return preg_replace_callback('/\[\[\s*\s*(.+?)\s*\]\]/s', $callback, $template);
    }
}
