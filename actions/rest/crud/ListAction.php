<?php

namespace kriss\actions\rest\crud;

use kriss\components\rest\ActiveDataProvider;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;

class ListAction extends Action
{
    /**
     * 注意：
     * 在使用 array 配置时如果要用到 比如： Yii::$app->user->id 时，请使用 callable，
     * 否则 Yii::$app->user->id 为空
     * @var string|array|callable
     */
    public $dataProvider;
    /**
     * 注意：
     * 在使用 array 配置时如果要用到 比如： Yii::$app->user->id 时，请使用 callable，
     * 否则 Yii::$app->user->id 为空
     * @var string|array|callable
     */
    public $searchModel;
    /**
     * @var string|callable
     */
    public $searchMethod = 'search';

    public function run()
    {
        if ($this->dataProvider) {
            if (is_array($this->dataProvider)) {
                if (!isset($this->dataProvider['class'])) {
                    $this->dataProvider['class'] = ActiveDataProvider::class;
                }
            }
            return Yii::createObject($this->dataProvider);
        } elseif ($this->searchModel) {
            $searchModel = Yii::createObject($this->searchModel);
            return $searchModel->{$this->searchMethod}(Yii::$app->request->get());
        } else {
            throw new InvalidConfigException('必须配置 dataProvider 或 searchModel');
        }
    }

}
