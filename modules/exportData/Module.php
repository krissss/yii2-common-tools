<?php

namespace kriss\modules\exportData;

/**
 * export data module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'kriss\modules\exportData\controllers';
    /**
     * @inheritdoc
     */
    public $defaultRoute = 'get';
    /**
     * auth token param
     * @var string
     */
    public $authTokenParam = 'token';
    /**
     * auth token value
     * @var string
     */
    public $authToken = 'suiyitianxie';
    /**
     * ActiveRecord Class to export namespace
     * like '\common\models'
     * @var string
     */
    public $exportDataNameSpace;
    /**
     * ActiveRecord Class Full Name
     * if $exportTableClassNameSpace is null
     * this should be class with namespace
     * otherwise it can be Only Class Name under $exportTableClassNameSpace
     * @var array
     */
    public $exportData = [];
    /**
     * @var string
     */
    public $csvSuffix = '.csv';
    /**
     * @var string
     */
    public $zipSuffix = '.data';
}
