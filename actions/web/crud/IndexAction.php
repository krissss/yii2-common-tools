<?php

namespace kriss\actions\web\crud;

use kriss\traits\WebControllerTrait;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;

class IndexAction extends Action
{
    use WebControllerTrait;

    /**
     * @var string|array|callable
     */
    public $dataProvider;
    /**
     * @var string|array|callable
     */
    public $searchModel;
    /**
     * @var string|callable
     */
    public $searchMethod = 'search';
    /**
     * @var string
     */
    public $view = 'index';

    public function run()
    {
        $this->rememberUrl($this->controller);

        if ($this->dataProvider) {
            if (is_array($this->dataProvider)) {
                if (!isset($this->dataProvider['class'])) {
                    $this->dataProvider['class'] = ActiveDataProvider::class;
                }
            }
            $dataProvider = Yii::createObject($this->dataProvider);
            $viewParams = [
                'dataProvider' => $dataProvider,
            ];
        } elseif ($this->searchModel) {
            $searchModel = Yii::createObject($this->searchModel);
            $dataProvider = $searchModel->{$this->searchMethod}(Yii::$app->request->get());
            $viewParams = [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ];
        } else {
            throw new InvalidConfigException('必须配置 dataProvider 或 searchModel');
        }

        return $this->controller->render($this->view, $viewParams);
    }

}