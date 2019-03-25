<?php
declare(strict_types = 1);

namespace src\support;

class View
{
    /**
     * @param string $name
     * @param array $data
     * @return string
     * @throws \Exception
     */
    public static function render($name = false, $data = array(), $layout = '')
    {
        if ($name) {
            $view = self::getPath($name);
        }

        extract($data);
        if (empty($layout)) {
            $layout = 'master';
        }

        $path = self::getPath($layout);

        ob_start();
        include $path;
        $contents = ob_get_contents();
        ob_end_clean();

        return $contents;
    }

    /**
     * @param $name
     * @param array $data
     * @return false|string
     * @throws \Exception
     */
    public static function partial($name, $data = array())
    {
        $path = self::getPath($name);

        extract($data);

        ob_start();
        include $path;
        $contents = ob_get_contents();
        ob_end_clean();

        return $contents;
    }

    /**
     * @param string $name
     * @return string
     * @throws \Exception
     */
    public static function getPath(string $name): string
    {

        $path = src_dir . 'view/' . $name . '.phtml';
        
        if (! file_exists($path)) {
            throw new \Exception('No view with the name ' . $name);
        }

        return $path;
    }
}