<?php

use yii\db\Migration;

class m160725_063957_worker extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // Logger table
        $this->createTable('{{%mods_worker}}', [
            'id' => $this->primaryKey(),
            'department' => $this->integer(1)->notNull(),
            'fio' => $this->integer(1)->notNull(),
            'photo' => $this->string(255)->notNull(),
            'flag1' => $this->string(255),
            'flag2' => $this->string(255),
            'flag3' => $this->string(255),
            'flag4' => $this->string(255),
            'flag5' => $this->string(255),
            'position' => $this->string(255)->notNull(),
            'phone' => $this->string(255),
            'phone_mobile' => $this->string(255),
            'email' => $this->string(255),
            'sort' => $this->integer(11)->notNull(),
        ], $tableOptions);

        // Indexes
        $this->createIndex('idx-mods_worker-sort', '{{%mods_worker}}', 'sort');

    }

    public function safeDown()
    {
        $this->dropTable('{{%mods_worker}}');
    }
}
