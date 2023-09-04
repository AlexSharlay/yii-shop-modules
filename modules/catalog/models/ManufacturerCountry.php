<?php

namespace common\modules\catalog\models;
use common\modules\catalog\traits\ModuleTrait;
use yii\db\ActiveRecord;

use Yii;

/**
 * This is the model class for table "{{%catalog_manufacturer_country}}".
 *
 * @property integer $id
 * @property integer $id_manufacturer
 * @property integer $id_country
 *
 */
class ManufacturerCountry extends ActiveRecord
{

    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%catalog_manufacturer_country}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_manufacturer', 'id_country'], 'integer'],
            [['id_manufacturer', 'id_country'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_manufacturer' => 'Производитель',
            'id_country' => 'Страна',
        ];
    }

}
