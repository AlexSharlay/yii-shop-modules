<?php

namespace common\modules\shop\models\backend;


use Yii;

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
 * @property DeliveryPayment[] $DeliveryPayments
 */
class Delivery extends \common\modules\shop\models\Delivery
{

    public static function SearchPayment($str)
    {
        $search = Payment::find()
            ->andWhere(['like', 'title', $str])
            ->select('id, title')->limit(20)->asArray()->all();
        return json_encode($search);
    }

    public static function AddPayment($id_delivery, $id_payment)
    {
        $rel = new DeliveryPayment();
        $rel->id_delivery = $id_delivery;
        $rel->id_payment = $id_payment;
        $rel->save();
    }

    public static function DeletePayment($id_delivery, $id_payment)
    {
        DeliveryPayment::find()->andWhere(['id_delivery' => $id_delivery, 'id_payment' => $id_payment])->select('id')->one()->delete();
    }


    public static function deliveryPayments($price)
    {
        $search = Delivery::find()
            ->joinWith('deliveryPayment')
            ->joinWith('deliveryPayment.payment')
            ->andWhere('{{%shop_delivery}}.price_from <= :price AND {{%shop_delivery}}.price_to >= :price',[':price'=>$price])->asArray()->all();
        return $search;
    }

    public function getDeliveryPayment() {
        return $this->hasMany(DeliveryPayment::className(), ['id_delivery' => 'id']);
    }

    public function getPayment() {
        return $this->hasMany(Payment::className(), ['id' => 'id_payment']);
    }


}
