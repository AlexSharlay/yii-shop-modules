<?php

namespace common\modules\catalog\models;

use Yii;
use yii\db\ActiveRecord;
use common\modules\catalog\traits\ModuleTrait;

/**
 * This is the model class for table "{{%catalog_field_value}}".
 *
 * @property integer $id
 * @property string $value
 * @property string $text
 * @property integer $dop
 */
class FieldValue extends ActiveRecord
{
    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%catalog_field_value}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value'], 'number'],
            [['text'], 'string'],
            [['dop'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'value' => 'Value',
            'text' => 'Text',
            'dop' => 'Dop',
        ];
    }
}
