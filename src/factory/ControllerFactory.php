<?php

namespace src\factory;

class ControllerFactory implements FactoryInterface
{
    /**
     * @param string $name
     * @return mixed
     * @throws \Exception
     */
    public static function create(string $name)
    {
        $fullName = ucfirst(strtolower($name)) . 'Controller';
        $class = 'src\\controller\\' . $fullName;
        if (! class_exists($class)) {
            throw new \Exception('Controller with name ' . $name . ' does not exists!');
        }

        return new $class();
    }
}
