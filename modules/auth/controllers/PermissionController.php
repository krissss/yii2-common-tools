<?php

namespace kriss\modules\auth\controllers;

use kriss\modules\auth\models\Auth;
use kriss\modules\auth\Module;
use kriss\modules\auth\tools\AuthValidate;
use Yii;
use yii\web\Controller;

class PermissionController extends Controller
{
    public function actionIndex()
    {
        /** @var Auth $authClass */
        $authClass = Yii::$app->user->authClass;
        AuthValidate::run([$authClass::AUTH__PERMISSION, $authClass::AUTH__PERMISSION__INDEX]);

        $operations = (Module::getAuthOperationClass())::findAllOperations();
        return $this->render('index', [
            'operations' => $operations,
        ]);
    }
}
