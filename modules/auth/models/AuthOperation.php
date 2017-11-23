<?php

namespace kriss\modules\auth\models;

use kriss\modules\auth\Module;
use Yii;

/**
 * This is the model class for table "auth_operation".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 */
class AuthOperation extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'auth_operation';
    }

    public function rules()
    {
        return [
            [['parent_id'], 'integer'],
            [['name'], 'string', 'max' => 32]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * 获取所有权限
     * @return array
     */
    public static function findAllOperations()
    {
        $operations = [];
        /** @var AuthOperation[] $rootOperations */
        $rootOperations = static::find()->where(['parent_id' => 0])->all();
        foreach ($rootOperations as $rootOperation) {
            if (in_array($rootOperation->id, Module::getInstance()->skipAuthOptions)) {
                continue;
            }
            $operations[$rootOperation->id]['name'] = $rootOperation->getViewName();
        }
        /** @var AuthOperation[] $subOperations */
        $subOperations = static::find()->where(['!=', 'parent_id', 0])->all();
        foreach ($subOperations as $subOperation) {
            if (in_array($subOperation->id, Module::getInstance()->skipAuthOptions)) {
                continue;
            }
            $operations[$subOperation->parent_id]['sub'][$subOperation->name] = $subOperation->getViewName();
        }
        return $operations;
    }

    /**
     * 获得显示用的名字
     * @return string
     */
    public function getViewName()
    {
        /** @var Auth $authClass */
        $authClass = Yii::$app->user->authClass;
        return $authClass::getName($this->name);
    }

    /**
     * 获得显示用的名字
     * @param $name
     * @return mixed|string
     */
    public static function findViewName($name)
    {
        if ($name === 'all') {
            return '所有';
        }
        /** @var Auth $authClass */
        $authClass = Yii::$app->user->authClass;
        return $authClass::getName($name);
    }
}
