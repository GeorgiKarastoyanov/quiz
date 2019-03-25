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
//        $this->insertQuestion();

        $data = ServiceFactory::create('Users')->getNextQuestion();

        return $this->renderView('main', $data);
    }

    public function answer()
    {
        $postData = Input::postData();

        ServiceFactory::create('Users')->answer($postData);

        $this->redirect('main', 'index');
    }

    protected function insertQuestion()
    {
        $questions = [
            0 => [
                'question' => 'Who is the dictator of Cuba?',
                'answers' => [
                    0 => [
                        'content' => 'Fidel Castro',
                        'isCorrect' => 1,
                    ],
                    1 => [
                        'content' => 'Pablo Escobar',
                        'isCorrect' => 0,
                    ],
                ],
            ]
        ];

        foreach ($questions as $data) {
            $questionConds = [
                'content' => $data['question'],
            ];
            $questionId = RepositoryFactory::create('Questions')->create($questionConds);

            $answersConds = [
                'questionId' => $questionId
            ];
            foreach ($data['answers'] as $answer) {
                $answersConds['content'] = $answer['content'];
                $answersConds['isCorrect'] = $answer['isCorrect'];
                RepositoryFactory::create('Answers')->create($answersConds);
            }
        }
        dd(123);
    }
}
