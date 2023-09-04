<?php

use yii\db\Migration;

class m160728_101622_review extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // Logger table
        $this->createTable('{{%mods_review}}', [
            'id' => $this->primaryKey(),
            'mark' => $this->integer(5)->notNull(),
            'name' => $this->string(255)->notNull(),
            'city' => $this->string(255),
            'desc' => $this->string(255)->notNull(),
            'date' => $this->integer(11)->notNull(),
            'published' => $this->integer(1)->notNull(),
        ], $tableOptions);

        // Indexes
        $this->createIndex('idx-mods_review-published', '{{%mods_review}}', 'published');
        $this->createIndex('idx-mods_review-date', '{{%mods_review}}', 'date');

    }

    public function safeDown()
    {
        $this->dropTable('{{%mods_review}}');
    }
}
