<?php

namespace kriss\actions\web\crud;

class ModelOperateAction extends AbstractModelAction
{
    public function run($id)
    {
        $this->setModel($this->findModel($id, $this->controller));
        $this->doModelMethod(false);
        return $this->redirectPrevious();
    }
}
