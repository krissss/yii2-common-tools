<?php

namespace kriss\actions\rest\crud;

use kriss\actions\traits\ModelClassActionTrait;

class DetailAction extends AbstractAction
{
    use ModelClassActionTrait;

    public function run($id)
    {
        $model = $this->findModel($id, $this->controller);

        return $model;
    }
}
