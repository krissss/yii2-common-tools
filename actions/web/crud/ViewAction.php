<?php

namespace kriss\actions\web\crud;

class ViewAction extends AbstractModelAction
{
    public function run($id)
    {
        $this->setModel($this->findModel($id, $this->controller));
        return $this->renderView();
    }
}
