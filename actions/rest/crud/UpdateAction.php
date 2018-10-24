<?php

namespace kriss\actions\rest\crud;

use kriss\actions\helper\ActionTools;
use kriss\actions\traits\ModelClassActionTrait;
use kriss\traits\KrissTranslationTrait;
use Yii;

class UpdateAction extends AbstractAction
{
    use ModelClassActionTrait;

    /**
     * @var string
     */
    public $idAttribute = 'id';
    /**
     * 是否返回修改后的 model
     * @var bool
     */
    public $returnModel = true;
    /**
     * 在 $returnModel 为 false 时返回的文字
     * @var string
     */
    public $successMsg;
    /**
     * 是否必须加载 post 里的数据，为 false 代表 post 里的数据可以为空
     * @var bool
     */
    public $mustLoadPostData = true;
    /**
     * @deprecated
     * alias of doMethod
     * @var string|callable
     */
    public $updateMethod;
    /**
     * @var string|callable
     */
    public $doMethod = 'save';

    public function init()
    {
        if (!isset($this->successMsg)) {
            $this->successMsg = Yii::t('kriss', '修改成功');
        }

        parent::init();
        if ($this->updateMethod) {
            $this->doMethod = $this->updateMethod;
        }
    }

    public function run()
    {
        $request = Yii::$app->request;
        $id = $request->post($this->idAttribute);
        if (!$id) {
            return ActionTools::restValidateError('id 必须');
        }

        $model = $this->findModel($id, $this->controller);
        $isLoadSuccess = $model->load($request->post(), '');
        if ($this->mustLoadPostData && !$isLoadSuccess) {
            return ActionTools::restValidateError("缺少 {$this->idAttribute} 以外的其他参数");
        }

        $result = ActionTools::invokeClassMethod($model, $this->doMethod);
        if ($result !== false) {
            if ($this->returnModel) {
                $model->refresh();
                return $model;
            } else {
                return $this->successMsg;
            }
        }
        return $model;
    }
}
