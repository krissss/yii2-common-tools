<?php

namespace kriss\actions\rest\crud;

use kriss\actions\traits\ToolsTrait;
use Yii;

class BatchOperateAction extends AbstractAction
{
    use ToolsTrait;

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
            return $this->restValidateError('ids 必传');
        }
        $idsArr = array_unique(array_filter(explode($this->explodeBy, $ids)));

        return $this->invokeClassMethod($this->controller, $this->doMethod, $idsArr);
    }
}
