<?php

namespace Akceli;

use Illuminate\Console\Command;

/**
 * Class Log
 * @package Akceli
 *
 * @mixin Command
 * @method static info(string $message, $verbosity = null)
 * @method static error(string $message, $verbosity = null)
 * @method static warn(string $message, $verbosity = null)
 */
class Log
{
    /**
     * @var Command
     */
    private static $logger;

    public static function setLogger(Command $logger)
    {
        self::$logger = $logger;
    }

    public static function __callStatic($name, $arguments)
    {
        call_user_func([self::$logger, $name], ...$arguments);
    }
}
