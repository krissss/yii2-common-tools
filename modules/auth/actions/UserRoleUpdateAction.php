<?php

namespace kriss\modules\auth\actions;

use kriss\modules\auth\models\UpdateUserRole;
use kriss\modules\auth\tools\AuthValidate;
use Yii;
use yii\base\Action;
use yii\web\ForbiddenHttpException;

/**
 * 用户授权的操作
 */
class UserRoleUpdateAction extends Action
{
    /**
     * auth permission name
     * @var string
     */
    public $permissionName;
    /**
     * it can be redirect
     * @var string|callable
     */
    public $successCallback;
    /**
     * @var bool
     */
    public $isRenderAjax = false;
    /**
     * view name
     * @var string
     */
    public $view = '@krissAuth/views/user-role/_update_role';
    /**
     * query parameter: user ID
     * @var string
     */
    public $queryParameter = 'id';

    public function run()
    {
        Yii::setAlias('@krissAuth', dirname(__DIR__));
        $id = Yii::$app->request->get($this->queryParameter);
        if (!$id) {
            throw new ForbiddenHttpException('need ' . $this->queryParameter . ' parameter');
        }

        $this->checkAuth($id);

        $updateUserRole = new UpdateUserRole([
            'userId' => $id,
        ]);

        if ($updateUserRole->load(Yii::$app->request->post())) {
            $result = $updateUserRole->updateUserRole();
            return call_user_func($this->successCallback, $this, $result);
        };

        $updateUserRole->initData();
        return $this->renderHtml($updateUserRole);
    }

    /**
     * 检查操作权限
     * @param $id
     * @throws ForbiddenHttpException
     */
    protected function checkAuth($id)
    {
        if ($this->permissionName) {
            AuthValidate::run($this->permissionName);
        }
        /** @var \kriss\modules\auth\components\User $user */
        $user = Yii::$app->user;
        if ($id == $user->superAdminId || $id == $user->id) {
            throw new ForbiddenHttpException(Yii::t('kriss', '没有访问权限'));
        }
    }

    /**
     * @param $model
     * @return string
     */
    protected function renderHtml($model)
    {
        $params = [
            'model' => $model,
        ];
        if ($this->isRenderAjax) {
            return $this->controller->renderAjax($this->view, $params);
        }
        return $this->controller->render($this->view, $params);
    }
}
