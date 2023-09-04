<?php

use yii\db\Schema;
use yii\db\Migration;

class m151218_101325_shop extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;

        // MySql table options
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        /*
         * Оплата и доставка
         */

        $this->createTable('{{%shop_delivery}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'desc' => $this->text(),
            'price' => $this->integer(11),
            'price_from' => $this->integer(11),
            'price_to' => $this->integer(11),
            'sort' => $this->integer(11),
        ], $tableOptions);

        $this->createIndex('idx-shop_delivery-price', '{{%shop_delivery}}', 'price');
        $this->createIndex('idx-shop_delivery-price_from', '{{%shop_delivery}}', 'price_from');
        $this->createIndex('idx-shop_delivery-price_to', '{{%shop_delivery}}', 'price_to');
        $this->createIndex('idx-shop_delivery-sort', '{{%shop_delivery}}', 'sort');

        $this->createTable('{{%shop_payment}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'desc' => $this->text(),
            'sort' => $this->integer(11),
        ], $tableOptions);

        $this->createIndex('idx-shop_payment-sort', '{{%shop_payment}}', 'sort');

        $this->createTable('{{%shop_delivery_payment}}', [
            'id' => $this->primaryKey(),
            'id_delivery' => $this->integer(11)->notNull(),
            'id_payment' => $this->integer(11)->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-shop_delivery_payment-id_delivery', '{{%shop_delivery_payment}}', 'id_delivery');
        $this->createIndex('idx-shop_delivery_payment-id_payment', '{{%shop_delivery_payment}}', 'id_payment');

        $this->addForeignKey('fk-shop_delivery_payment-id_delivery', '{{%shop_delivery_payment}}', 'id_delivery', '{{%shop_delivery}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk-shop_delivery_payment-id_payment', '{{%shop_delivery_payment}}', 'id_payment', '{{%shop_payment}}', 'id', 'SET NULL', 'CASCADE');

        /*
         * Статусы заказов
         */

        $this->createTable('{{%shop_order_status}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'sort' => $this->integer(11),
        ], $tableOptions);

        $this->createIndex('idx-shop_order_status-sort', '{{%shop_order_status}}', 'sort');

        /*
         * Заказы
         */

        $this->createTable('{{%shop_order}}', [
            'id' => $this->primaryKey(),
            'id_user' => $this->integer()->notNull(),
            'id_manager' => $this->integer()->notNull(),
            'id_status' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'price_sum' => $this->integer()->notNull()->defaultValue(0),
            'data_person' => $this->text() . ' NOT NULL',
            'data_order' => $this->text() . ' NOT NULL',
            'one_data' => $this->text() . '',
            'one_status' => $this->integer(1)->notNull()->defaultValue(0),
            'invoice_xlsx' => $this->text() . '(50)',
            'invoice_pdf' => $this->text() . '(50)',
        ], $tableOptions);

        $this->createIndex('idx-shop_order-id_user', '{{%shop_order}}', 'id_user');
        $this->createIndex('idx-shop_order-id_manager', '{{%shop_order}}', 'id_manager');
        $this->createIndex('idx-shop_order-id_status', '{{%shop_order}}', 'id_status');
        $this->createIndex('idx-shop_order-created_at', '{{%shop_order}}', 'created_at');
        $this->createIndex('idx-shop_order-updated_at', '{{%shop_order}}', 'updated_at');
        $this->createIndex('idx-shop_order-price_sum', '{{%shop_order}}', 'price_sum');

        // Foreign Keys
        $this->addForeignKey('fk-shop_order-id_user', '{{%shop_order}}', 'id_user', '{{%users}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-shop_order-id_manager', '{{%shop_order}}', 'id_manager', '{{%users}}', 'id', 'RESTRICT ', 'CASCADE');
        $this->addForeignKey('fk-shop_order-id_status', '{{%shop_order}}', 'id_status', '{{%shop_order_status}}', 'id', 'RESTRICT ', 'CASCADE');

        /*
        * Скидки пользователю на конкретные категории
        */

        $this->createTable('{{%shop_user_discount}}', [
            'id' => $this->primaryKey(),
            'id_user' => $this->integer(),
            'id_category' => $this->integer(),
            'discount' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('idx-shop_user_discount-id_user', '{{%shop_user_discount}}', 'id_user');
        $this->createIndex('idx-shop_user_discount-discount', '{{%shop_user_discount}}', 'discount');

        // Foreign Keys
        $this->addForeignKey('fk-shop_user_discount-id_user', '{{%shop_user_discount}}', 'id_user', '{{%users}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-shop_user_discount-id_category', '{{%shop_user_discount}}', 'id_category', '{{%catalog_category}}', 'id', 'CASCADE', 'CASCADE');

        /*
        * Корзина
        */

        $this->createTable('{{%shop_cart}}', [
            'id' => $this->primaryKey(),
            'id_element' => $this->integer(),
            'id_user' => $this->integer(),
            'count' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('idx-shop_cart-id_element', '{{%shop_cart}}', 'id_element');
        $this->createIndex('idx-shop_cart-id_user', '{{%shop_cart}}', 'id_user');

        // Foreign Keys
        $this->addForeignKey('fk-shop_cart-id_element', '{{%shop_cart}}', 'id_element', '{{%catalog_element}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-shop_cart-id_user', '{{%shop_cart}}', 'id_user', '{{%users}}', 'id', 'CASCADE', 'CASCADE');

        /*
        * Клиент - Менеджер
        */

        $this->createTable(
            '{{%shop_client_manager}}',
            [
                'id' => $this->primaryKey(),
                'id_manager' => $this->integer(),
                'id_client' => $this->integer(),
                'active' => $this->integer(1),
            ],
            $tableOptions
        );

        $this->createIndex('id-shop_client_manager-id_manager', '{{%shop_client_manager}}', 'id_manager');
        $this->createIndex('id-shop_client_manager-id_client', '{{%shop_client_manager}}', 'id_client');
        $this->createIndex('id-shop_client_manager-active', '{{%shop_client_manager}}', 'active');

        // Foreign Keys
        $this->addForeignKey('fk-shop_client_manager-id_manager', '{{%shop_client_manager}}', 'id_manager', '{{%users}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-shop_client_manager-id_client', '{{%shop_client_manager}}', 'id_client', '{{%users}}', 'id', 'CASCADE', 'CASCADE');

    }

    public function safeDown()
    {

        $this->dropTable('{{%shop_delivery}}');

        $this->dropTable('{{%shop_payment}}');

        $this->dropTable('{{%shop_delivery_payment}}');

        $this->dropTable('{{%shop_order}}');

        $this->dropTable('{{%shop_order_status}}');

        $this->dropTable('{{%shop_user_discount}}');

        $this->dropTable('{{%shop_cart}}');

    }
}