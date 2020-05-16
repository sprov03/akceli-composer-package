<?php

namespace Akceli\Bootstrap;

class ReplaceString extends AkceliBootstrap
{
    private string $from;
    private string $to;

    /**
     * ReplaceString constructor.
     * @param string $from
     * @param string $to
     */
    public function __construct(string $from, string $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    function process()
    {
        foreach ($this->getModifiableFiles() as $pathname => $fileInfo) {
            $file_contents = file_get_contents($fileInfo->getPathName());
            $file_contents = str_replace($this->from, $this->to, $file_contents);
            file_put_contents($fileInfo->getPathName(), $file_contents);
        }
    }
}