<?php

namespace Akceli\Bootstrap;

class TerminalCommand extends AkceliBootstrap
{
    private string $command;

    /**
     * TerminalCommand constructor.
     * @param string $command
     */
    public function __construct(string $command)
    {
        $this->command = $command;
    }

    public function process()
    {
        shell_exec(escapeshellcmd($this->command));
    }
}