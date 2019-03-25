<?php

namespace src\service;

use src\factory\RepositoryFactory;

class ScoresService extends AbstractService
{
    /**
     * @param int $userId
     * @return mixed
     * @throws \Exception
     */
    public function createScoresRecord(int $userId)
    {
        $bindParams = [
            'userId' => $userId,
            'lastAnswerDate' => date('Y-m-d'),
        ];
        return RepositoryFactory::create('Scores')->create($bindParams);
    }
}
