<?php

namespace kriss\actions\web\crud;

use kriss\actions\helper\ActionTools;
use kriss\actions\traits\FlashMessageTrait;
use kriss\actions\traits\ModelClassActionTrait;

class ModelOperateAction extends AbstractAction
{
    use ModelClassActionTrait;
    use FlashMessageTrait;

    /**
     * @var string
     */
    public $doMethod;
    /**
     * @var string|array
     */
    public $successRedirect;

    public function run($id)
    {
        $model = $this->findModel($id, $this->controller);

        $result = ActionTools::invokeClassMethod($model, $this->doMethod);
        $this->setFlashMessage($result, $model);

        return $this->redirectPrevious();
    }
}
