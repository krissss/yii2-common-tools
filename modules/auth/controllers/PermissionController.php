<?php

namespace kriss\modules\auth\controllers;

use kriss\modules\auth\models\Auth;
use kriss\modules\auth\Module;
use kriss\modules\auth\tools\AuthValidate;
use yii\web\Controller;
use Yii;

class PermissionController extends Controller
{
    public function actionIndex()
    {
        /** @var Auth $authClass */
        $authClass = Yii::$app->user->authClass;
        AuthValidate::run($authClass::PERMISSION_VIEW);

        $operations = (Module::getAuthOperationClass())::findAllOperations();
        return $this->render('index', [
            'operations' => $operations,
        ]);
    }
}