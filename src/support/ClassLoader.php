<?php

namespace src\support;

class ClassLoader
{
    protected static $registered = false;

    public static function load($class)
    {
        $class = self::normalizeClass($class);
        $path = app_dir . $class;

        if (file_exists($path)) {
            require_once $path;

            return true;
        }
    }

    public static function normalizeClass($class)
    {
        if ($class[0] == '\\') {
            $class = substr($class, 1);
        }

        return str_replace(array('\\', '_'), DIRECTORY_SEPARATOR, $class) . '.php';
    }

    public static function register()
    {
        if (! static::$registered) {
            spl_autoload_register(array(get_class(), 'load'));

            static::$registered = true;
        }
    }
}