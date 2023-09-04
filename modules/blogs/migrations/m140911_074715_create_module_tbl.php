<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * CLass m140526_193056_create_module_tbl
 * @package common\modules\blogs\migrations
 *
 * Create module tables.
 *
 * Will be created 1 table:
 * - `{{%blogs}}` - Blogs table.
 * - `{{%blogs_category}}` - Blog categories table.
 */
class m140911_074715_create_module_tbl extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;


        // MySql table options
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        }

        // Category table
        $this->createTable('{{%blogs_category}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull(),
            'alias' => $this->string(100)->notNull(),
            'content' => $this->text()->notNull(),
            'image_url' =>$this->string(64)->notNull(),
            'sort' => $this->smallInteger()->notNull(),
            'status_id' => $this->smallInteger()->notNull()->defaultValue(0),
            'seo_title' => $this->string(255),
            'seo_keyword' => $this->string(255),
            'seo_desc' => $this->string(255),
        ], $tableOptions);

        // Indexes
        $this->createIndex('idx-blogs_category-sort', '{{%blogs_category}}', 'sort');
        $this->createIndex('idx-blogs_category-status_id', '{{%blogs_category}}', 'status_id');

        // Blogs table
        $this->createTable('{{%blogs}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'title' => $this->string(100)->notNull(),
            'alias' => $this->string(100)->notNull(),
            'snippet' => $this->text()->notNull(),
            'content' => $this->text()->notNull(),
            'image_url' => $this->string(64)->notNull(),
            'preview_url' =>  $this->string(64)->notNull(),
            'views' => $this->integer()->notNull()->defaultValue(0),
            'status_id' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
            'seo_title' => $this->string(255),
            'seo_keyword' => $this->string(255),
            'seo_desc' => $this->string(255),
        ], $tableOptions);

        // Indexes
        $this->createIndex('idx-blogs-status_id', '{{%blogs}}', 'status_id');
        $this->createIndex('idx-blogs-category_id', '{{%blogs}}', 'category_id');
        $this->createIndex('idx-blogs-views', '{{%blogs}}', 'views');
        $this->createIndex('idx-blogs-created_at', '{{%blogs}}', 'created_at');
        $this->createIndex('idx-blogs-updated_at', '{{%blogs}}', 'updated_at');

        // Foreign Keys
        $this->addForeignKey('fk-blogs-category_id', '{{%blogs}}', 'category_id', '{{%blogs_category}}', 'id', 'RESTRICT', 'CASCADE');

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%blogs}}');
        $this->dropTable('{{%blogs_category}}');
    }

}
