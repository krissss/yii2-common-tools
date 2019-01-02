<?php

namespace kriss\actions\web\crud;

use Yii;

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
    public $operateMsg;

    public function init()
    {
        parent::init();
        if (!isset($this->operateMsg)) {
            $this->operateMsg = Yii::t('kriss', '删除');
        }
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
