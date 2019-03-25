<?php

namespace src\support;

use src\factory\RepositoryFactory;

class Manager
{
    const QUIZ_CONFIG_SETTINGS_NAME = 'quizConfig';

    private static $user = null;
    private static $quizConfig = null;
    private static $allQuestions = null;

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function currentUser()
    {
        if (is_null(self::$user)) {
            $userId = Session::getInstance()->get('userId', false);

            if ($userId === false) {
                $user = false;
            } else {
                $conds = [
                    'id' => $userId,
                ];
                $data = RepositoryFactory::create('Users')->findAllBy($conds);
                if (empty($data)) {
                    $user = false;
                } else {
                    $user = $data[0];
                    $scores = RepositoryFactory::create('Scores')->findAllBy(['userId' => $userId]);
                    $user['scores'] = $scores[0];
                }
            }
            self::$user = $user;
        }

        return self::$user;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public static function quizConfig(): array
    {
        if (is_null(self::$quizConfig)) {
            $conds = [
                'name' => self::QUIZ_CONFIG_SETTINGS_NAME,
            ];
            $result = RepositoryFactory::create('Settings')->findAllBy($conds);

            self::$quizConfig = json_decode($result[0]['value'], true);
        }

        return self::$quizConfig;
    }

    public static function allQuestions()
    {
        if (is_null(self::$allQuestions)) {
            self::$allQuestions = RepositoryFactory::create('Questions')->findAllBy();
        }

        return self::$allQuestions;
    }
}
