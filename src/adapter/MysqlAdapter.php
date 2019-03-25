<?php

namespace src\adapter;


class MysqlAdapter
{
    protected
        $pdo,
        $dbHost,
        $dbName,
        $dsn,
        $affectedRows = 0;

    public function __construct($dbHost, $dbName)
    {
        $this->dbHost = $dbHost;
        $this->dbName = $dbName;
        $this->dsn = "mysql:host={$dbHost};dbname={$dbName}";
    }

    public function connect($user = '', $pass = '')
    {
        try {
            $this->pdo = new \PDO($this->dsn, $user, $pass);

            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->pdo->exec('use `' . $this->dbName . '`;');
            $this->pdo->exec('SET NAMES utf8');
        } catch (\Exception $ex) {
            throw new \Exception("DSN: {$this->dsn}. {$ex->getMessage()}");
        }

        return $this;
    }

    public function exec($sql, $bindParams = null)
    {
        $statement = $this->pdo->prepare($sql);

        try {
            $statement->execute($bindParams);
        } catch (\PDOException $e) {
            throw new \Exception('Query failed: ' . $sql . '  | MESSAGE: ' . $e->getMessage());
        }

        return $statement;
    }

    public function getSqlValue($sql, $bindParams = array())
    {
        return $this->exec($sql, $bindParams)->fetch(\PDO::FETCH_COLUMN, 0);
    }

    public function fetchAll($sql, $bindParams = null)
    {
        return $this->exec($sql, $bindParams)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function fetch($sql, $bindParams = null)
    {
        return $this->exec($sql, $bindParams)->fetch(\PDO::FETCH_ASSOC);
    }

    public function getLastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

}