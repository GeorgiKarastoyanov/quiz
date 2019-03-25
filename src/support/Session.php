<?php

namespace src\support;

class Session
{
    private static $instance;

    /**
     * Session constructor.
     */
    private function __construct()
    {
        session_start();
    }

    /**
     * @return Session
     */
    public static function getInstance(): Session
    {
        if (! self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    
    /**
     * @param string $name
     * @param mixed $value
     */
    public function set(string $name, $value): void
    {
        $_SESSION[$name] = $value;
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get(string $name, $default = false)
    {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }

        return $default;
    }

    /**
     * Destroy current session
     */
    public function destroy()
    {
        session_destroy();
    }
}
