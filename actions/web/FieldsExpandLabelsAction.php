<?php

namespace kriss\actions\web;

use Yii;
use yii\base\Action;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;

class FieldsExpandLabelsAction extends Action
{
    /**
     * table_name 的参数名
     * @var string
     */
    public $paramTableName = 'model';
    /**
     * id 的参数名
     * @var string
     */
    public $paramId = 'id';
    /**
     * ActiveRecord 的 namespace
     * 字符串或数组
     * @var string | array
     */
    public $activeRecordNS = 'common/models';

    public function run()
    {
        try {
            $request = Yii::$app->request;
            $tableName = $request->get($this->paramTableName);
            $id = $request->get($this->paramId);
            if (!$tableName) {
                throw new InvalidConfigException('必须传递 ' . $this->paramTableName);
            }
            if (!$id) {
                throw new InvalidConfigException('必须传递 ' . $this->paramId);
            }

            $modelClass = $this->getActiveClass($tableName);

            $model = $modelClass::findOne($id);
            if (!$model) {
                throw new InvalidConfigException($this->paramId . '为' . $id . '的数据不存在');
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
        foreach ($fields as $key => &$value) {
            if (isset($labels[$key])) {
                $value = $labels[$key];
            }
        }
        foreach ($expand as $key => &$value) {
            if (isset($labels[$key])) {
                $value = $labels[$key];
            }
        }
        return [
            'fields' => $fields,
            'expand' => $expand
        ];
    }
}