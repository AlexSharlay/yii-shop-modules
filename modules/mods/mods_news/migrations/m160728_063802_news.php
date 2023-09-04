<?php

use yii\db\Migration;

class m160728_063802_news extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%mods_news}}', [
            'id' => $this->primaryKey(),
            'col' => $this->integer(1),
            'row' => $this->integer(1),
            'title' => $this->string(255),
            'ico_title' => $this->string(255),
            'ico_color' => $this->integer(1),
            'image' => $this->string(255)->notNull(),
            'url' => $this->string(255),
            'url_target' => $this->integer(1),
            'published' => $this->integer(11),
        ], $tableOptions);

        // Indexes
        $this->createIndex('idx-mods_news-published', '{{%mods_news}}', 'published');

    }

    public function safeDown()
    {
        $this->dropTable('{{%mods_news}}');
    }
}
