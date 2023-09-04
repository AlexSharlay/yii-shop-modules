<?php

use yii\db\Schema;
use yii\db\Migration;

class m140418_204054_catalog extends Migration
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
         * Category table
         */
        $this->createTable('{{%catalog_category}}', [
            'id' => $this->primaryKey(),
            'id_parent' => $this->integer(11),
            'title' => $this->string(255)->notNull(),
            'title_pre' => $this->string(255)->notNull(),
            'title_yml' => $this->string(255)->notNull(),
            'desc' => $this->text(),
            'alias' => $this->string(255)->notNull(),
            'ico' => $this->string(255),
            'sort' => $this->integer(11)->notNull(),
            'use_model' => $this->smallInteger(1),
            'hide_filter_after' => $this->smallInteger(1),
            'published' => $this->smallInteger(1),
            'seo_title' => $this->string(255),
            'seo_keyword' => $this->string(255),
            'seo_desc' => $this->string(255),
            'facets' => $this->text(),
        ], $tableOptions);

        $this->createIndex('idx-catalog_category-id_parent', '{{%catalog_category}}', 'id_parent');
        $this->createIndex('idx-catalog_category-published', '{{%catalog_category}}', 'published');

        $this->addForeignKey('fk-catalog_category-id_parent', '{{%catalog_category}}', 'id_parent', '{{%catalog_category}}', 'id', 'SET NULL', 'CASCADE');

        /*
         * Country table
         */
        $this->createTable('{{%catalog_country}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'ico' => $this->string(255),
        ], $tableOptions);

        /*
         * Manufacturer table
         */
        $this->createTable('{{%catalog_manufacturer}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'desc' => $this->text(),
            'alias' => $this->string(255)->notNull(),
            'ico' => $this->string(255),
            'published' => $this->smallInteger(1),
            'seo_title' => $this->string(255),
            'seo_keyword' => $this->string(255),
            'seo_desc' => $this->string(255),
        ], $tableOptions);

        $this->createIndex('idx-catalog_manufacturer-published', '{{%catalog_manufacturer}}', 'published');

        /*
        * Manufacturer Country table
        */
        $this->createTable('{{%catalog_manufacturer_country}}', [
            'id' => $this->primaryKey(),
            'id_manufacturer' => $this->integer(),
            'id_country' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('idx-catalog_manufacturer_country-id_manufacturer', '{{%catalog_manufacturer_country}}', 'id_manufacturer');
        $this->createIndex('idx-catalog_manufacturer_country-id_country', '{{%catalog_manufacturer_country}}', 'id_country');

        $this->addForeignKey('fk-catalog_manufacturer_country-id_manufacturer', '{{%catalog_manufacturer_country}}', 'id_manufacturer', '{{%catalog_manufacturer}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-catalog_manufacturer_country-id_country', '{{%catalog_manufacturer_country}}', 'id_country', '{{%catalog_country}}', 'id', 'CASCADE', 'CASCADE');

        /*
         * Collection table
         */
        $this->createTable('{{%catalog_collection}}', [
            'id' => $this->primaryKey(),
            'alias' => $this->string(255)->notNull(),
        ], $tableOptions);

        //
        $this->createTable('{{%catalog_collection_rel}}', [
            'id' => $this->primaryKey(),
            'id_collection' => $this->integer(11),
            'id_element' => $this->integer(11),
        ], $tableOptions);

        $this->createIndex('idx-catalog_collection_rel-id_collection', '{{%catalog_collection_rel}}', 'id_collection');
        $this->createIndex('idx-catalog_collection_rel-id_element', '{{%catalog_collection_rel}}', 'id_element');

        // Foreign Keys
        $this->addForeignKey('fk-catalog_collection-id_collection', '{{%catalog_collection}}', 'id_collection', '{{%catalog_collection}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-catalog_collection-id_element', '{{%catalog_collection}}', 'id_element', '{{%catalog_element}}', 'id', 'CASCADE', 'CASCADE');

        //
        $this->createTable('{{%catalog_collection_image}}', [
            'id' => $this->primaryKey(),
            'id_collection' => $this->integer(11),
            'name' => $this->string(255)->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-catalog_collection_image-id_collection', '{{%catalog_collection_image}}', 'id_collection');

        // Foreign Keys
        $this->addForeignKey('fk-catalog_collection_image-id_collection', '{{%catalog_collection_image}}', 'id_collection', '{{%catalog_collection}}', 'id', 'CASCADE', 'CASCADE');

        /*
         * Element table
         */

        $this->createTable('{{%catalog_measurement}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'code' => $this->string(255)->notNull()
        ], $tableOptions);

        $this->createTable('{{%catalog_element}}', [
            'id' => $this->primaryKey(),
            'alias' => $this->string(255)->notNull(),
            'title' => $this->string(255)->notNull(),
            'title_before' => $this->string(255)->notNull(),
            'title_model' => $this->string(255)->notNull(),
            'desc_mini' => $this->text(),
            'desc_full' => $this->text(),
            'desc_yml' => $this->text(),

            'facets' => $this->text(),

            'id_category' => $this->integer(11),
            'id_manufacturer' => $this->integer(11),
            'id_measurement' => $this->integer(11),

            'published' => $this->smallInteger(1),

            'created_at' => $this->string(255),
            'updated_at' => $this->string(255),
            'article' => $this->string(255),
            'guarantee' => $this->string(255),
            'life_time' => $this->string(255),

            'code_1c' => $this->integer(11),
            'price_1c' => $this->integer(11),
            'rentability' => $this->integer(11),
            'price' => $this->integer(11),
            'price_old' => $this->integer(11),

            'in_stock' => $this->integer(11),
            'is_defect' => $this->integer(1)->notNull()->defaultValue(0),
            'is_main' => $this->integer(1)->notNull()->defaultValue(0),
            'is_model' => $this->integer(1)->notNull()->defaultValue(0),
            'is_custom' => $this->integer(1)->notNull()->defaultValue(0),

            'hit' => $this->integer(11)->notNull()->defaultValue(0),

            'info_importer' => $this->text(),
            'info_service' => $this->text(),
            'tip_1c' => $this->text(),

            'sort' => $this->integer(11),

            /* Торговые площадки */

            'tp_onliner_by_alias' => $this->string(255),
            'tp_onliner_by_title' => $this->string(255),
            'tp_onliner_by_url' => $this->text(),

            'tp_1k_by_title' => $this->string(255),
            'tp_1k_by_alias' => $this->string(255),
            'tp_1k_by_url' => $this->text(),

            'tp_market_yandex_by_title' => $this->string(255),
            'tp_market_yandex_by_alias' => $this->string(255),
            'tp_market_yandex_by_url' => $this->text(),

            'tp_shop_by_title' => $this->string(255),
            'tp_shop_by_alias' => $this->string(255),
            'tp_shop_by_url' => $this->text(),

            'tp_unishop_by_title' => $this->string(255),
            'tp_unishop_by_alias' => $this->string(255),
            'tp_unishop_by_url' => $this->text(),

            /* /торговые площадки */

            'seo_title' => $this->string(255),
            'seo_keyword' => $this->string(255),
            'seo_desc' => $this->string(255),
        ], $tableOptions);

        $this->createIndex('idx-catalog_element-id_category', '{{%catalog_element}}', 'id_category');
        $this->createIndex('idx-catalog_element-id_manufacturer', '{{%catalog_element}}', 'id_manufacturer');
        $this->createIndex('idx-catalog_element-id_measurement', '{{%catalog_element}}', 'id_measurement');
        $this->createIndex('idx-catalog_element-price', '{{%catalog_element}}', 'price');
        $this->createIndex('idx-catalog_element-in_stock', '{{%catalog_element}}', 'in_stock');
        $this->createIndex('idx-catalog_element-is_defect', '{{%catalog_element}}', 'is_defect');
        $this->createIndex('idx-catalog_element-is_main', '{{%catalog_element}}', 'is_main');
        $this->createIndex('idx-catalog_element-is_model', '{{%catalog_element}}', 'is_model');
        $this->createIndex('idx-catalog_element-is_custom', '{{%catalog_element}}', 'is_custom');
        $this->createIndex('idx-catalog_element-hit', '{{%catalog_element}}', 'hit');
        $this->createIndex('idx-catalog_element-published', '{{%catalog_element}}', 'published');

        // Foreign Keys
        $this->addForeignKey('fk-catalog_element-id_category', '{{%catalog_element}}', 'id_category', '{{%catalog_catalog}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-catalog_element-id_manufacturer', '{{%catalog_element}}', 'id_manufacturer', '{{%catalog_manufacturer}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-catalog_element-id_measurement', '{{%catalog_element}}', 'id_measurement', '{{%catalog_measurement}}', 'id', 'SET NULL', 'CASCADE');

        /*
         * Photo table
         */

        $this->createTable('{{%catalog_photo}}', [
            'id' => $this->primaryKey(),
            'id_element' => $this->integer(11)->notNull(),
            'name' => $this->string(255)->notNull(),
            'sort' => $this->integer(11),
            'is_cover' => $this->smallInteger(1),
        ], $tableOptions);

        $this->createIndex('fk-catalog_photo-id_element', '{{%catalog_photo}}', 'id_element');
        $this->createIndex('fk-catalog_photo-sort', '{{%catalog_photo}}', 'sort');
        $this->createIndex('fk-catalog_photo-is_cover', '{{%catalog_photo}}', 'is_cover');

        // Foreign Keys
        $this->addForeignKey('fk-catalog_photo-id_element', '{{%catalog_photo}}', 'id_element', '{{%catalog_element}}', 'id', 'CASCADE', 'CASCADE');

        /*
         * Комплекты
         */

        $this->createTable('{{%catalog_complect_rel}}', [
            'id' => $this->primaryKey(),
            'id_element_parent' => $this->integer(11),
            'id_element_children' => $this->integer(11),
        ], $tableOptions);

        $this->createIndex('idx-catalog_complect_rel-id_element_parent', '{{%catalog_complect_rel}}', 'id_element_parent');
        $this->createIndex('idx-catalog_complect_rel-id_element_children', '{{%catalog_complect_rel}}', 'id_element_children');

        // Foreign Keys
        $this->addForeignKey('fk-catalog_complect_rel-id_element_parent', '{{%catalog_complect_rel}}', 'id_element_parent', '{{%catalog_element}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-catalog_complect_rel-id_element_children', '{{%catalog_complect_rel}}', 'id_element_children', '{{%catalog_element}}', 'id', 'CASCADE', 'CASCADE');

        /*
         * Модели
         */

        $this->createTable('{{%catalog_model_rel}}', [
            'id' => $this->primaryKey(),
            'id_element_parent' => $this->integer(11),
            'id_element_children' => $this->integer(11),
        ], $tableOptions);

        $this->createIndex('idx-catalog_model_rel-id_element_parent', '{{%catalog_model_rel}}', 'id_element_parent');
        $this->createIndex('idx-catalog_model_rel-id_element_children', '{{%catalog_model_rel}}', 'id_element_children');

        // Foreign Keys
        $this->addForeignKey('fk-catalog_model_rel-id_element_parent', '{{%catalog_model_rel}}', 'id_element_parent', '{{%catalog_element}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-catalog_model_rel-id_element_children', '{{%catalog_model_rel}}', 'id_element_children', '{{%catalog_element}}', 'id', 'CASCADE', 'CASCADE');

        /*
         * Наборы
         */

        $this->createTable('{{%catalog_kit_rel}}', [
            'id' => $this->primaryKey(),
            'id_complect' => $this->integer(11),
            'id_element_parent' => $this->integer(11),
            'id_element_children' => $this->integer(11),
            'sort' => $this->integer(11),
        ], $tableOptions);

        $this->createIndex('idx-catalog_kit_rel-id_element_parent', '{{%catalog_kit_rel}}', 'id_element_parent');
        $this->createIndex('idx-catalog_kit_rel-id_element_children', '{{%catalog_kit_rel}}', 'id_element_children');
        $this->createIndex('idx-catalog_kit_rel-sort', '{{%catalog_kit_rel}}', 'sort');

        // Foreign Keys
        $this->addForeignKey('fk-catalog_kit_rel-id_element_parent', '{{%catalog_kit_rel}}', 'id_element_parent', '{{%catalog_element}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-catalog_kit_rel-id_element_children', '{{%catalog_kit_rel}}', 'id_element_children', '{{%catalog_element}}', 'id', 'CASCADE', 'CASCADE');

        /*
        * Filters table
        */

        $this->createTable('{{%catalog_field_group}}', [
            'id' => $this->primaryKey(),
            'id_category' => $this->integer(11),
            'title' => $this->string(255)->notNull(),
            'sort' => $this->integer(11)->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-catalog_field_group-id_category', '{{%catalog_field_group}}', 'id_category');
        $this->createIndex('idx-catalog_field_group-sort', '{{%catalog_field_group}}', 'sort');

        // Foreign Keys
        $this->addForeignKey('fk-catalog_field_group-id_category', '{{%catalog_field_group}}', 'id_category', '{{%catalog_category}}', 'id', 'CASCADE', 'CASCADE');

        //
        $this->createTable('{{%catalog_field}}', [
            'id' => $this->primaryKey(),
            'id_group' => $this->integer(11),
            'alias' => $this->string(255)->notNull(),
            'name' => $this->string(255)->notNull(),
            'name_filter' => $this->string(255)->notNull(),
            'description' => $this->text(),
            'unit' => $this->string(255),
            'variant' => $this->text(),
            'type' => $this->integer(11)->notNull(),
            'boot_type' => $this->integer(11),
            'sort' => $this->integer(11),
            'dop' => $this->text(),
            'compare' => $this->string(255),
            'published' => $this->smallInteger(1),
        ], $tableOptions);

        $this->createIndex('idx-catalog_field-id_group', '{{%catalog_field}}', 'id_group');
        $this->createIndex('idx-catalog_field-sort', '{{%catalog_field}}', 'sort');
        $this->createIndex('idx-catalog_field-in_filter', '{{%catalog_field}}', 'in_filter');
        $this->createIndex('idx-catalog_field-published', '{{%catalog_field}}', 'published');

        // Foreign Keys
        $this->addForeignKey('fk-catalog_field-id_group', '{{%catalog_field}}', 'id_group', '{{%catalog_field_group}}', 'id', 'CASCADE', 'CASCADE');

        //
        $this->createTable('{{%catalog_field_element_value_rel}}', [
            'id' => $this->primaryKey(),
            'id_element' => $this->integer(11),
            'id_field' => $this->integer(11),
            'id_value' => $this->integer(11),
        ], $tableOptions);

        $this->createIndex('idx-catalog_field_element_value_rel-id_element', '{{%catalog_field_element_value_rel}}', 'id_element');
        $this->createIndex('idx-catalog_field_element_value_rel-id_field', '{{%catalog_field_element_value_rel}}', 'id_field');
        $this->createIndex('idx-catalog_field_element_value_rel-id_value', '{{%catalog_field_element_value_rel}}', 'id_value');

        // Foreign Keys
        $this->addForeignKey('fk-catalog_field_element_value_rel-id_element', '{{%catalog_field_element_value_rel}}', 'id_element', '{{%catalog_element}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-catalog_field_element_value_rel-id_field', '{{%catalog_field_element_value_rel}}', 'id_field', '{{%catalog_field}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-catalog_field_element_value_rel-id_value', '{{%catalog_field_element_value_rel}}', 'id_value', '{{%catalog_field_value}}', 'id', 'CASCADE', 'CASCADE');

        //
        $this->createTable('{{%catalog_field_value}}', [
            'id' => $this->primaryKey(),
            'value' => $this->decimal(14,4),
            'text' => $this->text(),
            'dop' => $this->integer(11),
        ], $tableOptions);

        $this->createIndex('idx-catalog_field_value-value', '{{%catalog_field_value}}', 'value');

        /*
         * Statistics filled table
         */
        $this->createTable('{{%catalog_statistic_filled}}', [
            'id' => $this->primaryKey(),
            'all' => $this->integer(11),
            'fill_mini' => $this->integer(11),
            'fill_full' => $this->integer(11),
            'with_photo' => $this->integer(11),
            'date' => $this->string(255),
        ], $tableOptions);

        $this->createIndex('idx-catalog_statistic_filled-all', '{{%catalog_statistic_filled}}', 'all');
        $this->createIndex('idx-catalog_statistic_filled-fill_mini', '{{%catalog_statistic_filled}}', 'fill_mini');
        $this->createIndex('idx-catalog_statistic_filled-fill_full', '{{%catalog_statistic_filled}}', 'fill_full');
        $this->createIndex('idx-catalog_statistic_filled-with_photo', '{{%catalog_statistic_filled}}', 'with_photo');

    }

    public function safeDown()
    {
        $this->dropTable('{{%catalog_category}}');
        $this->dropTable('{{%catalog_country}}');
        $this->dropTable('{{%catalog_manufacturer}}');
        $this->dropTable('{{%catalog_manufacturer_country}}');
        $this->dropTable('{{%catalog_collection}}');
        $this->dropTable('{{%catalog_collection_rel}}');
        $this->dropTable('{{%catalog_measurement}}');
        $this->dropTable('{{%catalog_element}}');
        $this->dropTable('{{%catalog_photo}}');
        $this->dropTable('{{%catalog_collection}}');
        $this->dropTable('{{%catalog_collection_rel}}');
        $this->dropTable('{{%catalog_complect_rel}}');
        $this->dropTable('{{%catalog_model_rel}}');

        $this->dropTable('{{%catalog_field_group}}');
        $this->dropTable('{{%catalog_field}}');
        $this->dropTable('{{%catalog_field_element_value_rel}}');
        $this->dropTable('{{%catalog_field_element_value_rel}}');

        $this->dropTable('{{%catalog_statistic_filled}}');
    }
}
