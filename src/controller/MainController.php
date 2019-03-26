<?php

namespace src\controller;

use src\factory\RepositoryFactory;
use src\factory\ServiceFactory;
use src\support\Input;

class MainController extends AbstractController
{
    protected $auth = true;
    /**
     * @return string
     * @throws \Exception
     */
    public function index()
    {
        $data = ServiceFactory::create('Users')->getNextQuestion();

        return $this->renderView('main', $data);
    }

    /**
     * @throws \Exception
     */
    public function answer()
    {
        $postData = Input::postData();

        ServiceFactory::create('Users')->answer($postData);

        $this->redirect('main', 'index');
    }
}
