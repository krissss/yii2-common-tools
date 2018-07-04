<?php

namespace kriss\actions\rest\crud;

use Yii;

class BatchOperateAction extends AbstractAction
{
    /**
     * @var string
     */
    public $idsAttribute = 'ids';
    /**
     * @var string
     */
    public $explodeBy = ',';
    /**
     * callable($idsArr, self $controller) : string
     * @var string|callable
     */
    public $doMethod;

    public function run()
    {
        $ids = Yii::$app->request->post($this->idsAttribute);
        if (!$ids) {
            return $this->validateError('ids 必传');
        }
        $idsArr = explode($this->explodeBy, $ids);

        return $this->doMethodOrCallback($this->doMethod, $this->controller, $idsArr, $this->controller);
    }
}
