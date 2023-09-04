<?php

namespace common\modules\catalog\models;

use Yii;
use common\modules\catalog\traits\ModuleTrait;

/**
 * This is the model class for table "{{%catalog_kit_rel}}".
 *
 * @property integer $id
 * @property integer $id_kit
 * @property integer $id_element_parent
 * @property integer $id_element_children
 * @property integer $sort
 */
class KitRel extends \yii\db\ActiveRecord
{
    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%catalog_kit_rel}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_kit', 'id_element_parent', 'id_element_children', 'sort'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_kit' => 'Id Kit',
            'id_element_parent' => 'Id Element Parent',
            'id_element_children' => 'Id Element Children',
            'sort' => 'Sort',
        ];
    }
}
