<?php

namespace src\support;

class Password
{
    /**
     * @param string $password
     * @return string
     */
    public static function getHashedPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password , $hash);
    }
}
