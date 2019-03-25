<?php

namespace src\service;

use src\factory\RepositoryFactory;
use src\factory\ServiceFactory;
use src\support\Manager;
use src\support\MessageHandler;
use src\support\Password;
use src\support\Session;

class LoginService extends AbstractService
{
    /**
     * @param array $params
     * @return bool
     * @throws \Exception
     */
    public function login(array $params)
    {
        if (empty($params['name']) || empty($params['pass'])) {
            MessageHandler::getInstance()->setMessage('Invalid parameters sent!');

            return false;
        }

        $conds = [
            'name' => $params['name'],
        ];
        $existUser = RepositoryFactory::create('Users')->findAllBy($conds);

        if (! $existUser) {
            MessageHandler::getInstance()->setMessage('Invalid username!');

            return false;
        }

        $row = $existUser[0];

        if (! Password::verifyPassword(trim($params['pass']), $row['password'])) {
            MessageHandler::getInstance()->setMessage('Invalid password!');

            return false;
        }

        $this->logUser((int) $row['id']);
        $user = Manager::currentUser();
        if ($user['scores']['lastAnswerDate'] != date('Y-m-d')) {
            $user['scores']['dailyScore'] = 0;
            $user['scores']['dailyAnswers'] = 0;
            RepositoryFactory::create('Scores')->update($user['scores']);

            Session::getInstance()->set('answeredQuestionIds', []);
        }

        MessageHandler::getInstance()->setMessage('Logged successfully!');

        return true;
    }

    /**
     * @param array $params
     * @return bool
     * @throws \Exception
     */
    public function register(array $params)
    {
        if (empty($params['name']) || empty($params['pass']) || empty($params['repeatPass'])) {
            MessageHandler::getInstance()->setMessage('Invalid parameters sent!');

            return false;
        }

        if ($params['pass'] !== $params['repeatPass']) {
            MessageHandler::getInstance()->setMessage('Passwords do not match!');

            return false;
        }

        $conds = [
            'name' => $params['name'],
        ];
        $existUser = RepositoryFactory::create('Users')->findAllBy($conds);
        if (! empty($existUser)) {
            MessageHandler::getInstance()->setMessage('Username already exists!');

            return false;
        }

        $bindParams = [
            'name' => $params['name'],
            'password' => Password::getHashedPassword($params['pass']),
        ];
        $lastInsertId = RepositoryFactory::create('Users')->create($bindParams);

        if ($lastInsertId) {
            $userId = (int) $lastInsertId;
            $this->logUser($userId);
            ServiceFactory::create('Scores')->createScoresRecord($userId);
        }

        MessageHandler::getInstance()->setMessage('Register successfully!');

        return true;
    }

    /**
     * @return bool
     */
    public function logout()
    {
        Session::getInstance()->destroy();

        MessageHandler::getInstance()->setMessage('Logout successfully!');

        return true;
    }

    /**
     * @param int $userId
     * @return void
     */
    protected function logUser(int $userId): void
    {
        Session::getInstance()->set('userId', $userId);
    }
}
