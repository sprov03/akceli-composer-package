<?php

namespace Akceli;

class ApiLogger
{
    private static array $messages = [];

    public static function getMessages()
    {
        return self::$messages;
    }

    public static function info(string $message)
    {
        array_push(self::$messages, [
            'type' => 'info',
            'message' => $message,
        ]);
    }

    public static function warn(string $message)
    {
        array_push(self::$messages, [
            'type' => 'warn',
            'message' => $message,
        ]);
    }

    public static function error(string $message)
    {
        array_push(self::$messages, [
            'type' => 'error',
            'message' => $message,
        ]);
    }

    public static function ask(string $question, $default = null)
    {
        // Nurfing this for the api calls
        return;
    }

    public static function choice($question, array $choices, $default = null, $attempts = null, $multiple = null)
    {
        // Nurfing this for the api calls
        return;
    }
}