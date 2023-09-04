<?php

namespace common\modules\shop\models;

use Yii;
use common\modules\blogs\traits\ModuleTrait;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%shop_cart}}".
 *
 * @property integer $id
 * @property integer $id_element
 * @property integer $id_user
 * @property integer $id_kit
 * @property integer $count
 *
 */
class Cart extends ActiveRecord
{

    use ModuleTrait;

    public static function tableName()
    {
        return '{{%shop_cart}}';
    }

    public function rules()
    {
        return [
            [['id_element', 'id_user' , 'id_kit' , 'count'], 'required'],
            [['id_element', 'id_user' , 'id_kit' , 'count'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_element' => 'id_element',
            'id_user' => 'id_user',
            'id_kit' => 'id_kit',
            'count' => 'count',
        ];
    }

}
