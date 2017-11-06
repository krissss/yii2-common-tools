<?php

namespace kriss\actions\web;

use Yii;
use yii\base\Action;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use yii\web\ForbiddenHttpException;

class FieldsExpandLabelsAction extends Action
{
    /**
     * @var bool
     */
    public $canUse = YII_DEBUG;
    /**
     * table_name 的参数名
     * @var string
     */
    public $paramTableName = 'model';
    /**
     * ActiveRecord 的 namespace
     * 字符串或数组
     * @var string | array
     */
    public $activeRecordNS = 'common/models';

    public function run()
    {
        if (!$this->canUse) {
            throw new ForbiddenHttpException('不可用');
        }
        try {
            $request = Yii::$app->request;
            $tableName = $request->get($this->paramTableName);
            if (!$tableName) {
                throw new InvalidConfigException('必须传递 ' . $this->paramTableName);
            }

            $modelClass = $this->getActiveClass($tableName);
            $model = $modelClass::find()->limit(1)->one();
            if (!$model) {
                throw new InvalidConfigException('请联系后台人员补充至少一条记录');
            }

            return $this->getFieldsAndExpandLabels($model);
        } catch (Exception $exception) {
            return [
                'error' => $exception->getMessage()
            ];
        }
    }

    /**
     * 获取 table 对应的 AC
     * @param $tableName
     * @return string|ActiveRecord
     * @throws InvalidConfigException
     */
    protected function getActiveClass($tableName)
    {
        $modelClassName = Inflector::camelize($tableName);
        if (is_array($this->activeRecordNS)) {
            foreach ($this->activeRecordNS as $ns) {
                $modelClass = str_replace('\\', '/', $ns) . '/' . $modelClassName;
                if (class_exists($modelClass)) {
                    break;
                }
            }
            throw new InvalidConfigException($this->paramTableName . ' 错误，不存在该类');
        } else {
            $modelClass = rtrim(str_replace('/', '\\', $this->activeRecordNS), '\\') . '\\' . $modelClassName;
            if (!class_exists($modelClass)) {
                throw new InvalidConfigException($this->paramTableName . ' 错误，不存在该类');
            }
        }
        $model = new $modelClass;
        if (!$model instanceof ActiveRecord) {
            throw new InvalidConfigException('activeRecordNS 配置错误，该模型必须');
        }
        return $modelClass;
    }

    /**
     * 获取模型的 fields 和 expand 的说明
     * @param $model ActiveRecord
     * @return array
     */
    protected function getFieldsAndExpandLabels($model)
    {
        $fields = $model->fields();
        $expand = $model->extraFields();
        $labels = $model->attributeLabels();

        $fields = $this->solveFields($fields, $labels);
        $expand = $this->solveFields($expand, $labels);

        return [
            'fields' => $fields,
            'expand' => $expand
        ];
    }

    /**
     * @param $fields
     * @param $labels
     * @return mixed
     */
    protected function solveFields($fields, $labels)
    {
        $data = [];
        foreach ($fields as $field => $definition) {
            if (is_int($field)) {
                $field = $definition;
            }
            if (!is_string($definition)) {
                $definition = $field;
            }
            if (isset($labels[$field])) {
                $data[$field] = $labels[$field];
            } else {
                $data[$field] = $definition;
            }
        }
        return $data;
    }
}