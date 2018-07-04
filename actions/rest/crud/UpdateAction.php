<?php

namespace kriss\actions\rest\crud;

use Yii;

class UpdateAction extends AbstractAction
{
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

    public function run()
    {
        $request = Yii::$app->request;
        $id = $request->post($this->idAttribute);
        if (!$id) {
            return $this->validateError('id 必须');
        }

        $model = $this->findModel($id);

        $isLoadSuccess = $model->load($request->post(), '');
        if ($this->mustLoadPostData && !$isLoadSuccess) {
            return $this->validateError("缺少 {$this->idAttribute} 以外的其他参数");
        }

        if ($model->save()) {
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
