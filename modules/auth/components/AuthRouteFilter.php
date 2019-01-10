<?php

namespace kriss\modules\auth\components;

use kriss\modules\auth\tools\RouteHelper;
use kriss\modules\auth\tools\AuthValidate;
use yii\base\ActionFilter;

class AuthRouteFilter extends ActionFilter
{
    public $exceptRoutes = [
        'site/*',
        'home/*',
        '*/previous-redirect',
    ];

    public function beforeAction($action)
    {
        $routeHelper = RouteHelper::create($this->exceptRoutes);
        if ($routeHelper->isMatchPrefix($action->controller->uniqueId) || $routeHelper->isMatchAction($action->controller->uniqueId, $action->id)) {
            return parent::beforeAction($action);
        }

        if (!AuthValidate::has($action->controller->uniqueId)) {
            AuthValidate::run($action->controller->uniqueId . '/' . $action->id);
        }

        return parent::beforeAction($action);
    }
}
