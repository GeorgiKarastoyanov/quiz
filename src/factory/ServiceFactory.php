<?php

namespace src\factory;

class ServiceFactory implements FactoryInterface
{
    private static $loaded = [];

    /**
     * @param string $name
     * @return mixed
     * @throws \Exception
     */
    public static function create(string $name)
    {
        if (! isset(self::$loaded[$name])) {
            $fullName = ucfirst(strtolower($name)) . 'Service';
            $class = 'src\\service\\' . $fullName;
            if (! class_exists($class)) {
                throw new \Exception('Service with name ' . $name . ' does not exists!');
            }

            self::$loaded[$name] = new $class();
        }

        return self::$loaded[$name];
    }
}
