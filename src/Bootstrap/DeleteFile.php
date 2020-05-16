<?php

namespace Akceli\Bootstrap;

class DeleteFile extends AkceliBootstrap
{
    private string $path;

    /**
     * DeleteFile constructor.
     * @param string $path Should be relative to the base path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    function process()
    {
        try {
            unlink(self::getBasePath($this->path));
        } catch (\Throwable $throwable) {
            dump($throwable->getMessage());
        }
    }
}