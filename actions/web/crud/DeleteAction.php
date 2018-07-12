<?php

namespace kriss\actions\web\crud;

use kriss\actions\traits\FlashMessageTrait;
use kriss\actions\traits\ModelClassActionTrait;

class DeleteAction extends AbstractAction
{
    use ModelClassActionTrait;
    use FlashMessageTrait;

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
        $model = $this->findModel($id, $this->controller);

        $result = $this->invokeClassMethod($model, $this->deleteMethod, $model);
        $this->setFlashMessage($result, $model);

        return $this->redirectPrevious();
    }
}
