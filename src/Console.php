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
 * @method static alert(string $message, $verbosity = null)
 * @method static ask($question, $default = null)
 * @method static choice($question, array $choices, $default = null, $attempts = null, $multiple = null)
 */
class Console
{
    /**
     * @var Command
     */
    private static $logger;

    /**
     * @param Command $logger
     */
    public static function setLogger(Command $logger)
    {
        self::$logger = $logger;
    }

    public static function __callStatic($name, $arguments)
    {
        return call_user_func([self::$logger, $name], ...$arguments);
    }
}
