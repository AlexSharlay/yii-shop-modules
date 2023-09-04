<?php

namespace common\modules\shop\models;

use Yii;
use yii\db\ActiveRecord;
use common\modules\blogs\traits\ModuleTrait;
use common\modules\shop\models\backend\DeliveryPayment;


/**
 * This is the model class for table "{{%shop_delivery}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $desc
 * @property integer $price
 * @property integer $price_from
 * @property integer $price_to
 * @property integer $sort
 *
 * @property ShopDeliveryPayment[] $shopDeliveryPayments
 */
class Delivery extends ActiveRecord
{

    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop_delivery}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['desc'], 'string'],
            [['price', 'price_from', 'price_to', 'sort'], 'integer'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'desc' => 'Описание',
            'price' => 'Цена',
            'price_from' => 'Цена от',
            'price_to' => 'Цена до',
            'sort' => 'Порядок',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryPayments()
    {
        return $this->hasMany(DeliveryPayment::className(), ['id_delivery' => 'id']);
    }
}
