<?php

namespace kriss\modules\auth\migrations;

use yii\db\Migration;

class m170301_062150_auth extends Migration
{
    public $addColumn = true;
    public $userTable = '{{%admin}}';
    public $authAttribute = 'auth_role';

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%auth_operation}}', [
                'id' => $this->primaryKey(),
                'parent_id' => $this->integer()->notNull()->defaultValue(0)->comment('父权限'),
                'name' => $this->string()->notNull()->comment('权限操作名称'),
            ], $tableOptions . ' COMMENT=\'权限操作表\'');
        $this->createIndex('parent_id', '{{%auth_operation}}', 'parent_id');

        $this->createTable('{{%auth_role}}', [
                'id' => $this->primaryKey(),
                'name' => $this->string()->notNull()->comment('权限名称'),
                'description' => $this->string()->comment('权限描述'),
                'operation_list' => $this->text()->comment('权限操作列表'),
            ], $tableOptions . ' COMMENT=\'权限角色表\'');

        if($this->addColumn){
            $this->addColumn($this->userTable, $this->authAttribute, $this->string()->comment('角色'));
        }
    }

    public function down()
    {
        echo "m170301_062150_auth cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
