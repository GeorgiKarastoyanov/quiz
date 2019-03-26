<?php

namespace src\controller;

use src\exception\MysqlConnectException;
use src\factory\ServiceFactory;
use src\support\MessageHandler;
use src\exception\CustomException;
use src\support\Response;
use src\support\Session;
use src\support\View;

abstract class AbstractController
{
    protected $auth = false;

    /**
     * Before execute of controller action
     */
    public function before()
    {
        if ($this->auth && ! isUserLogged()) {
            $this->redirect('login', 'loginView');
        }

        $sessionMessages = Session::getInstance()->get('messages', []);
        foreach ($sessionMessages as $msg) {
            MessageHandler::getInstance()->setMessage($msg);
        }
        Session::getInstance()->set('messages', []);
    }

    /**
     * @param \Exception $ex
     * @return string
     * @throws \Exception
     */
    public function handleException(\Exception $ex)
    {
        return 'Something went wrong! Message: ' . $ex->getMessage() . '!';
    }

    /**
     * @param string $name
     * @param array $data
     * @return string
     * @throws \Exception
     */
    protected function renderView(string $name, array $data = []): string
    {
        $alphabet = 'ABCDEFGH';

        $addData = [
            'messages' => MessageHandler::getInstance()->getMessages(),
            'alphabet' => $alphabet,
            'baseUrl' => getBaseUrl(),
        ];
        $data = array_merge($data, $addData);

        return View::render($name, $data);
    }

    /**
     * @param string $target
     * @param string $action
     */
    protected function redirect(string $target, string $action)
    {
        Session::getInstance()->set('messages', MessageHandler::getInstance()->getMessages());

        Response::getInstance()->redirect($target, $action);
    }
}
