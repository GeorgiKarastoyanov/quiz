<?php

namespace src\repository;

class UsersRepository extends AbstractRepository
{
    protected $tableName = 'users';

    public function joinSomeTables(array $bindParams)
    {
        $where = $this->getConditionsWhereClause($bindParams);

        $sql = 'select * from users join questions on user.id = question.userId WHERE ' . $where;

        return $this->exec($sql, $bindParams)->fetchAll(\PDO::FETCH_ASSOC);
    }
}