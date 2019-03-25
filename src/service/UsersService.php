<?php

namespace src\service;

use src\factory\RepositoryFactory;
use src\support\Manager;
use src\support\MessageHandler;
use src\support\Session;

class UsersService extends AbstractService
{
    /**
     * @return array|bool
     * @throws \Exception
     */
    public function getNextQuestion()
    {
        $user = Manager::currentUser();
        if (! $user) {
            throw new \Exception('Missing user!');
        }

        $config = Manager::quizConfig();

        if ($user['scores']['dailyAnswers'] >= $config['maxDailyQuestions']) {
            MessageHandler::getInstance()->setMessage('You have reached the maximum amount of questions for today! Thank you for your answers!');

            return [];
        }

        $currentQuestion = Session::getInstance()->get('currentQuestion', false);

        if ($currentQuestion) {
            $question = $currentQuestion;
        } else {
            $allQuestions = Manager::allQuestions();
            $alreadyAnsweredIds = Session::getInstance()->get('answeredQuestionIds', []);

            $hasAlreadyAnswered = true;
            while ($hasAlreadyAnswered) {
                $randKey = array_rand($allQuestions);
                $question = $allQuestions[$randKey];
                $hasAlreadyAnswered = in_array($question['id'], $alreadyAnsweredIds);
            }

            $answersConds = [
                'questionId' => $question['id'],
            ];
            $question['answers'] = RepositoryFactory::create('Answers')->findAllBy($answersConds);

            Session::getInstance()->set('currentQuestion', $question);
        }

        return [
            'question' => $question,
            'user' => $user,
            'config' => $config,
        ];
    }

    public function answer(array $params)
    {
        $user = Manager::currentUser();
        if (! $user) {
            throw new \Exception('Missing user!');
        }

        $config = Manager::quizConfig();

        if ($user['scores']['dailyAnswers'] >= $config['maxDailyQuestions']) {
            MessageHandler::getInstance()->setMessage('You have reached the maximum amount of questions for today!');

            return false;
        }

        if (empty($params['questionId']) || empty($params['answerId'])) {
            MessageHandler::getInstance()->setMessage('Invalid parameters!');

            return false;
        }

        $conds = [
            'id' => $params['questionId'],
        ];
        $result = RepositoryFactory::create('Questions')->findAllBy($conds);

        if (empty($result)) {
            MessageHandler::getInstance()->setMessage('Invalid question!');

            return false;
        }

        $question = $result[0];

        $answerConds = [
            'id' => $params['answerId'],
        ];
        $aResult = RepositoryFactory::create('Answers')->findAllBy($answerConds);

        if (empty($aResult)) {
            MessageHandler::getInstance()->setMessage('Invalid answer!');

            return false;
        }
        $answer = $aResult[0];

        if ($answer['questionId'] != $question['id']) {
            MessageHandler::getInstance()->setMessage('Answer provided belongs to other question!');

            return false;
        }

        $user['scores']['dailyAnswers']++;

        $winPoints = 0;
        if ($answer['isCorrect']) {
            $addMsg = 'correct';
            if ($user['scores']['dailyAnswers'] % $config['bonusAnswerNumber'] == 0) {
                $winPoints = $config['bonusAnswerPoints'];
            } else {
                $winPoints = $config['correctAnswerPoints'];
            }
        } else {
            $addMsg = 'incorrect';
        }

        $user['scores']['dailyScore'] += $winPoints;
        $user['scores']['totalScore'] += $winPoints;
        $user['scores']['lastAnswerDate'] = date('Y-m-d');

        if ($user['scores']['dailyScore'] > $user['scores']['maxDailyScore']) {
            $user['scores']['maxDailyScore'] = $user['scores']['dailyScore'];
        }
        RepositoryFactory::create('Scores')->update($user['scores']);

        $alreadyAnsweredIds = Session::getInstance()->get('answeredQuestionIds', []);
        $alreadyAnsweredIds[] = $question['id'];

        Session::getInstance()->set('answeredQuestionIds', $alreadyAnsweredIds);
        Session::getInstance()->set('currentQuestion', false);

        MessageHandler::getInstance()->setMessage(sprintf('Your answer was %s and you won %s points!', $addMsg, $winPoints));

        return true;

    }
}
