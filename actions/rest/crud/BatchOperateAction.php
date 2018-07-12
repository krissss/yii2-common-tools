<?php

namespace kriss\actions\rest\crud;

use kriss\actions\helper\ActionTools;
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
            return ActionTools::restValidateError('ids 必传');
        }
        $idsArr = array_unique(array_filter(explode($this->explodeBy, $ids)));

        return ActionTools::invokeClassMethod($this->controller, $this->doMethod, $idsArr);
    }
}
