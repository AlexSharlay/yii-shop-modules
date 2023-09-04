<?php

namespace common\modules\shop\models\backend;

use Yii;
use DateTime;
use common\modules\users\models\Profile;
use common\modules\catalog\models\backend\Element;

class Order extends \common\modules\shop\models\Order
{

    public static function deleteOrder($model) {
        $dateEnd = new DateTime();
        $dateEnd->setTimestamp($model->created)->modify('+3 day');
        $dateNow = new DateTime('NOW');
        $secondLeft = strtotime($dateEnd->format("d.m.Y H:i:s")) - strtotime($dateNow->format("d.m.Y H:i:s"));

        if (Yii::$app->user->id != $model->id_user || $secondLeft < 0 || $model->status == 1) {
            Yii::$app->session->setFlash('danger', 'Ошибка. Причины: вы пытаетесь удалить не свою заявку; время для удаления истекло; заявка оплачена.');
        } else {
            self::returnStock($model->id);
            $model->delete();
        }
    }

    public static function returnStock($id) {
        $str = Order::find()->select('one_data')->where('id = :id', [':id' => $id])->asArray()->one()['one_data'];
        $arr = unserialize($str);
        $products = [];
        foreach($arr as $a) {
            if ($a['count']) {
                $products[] = $a;
            }
        }
        foreach($products as $product) {
            $element = Element::findOne($product['id']);
            $element->updateCounters(['in_stock' => $product['count']]);
        }
    }

    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id_user']);
    }


    public function getProfile2()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id_user']);
    }


}