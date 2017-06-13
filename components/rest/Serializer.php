<?php

namespace kriss\components\rest;

use yii\base\Arrayable;
use yii\base\Model;
use yii\data\DataProviderInterface;
use yii\web\Link;

class Serializer extends \yii\rest\Serializer
{
    /**
     * @inheritdoc
     */
    public $collectionEnvelope = 'data';
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
     * 普通的所有数据返回的字段名
     * @var string
     */
    public $dataCommonLabel = 'data';

    /**
     * 总数
     * @var string
     */
    public $paginationTotalCount = 'totalCount';

    /**
     * 总页数
     * @var string
     */
    public $paginationPageCount = 'pageCount';

    /**
     * 当前页
     * @var string
     */
    public $paginationCurrentPage = 'currentPage';

    /**
     * 每页显示数量
     * @var string
     */
    public $paginationPageSize = 'perPage';

    /**
     * 调整：修改通用的数据序列化
     * @inheritdoc
     */
    public function serialize($data)
    {
        if ($data instanceof Model && $data->hasErrors()) {
            return $this->serializeModelErrors($data);
        } elseif ($data instanceof Arrayable) {
            return $this->serializeModel($data);
        } elseif ($data instanceof DataProviderInterface) {
            return $this->serializeDataProvider($data);
        } else {
            if ($this->dataCommonLabel) {
                return [$this->dataCommonLabel => $data];
            }
            return $data;
        }
    }

    /**
     * 调整：让 linksEnvelope 和 metaEnvelope 可以分开显示
     * @inheritdoc
     */
    protected function serializePagination($pagination)
    {
        $result = [];
        if ($this->linksEnvelope) {
            $result[$this->linksEnvelope] = Link::serialize($pagination->getLinks(true));
        }
        if ($this->metaEnvelope) {
            $result[$this->metaEnvelope] = [
                $this->paginationTotalCount => $pagination->totalCount,
                $this->paginationPageCount => $pagination->getPageCount(),
                $this->paginationCurrentPage => $pagination->getPage() + 1,
                $this->paginationPageSize => $pagination->getPageSize(),
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
}