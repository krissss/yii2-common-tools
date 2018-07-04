<?php

namespace kriss\actions\web\crud;

use kriss\traits\WebControllerTrait;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class IndexAction extends Action
{
    use WebControllerTrait;

    /**
     * @var ActiveDataProvider
     */
    public $dataProvider;
    /**
     * @var Model
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
            if (!isset($this->dataProvider['class'])) {
                $this->dataProvider['class'] = ActiveDataProvider::className();
            }
            $dataProvider = Yii::createObject($this->dataProvider);
            $viewParams = [
                'dataProvider' => $dataProvider,
            ];
        } elseif ($this->searchModel) {
            $searchModel = Yii::createObject($this->searchModel);
            $searchMethod = $this->searchMethod;
            $dataProvider = $searchModel->$searchMethod(Yii::$app->request->get());
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
