<?php

namespace src\repository;

use src\exception\MysqlConnectException;
use src\factory\AdapterFactory;

abstract class AbstractRepository
{
    protected
        $adapter,
        $tableName,
        $primaryKey = 'id';

    public function __construct()
    {
        $this->adapter = AdapterFactory::createMysqlAdapter();
    }

    /**
     * @param array $params
     * @return int
     * @throws MysqlConnectException
     */
    public function create(array $params): int
    {
        $sql = $this->getInsertSql($params);

        $this->exec($sql, $params);

        return $this->adapter->lastInsertId();
    }

    /**
     * @param array $params
     * @throws \Exception
     */
    public function update(array $params)
    {
        if (! isset($params[$this->primaryKey])) {
            throw new \Exception('Missing ' . $this->primaryKey . ' by update!');
        }

        $updateExpr = $this->buildUpdateExpression($params);

        $sql = 'UPDATE ' . $this->tableName . ' SET ' . $updateExpr . ' WHERE ' . $this->primaryKey . ' = :' . $this->primaryKey;

        $this->exec($sql, $params);
    }

    /**
     * @param array $conditions
     * @param array $order
     * @param bool $limit
     * @return array
     * @throws MysqlConnectException
     */
    public function findAllBy(array $conditions = [], array $order = [], $limit = false): array
    {
        $where = $this->getConditionsWhereClause($conditions);

        $sql = 'SELECT * FROM ' . $this->tableName;

        if (! empty($conditions)) {
            $sql .= ' WHERE '. $where;
        }

        if (! empty($order)) {
            $sql .= ' ORDER BY ' . $order['orderBy'] . ' ' . $order['orderType'];
        }

        if (! empty($limit) && is_numeric($limit)) {
            $sql .= ' LIMIT ' . $limit;
        }

        return $this->exec($sql, $conditions)->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param array $params
     * @return string
     */
    protected function buildUpdateExpression(array $params)
    {
        if (isset($params[$this->primaryKey])) {
            unset($params[$this->primaryKey]);
        }

        $return = '';
        $add = '';
        foreach ($params as $column => $value) {
            $return .= $add . $column . ' = :' . $column;
            $add = ', ';
        }

        return $return;
    }

    /**
     * @param string $sql
     * @param array $bindParams
     * @return mixed
     * @throws MysqlConnectException
     */
    protected function exec(string $sql, array $bindParams = [])
    {
        $statement = $this->adapter->prepare($sql);

        try {
            $statement->execute($bindParams);
        } catch (\PDOException $e) {
            throw new MysqlConnectException('Query failed: ' . $sql . '  | MESSAGE: ' . $e->getMessage());
        }

        return $statement;
    }

    /**
     * @param array $conditions
     * @return string
     */
    protected function getConditionsWhereClause(array &$conditions): string
    {
        $return = '';
        $add = '';
        foreach ($conditions as $col => &$val) {
            if (is_array($val)) {
                $return .= $add . $col . ' ' . $val['operator'] . ' :' . $col;
                $val = $val['value'];
            } else {
                $return .= $add . $col . ' = :' . $col;
            }

            $add = ' AND ';
        }

        return $return;
    }

    /**
     * @param array $params
     * @return string
     */
    protected function getInsertSql(array $params): string
    {
        $columns = '';
        $values = '';
        $add = '';
        foreach ($params as $col => $val) {
            $columns .= $add . $col;
            $values .= $add . ':' . $col;
            $add = ', ';
        }

        return 'INSERT INTO ' . $this->tableName . ' (' . $columns . ') VALUES (' . $values . ') '
            . 'ON DUPLICATE KEY UPDATE ' . $this->primaryKey . '=' . $this->primaryKey ;
    }
}
