<?php

use yii\db\Migration;

class m161108_123257_reviews extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $tableOptions = null;

        // MySql table options
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%review}}',[
            'id'=>$this->primaryKey(),
            'title'=>$this->string()->notNull(),
            'text'=>$this->text()->notNull(),
            'advantage'=>$this->text()->notNull(),
            'disadvantages'=>$this->text()->notNull(),
            'vote_up'=>$this->integer(11)->defaultValue(0),
            'vote_down'=>$this->integer(11)->defaultValue(0),
            'rating'=>$this->integer()->defaultValue(0),
            'published'=>$this->smallInteger(1)->defaultValue(0),
            'created_at'=>$this->dateTime()->notNull(),
            'catalog_element_id'=>$this->integer(),
            'user_id'=>$this->integer()
        ],$tableOptions);

        $this->createIndex('idx-review-published','{{%review}}','published');
        $this->createIndex('idx-review-created_at','{{%review}}','created_at');

        $this->addForeignKey('fk-review-catalog_element_id','{{%review}}','catalog_element_id','{{%catalog_element}}','id','CASCADE','NOT ACTION');
        $this->addForeignKey('fk-review-user_id','{{%review}}','user_id','{{%users}}','id','CASCADE','NOT ACTION');

    }

    public function safeDown()
    {

        $this->dropTable('{{%review}}');

    }

}
