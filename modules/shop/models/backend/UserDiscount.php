<?php

namespace common\modules\shop\models\backend;

use Yii;

class UserDiscount extends \common\modules\shop\models\UserDiscount
{

    public static function saveDiscounts($id, $discounts) {
        // Удалить предыдущие
        UserDiscount::deleteAll('id_user = :id_user', [':id_user' => $id]);
        // Добавить новые
        foreach ($discounts as $id_category => $discount) {
            if ($discount) {
                $model = new UserDiscount();
                $model->id_category = $id_category;
                $model->id_user = $id;
                $model->discount = $discount;
                if (!$model->save()) {
                    Yii::$app->session->setFlash('error', 'Ошибка пересохраните результат. В случае повторения ошибки обратитесь к веб-разработчику.');
                }
            }
        }
    }

}
