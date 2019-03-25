<?php

namespace src\factory;

interface FactoryInterface
{
    /**
     * @param string $name
     * @return mixed
     */
    public static function create(string $name);
}