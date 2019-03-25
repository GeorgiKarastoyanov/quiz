<?php

namespace src\controller;

use src\factory\RepositoryFactory;
use src\factory\ServiceFactory;
use src\support\Input;

class LoginController extends AbstractController
{
    /**
     * @return string
     * @throws \Exception
     */
    public function loginView(): string
    {
        return $this->renderView('login-view');
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function doLogin()
    {
        $postData = Input::postData();

        $success = ServiceFactory::create('Login')->login($postData);

        if ($success) {
            $this->redirect('main', 'index');
        }

        return $this->renderView('login-view');
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function registerView()
    {
        return $this->renderView('register-view');
    }

    /**
     * @throws \Exception
     */
    public function doRegister()
    {
        $postData = Input::postData();

        ServiceFactory::create('Login')->register($postData);

        $this->redirect('main', 'index');
    }

    public function logout()
    {
        ServiceFactory::create('Login')->logout();

        $this->redirect('main', 'index');
    }
}
