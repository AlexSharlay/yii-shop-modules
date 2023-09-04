<?php

namespace common\modules\catalog\models;

use Yii;
use yii\db\ActiveRecord;
use common\modules\catalog\traits\ModuleTrait;

/**
 * This is the model class for table "{{%catalog_field}}".
 *
 * @property integer $id
 * @property integer $id_group
 * @property string $alias
 * @property string $name
 * @property string $name_filter
 * @property string $description
 * @property string $unit
 * @property string $variant
 * @property integer $type
 * @property integer $boot_type
 * @property integer $sort
 * @property integer $in_filter
 * @property string $dop
 * @property string $compare
 * @property integer $published
 */
class Field extends ActiveRecord
{
    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%catalog_field}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_group', 'type', 'boot_type', 'sort', 'published'], 'integer'],
            [['alias', 'name', 'name_filter', 'type'], 'required'],
            [['description', 'variant', 'dop'], 'string'],
            [['alias', 'name', 'name_filter', 'unit', 'compare'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_group' => 'Id Group',
            'alias' => 'Alias',
            'name' => 'Name',
            'name_filter' => 'Name Filter',
            'description' => 'Description',
            'unit' => 'Unit',
            'variant' => 'Variant',
            'type' => 'Type',
            'boot_type' => 'Boot Type',
            'sort' => 'Sort',
            'in_filter' => 'In Filter',
            'dop' => 'Dop',
            'compare' => 'Compare',
            'published' => 'Published',
        ];
    }
}
