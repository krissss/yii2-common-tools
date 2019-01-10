<?php

namespace kriss\modules\auth\controllers;

use kriss\modules\auth\models\Auth;
use kriss\modules\auth\models\AuthRole;
use kriss\modules\auth\models\AuthRoleSearch;
use kriss\modules\auth\Module;
use kriss\modules\auth\tools\AuthValidate;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class RoleController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'delete' => ['post'],
            ],
        ];

        return $behaviors;
    }

    public function actionIndex()
    {
        /** @var Auth $authClass */
        $authClass = Yii::$app->user->authClass;
        AuthValidate::run([$authClass::AUTH__ROLE, $authClass::AUTH__ROLE__INDEX]);

        Url::remember();

        $class = Module::getAuthRoleSearchClass();
        /** @var AuthRoleSearch $searchModel */
        $searchModel = new $class();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        /** @var Auth $authClass */
        $authClass = Yii::$app->user->authClass;
        AuthValidate::run([$authClass::AUTH__ROLE, $authClass::AUTH__ROLE__VIEW]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'operations' => (Module::getAuthOperationClass())::findAllOperations(),
        ]);
    }

    public function actionCreate()
    {
        /** @var Auth $authClass */
        $authClass = Yii::$app->user->authClass;
        AuthValidate::run([$authClass::AUTH__ROLE, $authClass::AUTH__ROLE__CREATE]);

        $class = Module::getAuthRoleClass();
        /** @var AuthRole $model */
        $model = new $class();

        if ($model->load(Yii::$app->request->post())) {
            $operations = $this->prepareOperations($model, Yii::$app->request->post());
            $model->operation_list = implode(';', $operations);
            $model->save(false);
            Yii::$app->session->setFlash('success', Yii::t('kriss', '创建成功'));
            return $this->redirect(Url::previous());
        } else {
            $operations = (Module::getAuthOperationClass())::findAllOperations();

            return $this->render('create_update', [
                'model' => $model,
                'operations' => $operations,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        /** @var Auth $authClass */
        $authClass = Yii::$app->user->authClass;
        AuthValidate::run([$authClass::AUTH__ROLE, $authClass::AUTH__ROLE__UPDATE]);

        if (!(Module::getAuthRoleClass())::canLoginUserModify($id)) {
            throw new ForbiddenHttpException(Yii::t('app', Yii::t('kriss', '没有访问权限')));
        }

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $operations = $this->prepareOperations($model, Yii::$app->request->post());
            $model->operation_list = implode(';', $operations);

            $model->save(false);
            Yii::$app->session->setFlash('success', Yii::t('kriss', '更新成功'));
            return $this->redirect(Url::previous());
        } else {
            $operations = (Module::getAuthOperationClass())::findAllOperations();

            //generate selected operations
            $model->_operations = explode(';', $model->operation_list);

            return $this->render('create_update', [
                'model' => $model,
                'operations' => $operations,
            ]);
        }
    }

    public function actionDelete($id)
    {
        /** @var Auth $authClass */
        $authClass = Yii::$app->user->authClass;
        AuthValidate::run([$authClass::AUTH__ROLE, $authClass::AUTH__ROLE__DELETE]);

        if (!(Module::getAuthRoleClass())::canLoginUserModify($id)) {
            throw new ForbiddenHttpException(Yii::t('app', Yii::t('kriss', '没有访问权限')));
        }

        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', Yii::t('kriss', '删除成功'));

        return $this->redirect(Url::previous());
    }

    /**
     * @param $id
     * @return AuthRole
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = (Module::getAuthRoleClass())::find()->andWhere(['id' => $id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * prepare Operations
     * @param AuthRole $model
     * @param array $post
     * @return array
     */
    protected function prepareOperations($model, $post)
    {
        $formName = $model->formName();
        return (isset($post[$formName]['_operations']) &&
            is_array($post[$formName]['_operations'])) ? $post[$formName]['_operations'] : [];
    }
}
