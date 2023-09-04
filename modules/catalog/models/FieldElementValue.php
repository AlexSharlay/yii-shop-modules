<?php

namespace common\modules\catalog\models;

use Yii;
use yii\db\ActiveRecord;
use common\modules\catalog\traits\ModuleTrait;

/**
 * This is the model class for table "{{%catalog_field_element_value_rel}}".
 *
 * @property integer $id
 * @property integer $id_element
 * @property integer $id_field
 * @property integer $id_value
 */
class FieldElementValue extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%catalog_field_element_value_rel}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_element', 'id_field', 'id_value'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_element' => 'Id Element',
            'id_field' => 'Id Field',
            'id_value' => 'Id Value',
        ];
    }
}
