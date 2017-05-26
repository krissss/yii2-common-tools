<?php

namespace kriss\components\rest;

use yii\web\Link;

class Serializer extends \yii\rest\Serializer
{
    /**
     * @inheritdoc
     */
    public $collectionEnvelope = 'items';
    /**
     * @var bool|string
     */
    public $linksEnvelope = false;
    /**
     * @inheritdoc
     */
    public $metaEnvelope = 'pagination';

    /**
     * 验证失败的返回错误集合
     * @var string
     */
    public $modelErrorsLabel = 'errors';
    /**
     * model 返回的字段名
     * @var string
     */
    public $modelLabel = 'data';
    /**
     * models 返回的字段名
     * @var string
     */
    public $modelsLabel = 'items';


    /**
     * 调整：让 linksEnvelope 和 metaEnvelope 可以分开显示
     * @inheritdoc
     */
    protected function serializePagination($pagination)
    {
        $result = [];
        if($this->linksEnvelope){
            $result[$this->linksEnvelope] = Link::serialize($pagination->getLinks(true));
        }
        if($this->metaEnvelope){
            $result[$this->metaEnvelope] = [
                'totalCount' => $pagination->totalCount,
                'pageCount' => $pagination->getPageCount(),
                'currentPage' => $pagination->getPage() + 1,
                'perPage' => $pagination->getPageSize(),
            ];
        }
        return $result;
    }

    /**
     * 调整：model 错误时序列话结果调整
     * @inheritdoc
     */
    protected function serializeModelErrors($model)
    {
        $data = parent::serializeModelErrors($model);
        return [
            $this->modelErrorsLabel => $data
        ];
    }

    /**
     * 调整：把 model 放到 data 下，避免和 status 和 message 的冲突
     * @inheritdoc
     */
    protected function serializeModel($model)
    {
        $data = parent::serializeModel($model);
        return [
            $this->modelLabel => $data
        ];
    }

    /**
     * 调整：把 models 放到 data 下，避免和 status 和 message 的冲突
     * @inheritdoc
     */
    protected function serializeModels(array $models)
    {
        $data = parent::serializeModels($models);
        return [
            $this->modelsLabel => $data
        ];
    }
}