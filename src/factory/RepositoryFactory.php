<?php

namespace src\factory;

class RepositoryFactory implements FactoryInterface
{
    /**
     * @param string $name
     * @return mixed
     * @throws \Exception
     */
    public static function create(string $name)
    {
        $fullName = ucfirst(strtolower($name)) . 'Repository';
        $class = 'src\\repository\\' . $fullName;
        if (! class_exists($class)) {
            throw new \Exception('Repository with name ' . $name . ' does not exists!');
        }

        return new $class();
    }
}