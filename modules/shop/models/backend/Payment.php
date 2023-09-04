<?php

namespace common\modules\shop\models\backend;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%shop_payment}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $desc
 * @property integer $sort
 *
 * @property DeliveryPayment[] $deliveryPayments
 */
class Payment extends \common\modules\shop\models\Payment
{
    public function getPaymentsList($id)
    {
        //->select('payment.id, payment.title')
        $model = DeliveryPayment::find()->andWhere(['id_delivery' => $id])->with(['payment'])->asArray()->all();
        $arr = [];
        foreach ($model as $rel) {
            $arr[] = [
                'id' => $rel['payment']['id'],
                'title' => $rel['payment']['title']
            ];
        }
        return $arr;
    }
}
