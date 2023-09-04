<?php

use yii\db\Migration;

class m160808_094503_seo extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // Logger table
        $this->createTable('{{%mods_seo}}', [
            'id' => $this->primaryKey(),
            'url' => $this->string(255)->notNull(),
            'note' => $this->string(255),
            'seo_title' => $this->string(255),
            'seo_keyword' => $this->string(255),
            'seo_desc' => $this->string(255),
        ], $tableOptions);


    }

    public function safeDown()
    {
        $this->dropTable('{{%mods_seo}}');
    }
}
