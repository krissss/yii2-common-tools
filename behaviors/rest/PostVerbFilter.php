<?php

namespace kriss\behaviors\rest;

use Yii;
use yii\base\Behavior;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\MethodNotAllowedHttpException;

/**
 * must be post and params can not be empty
 */
class PostVerbFilter extends Behavior
{
    /**
     * @var array
     *
     * For example,
     *
     * ```php
     * ['create', 'update'] for choose actions
     * or
     * ['*'] for all actions
     * ```
     */
    public $actions = [];

    /**
     * Declares event handlers for the [[owner]]'s events.
     * @return array events (array keys) and the corresponding event handler methods (array values).
     */
    public function events()
    {
        return [Controller::EVENT_BEFORE_ACTION => 'beforeAction'];
    }

    /**
     * @param $event
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws MethodNotAllowedHttpException
     */
    public function beforeAction($event)
    {
        $action = $event->action->id;
        if (in_array($action, $this->actions) || in_array('*', $this->actions)) {
            $method = 'post';
            $allowed = ['POST'];
        } else {
            return $event->isValid;
        }

        // validate request method
        $verb = Yii::$app->getRequest()->getMethod();
        $allowed = array_map('strtoupper', $allowed);
        if (!in_array($verb, $allowed)) {
            $event->isValid = false;
            // http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.7
            Yii::$app->getResponse()->getHeaders()->set('Allow', implode(', ', $allowed));
            throw new MethodNotAllowedHttpException('Method Not Allowed. This url can only handle the following request methods: ' . implode(', ', $allowed) . '.');
        }

        // validate is post params empty
        $request = Yii::$app->request;
        if (count($request->$method()) === 0) {
            $event->isValid = false;
            throw new ForbiddenHttpException('This Request Need Parameter');
        }

        return $event->isValid;
    }
}
