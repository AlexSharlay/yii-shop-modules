<?php

namespace common\modules\shop\models;
use common\modules\blogs\traits\ModuleTrait;
use yii\db\ActiveRecord;

use Yii;

/**
 * This is the model class for table "{{%shop_delivery_payment}}".
 *
 * @property integer $id
 * @property integer $id_delivery
 * @property integer $id_payment
 *
 */
class DeliveryPayment extends ActiveRecord
{

    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop_delivery_payment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_delivery', 'id_payment'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_delivery' => 'Id Delivery',
            'id_payment' => 'Id Payment',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDelivery()
    {
        return $this->hasOne(Delivery::className(), ['id' => 'id_delivery']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayment()
    {
        return $this->hasOne(Payment::className(), ['id' => 'id_payment']);
    }
}
