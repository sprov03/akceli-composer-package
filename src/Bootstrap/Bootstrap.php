<?php

namespace Akceli\Bootstrap;

use Closure;

class Bootstrap
{
    public static function globalStringReplace(string $from, string $to)
    {
        return new ReplaceString($from, $to);
    }

    public static function deleteFile(string $path)
    {
        return new DeleteFile($path);
    }

    public static function moveFile(string $from_path, string $to_path)
    {
        return new MoveFile($from_path, $to_path);
    }

    /**
     * @param Closure $modifierCallback Closure is required in order to not execute the modifier until its time to be executed
     * @return FileModifier
     */
    public static function fileModifier(Closure $modifierCallback)
    {
        return new FileModifier($modifierCallback);
    }

    public static function terminalCommand(string $command)
    {
        return new TerminalCommand($command);
    }
}