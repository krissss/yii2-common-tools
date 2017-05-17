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
        $this->response->setStatusCode(422, 'Data Validation Failed.');
        $result = [];
        foreach ($model->getFirstErrors() as $name => $message) {
            $result['errors'][] = [
                'field' => $name,
                'message' => $message,
            ];
        }
        return $result;
    }
}