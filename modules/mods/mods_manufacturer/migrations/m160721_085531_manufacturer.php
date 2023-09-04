<?php

use yii\db\Migration;

class m160721_085531_manufacturer extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // Logger table
        $this->createTable('{{%mods_manufacturer}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'url' => $this->string(255),
            'ico' => $this->string(255)->notNull(),
            'sort' => $this->integer(11)->notNull(),
        ], $tableOptions);

        // Indexes
        $this->createIndex('idx-mods_manufacturer-sort', '{{%mods_manufacturer}}', 'sort');

    }

    public function safeDown()
    {
        $this->dropTable('{{%mods_manufacturer}}');
    }
}
