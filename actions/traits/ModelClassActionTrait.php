<?php

namespace kriss\actions\traits;

use kriss\actions\helper\ActionTools;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

trait ModelClassActionTrait
{
    /**
     * 在使用 array 配置时如果要用到 比如： Yii::$app->user->id 时，请使用 callable，
     * 否则 Yii::$app->user->id 为空，
     * 也可以通过配置 AutoSetUserTrait 中的属性实现
     * @see AutoSetUserTrait
     * @var string|array|callable
     */
    public $modelClass;
    /**
     * 场景
     * @var string
     */
    public $scenario = null;
    /**
     * 是否加载默认值
     * @var bool
     */
    public $loadDefaultValue = true;
    /**
     * 根据id查找一个模型
     * @see ModelClassActionTrait::findModel
     * @var string|callable
     */
    public $findModel;
    /**
     * findModel 时额外的条件
     * @see Query::andWhere()
     * @var string|array
     */
    public $findModelCondition;
    /**
     * findModel 是否检查用户归属
     * @var bool
     */
    public $isFindModelCheckUser = false;
    /**
     * 检查时用户id字段
     * @var string
     */
    public $checkUserIdAttribute = 'user_id';
    /**
     * 模型的主键
     * @var string
     */
    public $modelClassPrimaryKey = 'id';
    /**
     * 未找到数据的提示信息
     * @var string
     */
    public $msgNoRecord = 'No Record';
    /**
     * 找到的数据不属于该用户时的提示信息
     * @var string
     */
    public $msgRecordNotAllowed = 'Record Not Allowed';

    /**
     * new 一个 model
     * @return Model|ActiveRecord
     * @throws InvalidConfigException
     */
    protected function newModel()
    {
        /** @var Model $model */
        $model = Yii::createObject($this->modelClass);

        $this->scenario && $model->setScenario($this->scenario);
        if ($this->loadDefaultValue && $model instanceof ActiveRecord) {
            $model->loadDefaultValues();
        }

        return $model;
    }

    /**
     * 查找一个 model
     * @param $id
     * @param $controller
     * @return ActiveRecord
     * @throws ForbiddenHttpException
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    protected function findModel($id, $controller)
    {
        if ($this->findModel) {
            $model = ActionTools::invokeClassMethod($controller, $this->findModel, $id);
        } else {
            if (!$this->findModelCondition) {
                $model = call_user_func([$this->modelClass, 'findOne'], $id);
            } else {
                /** @var ActiveRecord $modelClass */
                $modelClass = $this->modelClass;
                $model = $modelClass::find()
                    ->where([$this->modelClassPrimaryKey => $id])
                    ->andWhere($this->findModelCondition)
                    ->one();
            }
        }

        if (!$model) {
            throw new NotFoundHttpException($this->msgNoRecord);
        }
        if ($this->isFindModelCheckUser) {
            if ($model->{$this->checkUserIdAttribute} != Yii::$app->user->id) {
                throw new ForbiddenHttpException($this->msgRecordNotAllowed);
            }
        }

        $this->scenario && $model->setScenario($this->scenario);
        $this->loadDefaultValue && $model->loadDefaultValues();

        return $model;
    }
}
