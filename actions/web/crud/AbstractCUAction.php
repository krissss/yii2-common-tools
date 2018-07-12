<?php

namespace kriss\actions\web\crud;

use kriss\actions\helper\ActionTools;
use kriss\actions\traits\AjaxViewTrait;
use kriss\actions\traits\FlashMessageTrait;
use kriss\actions\traits\ModelClassActionTrait;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecord;

abstract class AbstractCUAction extends AbstractAction
{
    use ModelClassActionTrait;
    use FlashMessageTrait;
    use AjaxViewTrait;

    /**
     * @var string
     */
    public $doMethod = 'save';
    /**
     * @var callable
     */
    public $beforeValidateCallback;
    /**
     * @var callable
     */
    public $beforeRenderCallback;

    /**
     * 新增或修改操作
     * @param $model ActiveRecord|Model
     * @return mixed
     * @throws InvalidConfigException
     */
    protected function createOrUpdate($model)
    {
        if ($model->load(Yii::$app->request->post())) {

            $this->beforeValidateCallback && call_user_func($this->beforeValidateCallback, $model);

            if ($this->doMethod === 'save' || $model->validate()) {
                $result = ActionTools::invokeClassMethod($model, $this->doMethod);
                $this->setFlashMessage($result, $model);
            }

            return $this->redirectPrevious();
        }

        $this->beforeRenderCallback && call_user_func($this->beforeRenderCallback, $model);

        return $this->render($this->controller, [
            'model' => $model
        ]);
    }
}
