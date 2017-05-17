<?php

namespace kriss\modules\auth\models;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%auth_role}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $operation_list
 */
class AuthRole extends \yii\db\ActiveRecord
{
    public $_operations = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_role}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 64],
            [['description'], 'string', 'max' => 255],
            [['operation_list'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => '角色名称',
            'description' => '描述',
            'operation_list' => '权限列表',
        ];
    }

    public static function findName($ids) {
        $models = AuthRole::find()->select('name')->where(['id' => $ids])->asArray()->all();
        $nameArr = ArrayHelper::getColumn($models, 'name');
        return implode(',', $nameArr);
    }
}
