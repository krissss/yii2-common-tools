<?php

namespace kriss\actions\web\crud;

class CommonFormAction extends AbstractModelAction
{
    public function run()
    {
        $this->setModel($this->newModel());
        if ($this->loadPostData()) {
            $result = $this->doModelMethod(true);
            return $this->redirectAfterDoMethod($result);
        }
        return $this->renderView();
    }
}
