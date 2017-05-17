<?php

namespace kriss\modules\auth\controllers;

use kriss\modules\auth\models\Auth;
use kriss\modules\auth\tools\AuthValidate;
use Yii;
use kriss\modules\auth\models\AuthRole;
use kriss\modules\auth\models\AuthRoleSearch;
use kriss\modules\auth\models\AuthOperation;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;

class RoleController extends Controller
{
    const SUPER_ADMIN_ID = 1;

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
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
        AuthValidate::run($authClass::ROLE_VIEW);

        Url::remember();

        $searchModel = new AuthRoleSearch();
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
        AuthValidate::run($authClass::ROLE_VIEW);

        $model = $this->findModel($id);

        $strOperation = '';
        $i = 0;
        if ($model->operation_list) {
            $arrayOperation = explode(';', $model->operation_list);
            foreach ($arrayOperation as $item) {
                $strOperation .= AuthOperation::findViewName($item) . ' | ';
                $i++;
                if ($i % 5 == 0)
                    $strOperation .= "<br>";
            }
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
            'strOperation' => $strOperation,
        ]);
    }

    public function actionCreate()
    {
        /** @var Auth $authClass */
        $authClass = Yii::$app->user->authClass;
        AuthValidate::run($authClass::ROLE_CREATE);

        $model = new AuthRole();

        if ($model->load(Yii::$app->request->post())) {
            $operations = $this->prepareOperations(Yii::$app->request->post());
            $model->operation_list = implode(';', $operations);

            $model->save(false);
            Yii::$app->session->setFlash('success', '创建成功');
            return $this->redirect(Url::previous());
        } else {
            $operations = AuthOperation::findAllOperations();

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
        AuthValidate::run($authClass::ROLE_UPDATE);
        if ($id == static::SUPER_ADMIN_ID) throw new ForbiddenHttpException(Yii::t('app', 'No Auth'));

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $operations = $this->prepareOperations(Yii::$app->request->post());
            $model->operation_list = implode(';', $operations);

            $model->save(false);
            Yii::$app->session->setFlash('success', '更新成功');
            return $this->redirect(Url::previous());
        } else {
            $operations = AuthOperation::findAllOperations();

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
        AuthValidate::run($authClass::ROLE_DELETE);
        if ($id == static::SUPER_ADMIN_ID) throw new ForbiddenHttpException(Yii::t('app', 'No Auth'));

        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', '删除成功');

        return $this->redirect(Url::previous());
    }

    /**
     * @param $id
     * @return AuthRole
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = AuthRole::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * prepare Operations
     * @param array $post
     * @return array
     */
    protected function prepareOperations($post)
    {
        return (isset($post['AuthRole']['_operations']) &&
            is_array($post['AuthRole']['_operations'])) ? $post['AuthRole']['_operations'] : [];
    }
}
