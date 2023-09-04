<?php

use yii\db\Migration;

class m160722_064744_job extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // Logger table
        $this->createTable('{{%mods_job}}', [
            'id' => $this->primaryKey(),
            'department' => $this->string(255)->notNull(),
            'vacancy' => $this->string(255)->notNull(),
            'salary' => $this->string(255),
            'content' => $this->text()->notNull(),
            'sort' => $this->integer(11)->notNull(),
        ], $tableOptions);

        // Indexes
        $this->createIndex('idx-mods_job-sort', '{{%mods_job}}', 'sort');

    }

    public function safeDown()
    {
        $this->dropTable('{{%mods_job}}');
    }
}
