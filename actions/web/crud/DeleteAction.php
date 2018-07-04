<?php

namespace kriss\actions\web\crud;

use yii\db\ActiveRecord;

class DeleteAction extends AbstractAction
{
    /**
     * @var string|callable
     */
    public $deleteMethod = 'delete';
    /**
     * @var string
     */
    public $operateMsg = '删除';

    public function run($id)
    {
        /** @var ActiveRecord $model */
        $model = $this->findModel($id);

        $result = $this->doMethodOrCallback($this->deleteMethod, $model);
        $this->messageAlert($result, $model);

        return $this->redirectPrevious();
    }
}
