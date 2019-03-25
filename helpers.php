<?php

use src\support\Session;

function dd()
{
    echo '<pre>';
    array_map(function($x) { var_dump($x); }, func_get_args());
    echo '</pre>';
    die;
}

function isUserLogged()
{
    return Session::getInstance()->get('userId', false) !== false;
}

function getBaseUrl()
{
    $url = $_SERVER['REQUEST_URI']; //returns the current URL
    $parts = explode('/',$url);
    $dir = $_SERVER['SERVER_NAME'];
    for ($i = 0; $i < count($parts) - 1; $i++) {
        $dir .= $parts[$i] . "/";
    }

    return 'http://' . $dir;
}
