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
     * ids 是否是数组，如果不是，会进行 explode 切割为数组
     * @var bool
     */
    public $isIdsArr = false;
    /**
     * isIdsArr 为 false 时，用来切割字符串的分割符
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
        $ids = $this->getRequestIds();
        if (!$ids) {
            return ActionTools::restValidateError("{$this->idsAttribute} 必传");
        }
        $idsArr = $this->transIdsToArr($ids);

        return ActionTools::invokeClassMethod($this->controller, $this->doMethod, $idsArr);
    }

    /**
     * @return array|mixed
     */
    protected function getRequestIds()
    {
        return Yii::$app->request->post($this->idsAttribute);
    }

    /**
     * @param $ids
     * @return array
     */
    protected function transIdsToArr($ids)
    {
        if (!$this->isIdsArr) {
            $idsArr = array_unique(array_filter(explode($this->explodeBy, $ids)));
        } else {
            $idsArr = $ids;
        }
        return $idsArr;
    }
}
