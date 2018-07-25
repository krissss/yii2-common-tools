<?php

namespace kriss\actions\web\crud;

use kriss\actions\helper\ActionTools;

class FormOperateAction extends AbstractModelAction
{
    /**
     * @var string
     */
    public $modelIdAttribute = 'id';

    public function run($id)
    {
        ActionTools::generateYiiObjectConfig($this->modelClass, [$this->modelIdAttribute => $id]);
        $this->setModel($this->newModel());
        if ($this->loadPostData()) {
            $result = $this->doModelMethod(true);
            return $this->redirectAfterDoMethod($result);
        }
        return $this->renderView();
    }
}
