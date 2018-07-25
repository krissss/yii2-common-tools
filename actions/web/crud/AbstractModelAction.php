<?php

namespace kriss\actions\web\crud;

use kriss\actions\helper\ActionTools;
use kriss\actions\traits\FlashMessageTrait;
use kriss\actions\traits\ModelClassActionTrait;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\db\BaseActiveRecord;

class AbstractModelAction extends AbstractAction
{
    use ModelClassActionTrait;
    use FlashMessageTrait;

    /**
     * @var string
     */
    public $doMethod;
    /**
     * @var callable
     */
    public $beforeValidateCallback;
    /**
     * @var callable
     */
    public $beforeRenderCallback;
    /**
     * @var string|array
     */
    public $successRedirect;
    /**
     * @var bool
     */
    public $isAjax = true;
    /**
     * @var string
     */
    public $view;

    /**
     * @var false|Model
     */
    private $_model = false;

    /**
     * @param Model $model
     * @see ModelClassActionTrait::newModel()
     * @see ModelClassActionTrait::findModel()
     */
    protected function setModel(Model $model)
    {
        $this->_model = $model;
    }

    /**
     * @return Model
     */
    private function getModelInner()
    {
        if ($this->_model === false) {
            throw new Exception('必须先调用 $this->setModel($model)');
        }
        return $this->_model;
    }

    /**
     * 加载 post 数据
     * @return mixed
     */
    protected function loadPostData()
    {
        return $this->getModelInner()->load(Yii::$app->request->post());
    }

    /**
     * 执行方法操作
     * @param bool $validate 是否进行数据校验
     * @return bool|mixed
     * @throws \yii\base\InvalidConfigException
     */
    protected function doModelMethod($validate = true)
    {
        $model = $this->getModelInner();
        $result = true;
        if ($validate) {
            $this->beforeValidateCallback && call_user_func($this->beforeValidateCallback, $model);

            $result = $this->getModelInner()->validate();
        }
        if ($result) {
            if ($this->doMethod == 'save' && $model instanceof BaseActiveRecord) {
                $result = $model->save(false);
            } else {
                $result = ActionTools::invokeClassMethod($model, $this->doMethod);
            }
        }
        $this->setFlashMessage($result, $model);
        return $result;
    }

    /**
     * 渲染视图
     * @return mixed
     */
    protected function renderView()
    {
        $model = $this->getModelInner();

        $this->beforeRenderCallback && call_user_func($this->beforeRenderCallback, $model);

        $renderMethod = $this->isAjax ? 'renderAjax' : 'render';
        return $this->controller->$renderMethod($this->view, [
            'model' => $model,
        ]);
    }

    /**
     * 执行操作之后的操作，一般都是 redirect
     * @param bool $result
     * @return false|\yii\web\Response
     */
    protected function redirectAfterDoMethod($result = null)
    {
        // 非 ajax ，验证或者结果有误的情况下，应该回到表单页面
        if (!$this->isAjax && $result === false) {
            return $this->renderView();
        }
        return $this->successRedirect ? $this->controller->redirect($this->successRedirect) : $this->redirectPrevious();
    }
}
