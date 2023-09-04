<?php

namespace common\modules\shop\controllers\frontend;

use Yii;
use backend\components\Controller;
use common\modules\shop\models\frontend\Cart;

class CartController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => [
                'cart-delete', 'cart-get', 'cart-add', 'change-quantity',
                'delivery-get', 'delivery-delete', 'delivery-add'
            ],
            'roles' => ['@']
        ];

        return $behaviors;
    }

    public function actionCartDelete($id)
    {
        Cart::cartDelete($id);
    }

    public function actionCartGet()
    {
        Cart::cartGet(1);
    }

    public function actionCartAdd($id, $kit_id, $count)
    {
        Cart::cartAdd($id, $kit_id, $count);
    }

    public function actionChangeQuantity($id, $quantity)
    {
        Cart::changeQuantity($id, $quantity);
    }

    // При оформлении заказа танцы с адресом доставки

    public function actionDeliveryGet()
    {
        Cart::deliveryGet(1);
    }

    public function actionDeliveryDelete($id)
    {
        Cart::deliveryDelete($id);
    }

    public function actionDeliveryAdd($delivery)
    {
        Cart::deliveryAdd($delivery);
    }

}
