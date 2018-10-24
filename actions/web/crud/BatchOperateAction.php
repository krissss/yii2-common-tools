<?php

namespace kriss\actions\web\crud;

use kriss\actions\helper\ActionTools;
use kriss\components\MessageAlert;
use kriss\traits\WebControllerTrait;
use Yii;

class BatchOperateAction extends \kriss\actions\rest\crud\BatchOperateAction
{
    use WebControllerTrait;

    /**
     * @var string
     */
    public $idsAttribute = 'keys';
    /**
     * @var bool
     */
    public $isIdsArr = true;
    /**
     * @var string
     */
    public $operateMsg;
    /**
     * @var string|array
     */
    public $successRedirect;

    public function init()
    {
        if (!isset($this->operateMsg)) {
            $this->operateMsg = Yii::t('kriss', '批量操作');
        }

        parent::init();
    }

    public function run()
    {
        $ids = $this->getRequestIds();
        if (!$ids) {
            MessageAlert::error("{$this->idsAttribute} 必传");
            return $this->redirectPrevious();
        }
        $idsArr = $this->transIdsToArr($ids);

        $result = ActionTools::invokeClassMethod($this->controller, $this->doMethod, $idsArr);

        $totalCount = count($idsArr);
        if (is_int($result)) {
            if ($result == $totalCount) {
                MessageAlert::success("{$this->operateMsg}成功，共处理:{$result}条数据");
            } else {
                $noSolveCount = $totalCount - $result;
                MessageAlert::warning("{$this->operateMsg}成功，共处理:{$result}条数据，未处理{$noSolveCount}条数据");
            }
        } elseif ($result === false || is_string($result)) {
            if ($result === false) {
                $result = '请联系管理员';
            }
            MessageAlert::error("{$this->operateMsg}失败，{$result}");
        }

        return $this->redirectPrevious();
    }

    /**
     * 跳转到前一个页面
     */
    public function redirectPrevious()
    {
        return $this->actionPreviousRedirect($this->controller);
    }
}
