<?php
/**
 * 用户授权的操作
 */

namespace kriss\modules\auth\actions;

use kriss\modules\auth\models\UpdateUserRole;
use kriss\modules\auth\tools\AuthValidate;
use Yii;
use yii\base\Action;
use yii\web\ForbiddenHttpException;

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
     * see ajax example under @kriss\modules\auth\examples\views\_update_role.php
     * @var string
     */
    public $view;
    /**
     * query parameter: user ID
     * @var string
     */
    public $queryParameter = 'id';

    public function run()
    {
        $id = Yii::$app->request->get('id');
        if (!$id) {
            throw new ForbiddenHttpException('need id parameter');
        }
        if ($this->permissionName) {
            AuthValidate::run($this->permissionName);
        }
        /** @var \kriss\modules\auth\components\User $user */
        $user = Yii::$app->user;
        if ($id == $user->superAdminId || $id == $user->id){
            throw new ForbiddenHttpException(Yii::t('app', 'No Auth'));
        }

        $updateUserRole = new UpdateUserRole([
            'userClass' => $this->userClass,
            'userClassAuthRoleAttribute' => $this->userClassAuthRoleAttribute,
            'userId' => $id
        ]);

        if ($updateUserRole->load(Yii::$app->request->post())) {
            $result = $updateUserRole->updateUserRole();
            return call_user_func($this->successCallback, $this, $result);
        };

        $updateUserRole->initData();
        return $this->renderHtml($updateUserRole);
    }

    /**
     * @param $model
     * @return string
     */
    protected function renderHtml($model)
    {
        $params = [
            'model' => $model
        ];
        if ($this->isRenderAjax) {
            return $this->controller->renderAjax($this->view, $params);
        }
        return $this->controller->render($this->view, $params);
    }
}