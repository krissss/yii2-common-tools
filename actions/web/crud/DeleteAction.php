<?php

namespace kriss\actions\web\crud;

class DeleteAction extends AbstractModelAction
{
    /**
     * @deprecated
     * alias of doMethod
     * @var string|callable
     */
    public $deleteMethod;
    /**
     * @var string|callable
     */
    public $doMethod = 'delete';
    /**
     * @var string
     */
    public $operateMsg = '删除';

    public function init()
    {
        parent::init();
        if ($this->deleteMethod) {
            $this->doMethod = $this->deleteMethod;
        }
    }

    public function run($id)
    {
        $this->setModel($this->findModel($id, $this->controller));
        $this->doModelMethod(false);
        return $this->redirectAfterDoMethod();
    }
}
