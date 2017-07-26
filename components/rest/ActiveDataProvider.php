<?php

namespace kriss\components\rest;

use Yii;
use yii\data\Pagination;

class ActiveDataProvider extends \yii\data\ActiveDataProvider
{
    /**
     * 修改：修改 pagination 的默认值，且不影响原应用
     * @inheritdoc
     */
    public function setPagination($value)
    {
        if (is_array($value)) {
            $value = array_merge([
                'pageParam' => 'page',
                'pageSizeParam' => 'per_page',
                'validatePage' => false,
            ], $value);
            $config = ['class' => Pagination::className()];
            if ($this->id !== null) {
                $config['pageParam'] = $this->id . '-page';
                $config['pageSizeParam'] = $this->id . '-per-page';
            }
            $value = Yii::createObject(array_merge($config, $value));
        }
        parent::setPagination($value);
    }
}