<?php

namespace kriss\actions\rest\crud;

class DetailAction extends AbstractAction
{
    /**
     * @var string
     */
    public $beforeRenderCallback;

    public function run($id)
    {
        $model = $this->findModel($id);

        $this->beforeRenderCallback && call_user_func($this->beforeRenderCallback, $model);

        return $model;
    }
}
