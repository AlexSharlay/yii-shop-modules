<?php

namespace common\modules\shop\components;

use Yii;
use yii\web\NotFoundHttpException;
use common\modules\shop\models\frontend\Order;

class Invoice
{

    public static function pdf($id) {
        $userId = Yii::$app->user->id;
        $order = Order::find()->select('id, invoice_pdf')->where('id = :id AND id_user = :id_user', ['id' => $id, 'id_user' => $userId])->asArray()->one();
        if ($order['id']) {
            return ($order['invoice_pdf']) ? $order['invoice_pdf'] : '';
        } else {
            throw new NotFoundHttpException('Получить чужие счета нельзя.');
        }
    }

}





