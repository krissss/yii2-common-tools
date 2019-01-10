<?php

namespace kriss\modules\auth\tools;

use Yii;

class RouteHelper
{
    public $routes = [];

    public function __construct($routes)
    {
        $this->routes = $routes;
    }

    public static function create($routes)
    {
        return new static($routes);
    }

    public static function normalizeRoute($route)
    {
        if ($route === '') {
            $normalized = '/' . Yii::$app->controller->getRoute();
        } elseif (strncmp($route, '/', 1) === 0) {
            $normalized = $route;
        } elseif (strpos($route, '/') === false) {
            $normalized = '/' . Yii::$app->controller->getUniqueId() . '/' . $route;
        } elseif (($mid = Yii::$app->controller->module->getUniqueId()) !== '') {
            $normalized = '/' . $mid . '/' . $route;
        } else {
            $normalized = '/' . $route;
        }

        return ltrim($normalized, '/');
    }

    /**
     * 匹配 admin/*
     * @param $prefix
     * @return bool
     */
    public function isMatchPrefix($prefix)
    {
        foreach ($this->routes as $route) {
            if (substr($route, -1) === '*') {
                $route = substr($route, 0, strlen($route) - 1);
                if (strpos(rtrim($prefix, '/') . '/', $route) === 0) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * 匹配 *\/index
     * @param $prefix
     * @param $action
     * @return bool
     */
    public function isMatchAction($prefix, $action)
    {
        foreach ($this->routes as $route) {
            if (strpos($route, '*/') === 0 && $action === substr($route, 2)) {
                return true;
            }
            if ($route === $prefix . '/' . $action) {
                return true;
            }
        }
        return false;
    }
}
