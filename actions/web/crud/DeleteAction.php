<?php

namespace kriss\actions\web\crud;

use kriss\components\MessageAlert;
use kriss\tools\Fun;
use yii\db\ActiveRecord;

class DeleteAction extends AbstractAction
{
    /**
     * @var string|callable
     */
    public $deleteMethod = 'delete';

    public function run($id)
    {
        /** @var ActiveRecord $model */
        $model = $this->findModel($id);

        $result = $this->doMethodOrCallback($this->deleteMethod, $model);
        if ($result) {
            MessageAlert::set(['success' => '删除成功！']);
        } else {
            MessageAlert::set(['error' => '删除失败：' . Fun::formatModelErrors2String($model->errors)]);
        }

        return $this->actionPreviousRedirect($this->controller);
    }
}