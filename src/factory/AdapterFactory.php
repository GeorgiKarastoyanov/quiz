<?php

namespace src\factory;

use src\exception\MysqlConnectException;

class AdapterFactory
{
    private static $adapters = [];

    /**
     * @return mixed
     * @throws MysqlConnectException
     */
    public static function createMysqlAdapter()
    {
        if (empty(self::$adapters['mysql'])) {

            try {
                $dsn = "mysql:host=" . mysqlHost . ":3307;dbname=" . mysqlDb;

                $pdo = new \PDO($dsn, mysqlUser, mysqlPass);

                $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $pdo->exec('use `' . mysqlDb . '`;');
                $pdo->exec('SET NAMES utf8');

                self::$adapters['mysql'] = $pdo;
            } catch (\Exception $ex) {
                dd($ex);
                throw new MysqlConnectException();
            }
        }

        return self::$adapters['mysql'];
    }
}