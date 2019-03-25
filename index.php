<?php

use src\factory\ControllerFactory;
use src\support\Response;
use src\support\Session;
use src\support\Router;
use src\support\Input;

require_once './config/constants.php';
require_once './helpers.php';
require_once './autoload.php';

Session::getInstance();
$response = Response::getInstance();

try {

    list($controllerName, $method) = Router::resolve(Input::getData());

    // Hardcoded controller name only for the project goals.
    $controller = ControllerFactory::create($controllerName);

    if (! method_exists($controller, $method)) {
        throw new Exception('Controller ' . $controllerName . ' does not have method ' . $method . '!');
    }

    $controller->before();
    $responseContent = $controller->$method();
} catch (Exception $ex) {
    dd($ex);
    $responseContent = $controller->handleException($ex);
}

$response->setContent($responseContent);
$response->send();

