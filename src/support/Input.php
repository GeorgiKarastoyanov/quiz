<?php

namespace src\support;

class Input
{
    /**
     * @var null|array
     */
    private static $postData = null;

    /**
     * @var null|array
     */
    private static $getData = null;

    /**
     * @return array
     */
    public static function postData(): array
    {
        if (is_null(self::$postData)) {
            self::$postData = $_POST;
        }

        return self::$postData;
    }

    /**
     * @return array
     */
    public static function getData(): array
    {
        if (is_null(self::$getData)) {
            self::$getData = $_GET;
        }

        return self::$getData;
    }
}