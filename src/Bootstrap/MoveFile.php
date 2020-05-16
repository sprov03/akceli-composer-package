<?php

namespace Akceli\Bootstrap;

use Illuminate\Support\Facades\File;

class MoveFile extends AkceliBootstrap
{
    private string $from_path;
    private string $to_path;

    /**
     * MoveFile constructor.
     * @param string $from_path Relative to the base bath
     * @param string $to_path Relative to the base bath
     */
    public function __construct(string $from_path, string $to_path)
    {
        $this->from_path = $from_path;
        $this->to_path = $to_path;
    }

    function process()
    {
        File::move(self::getBasePath($this->from_path), self::getBasePath($this->to_path));
    }
}