<?php

use yii\db\Schema;
use yii\db\Migration;

class m160520_133505_logger extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // Logger table
        $this->createTable('{{%logger_action}}', [
            'id' => $this->primaryKey(),
            'module' => $this->string(64)->notNull(),
            'controller' => $this->string(64)->notNull(),
            'action' => $this->string(64)->notNull(),
            'ip' => $this->string(16)->notNull(),
            'id_user' => $this->integer(11),
            'create' => $this->string(),
            'headers' => $this->text(),
            'get' => $this->text(),
            'post' => $this->text(),
        ], $tableOptions);

        // Indexes
        $this->createIndex('idx-logger_action-id_user', '{{%logger_action}}', 'id_user');

        // Foreign Keys
        $this->addForeignKey('fk-logger_action-id_user', '{{%logger_action}}', 'id_user', '{{%users}}', 'id', 'CASCADE', 'CASCADE');

    }

    public function safeDown()
    {
        $this->dropTable('{{%logger_action}}');
    }
}
