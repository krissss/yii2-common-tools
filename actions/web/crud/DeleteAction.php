<?php

namespace kriss\actions\web\crud;

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
        $model = $this->findModel($id);

        $result = $this->doMethodOrCallback($this->deleteMethod, $model);
        $this->messageAlert($result, $model);

        return $this->redirectPrevious();
    }
}
