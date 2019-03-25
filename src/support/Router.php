<?php

namespace src\support;

class Router
{
    const DEFAULT_TARGET_NAME = 'Main';
    const DEFAULT_ACTION_NAME = 'index';

    /**
     * @param array $params
     * @return array
     */
    public static function resolve(array $params): array
    {
        $target = (! empty($params['target'])) ? trim($params['target']) : self::DEFAULT_TARGET_NAME;
        $action = (! empty($params['action'])) ? trim($params['action']) : self::DEFAULT_ACTION_NAME;

        return array($target, $action);
    }
}
