<?php

namespace kriss\actions\rest\crud;

use kriss\actions\traits\ModelClassActionTrait;
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
    public $successMsg = '修改成功';
    /**
     * 是否必须加载 post 里的数据，为 false 代表 post 里的数据可以为空
     * @var bool
     */
    public $mustLoadPostData = true;
    /**
     * 更新的操作
     * @var string
     */
    public $updateMethod = 'save';

    public function run()
    {
        $request = Yii::$app->request;
        $id = $request->post($this->idAttribute);
        if (!$id) {
            return $this->restValidateError('id 必须');
        }

        $model = $this->findModel($id, $this->controller);
        $isLoadSuccess = $model->load($request->post(), '');
        if ($this->mustLoadPostData && !$isLoadSuccess) {
            return $this->restValidateError("缺少 {$this->idAttribute} 以外的其他参数");
        }

        $result = $this->invokeClassMethod($model, $this->updateMethod);
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
