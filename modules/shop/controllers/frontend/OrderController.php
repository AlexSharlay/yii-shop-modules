<?php

namespace common\modules\shop\controllers\frontend;

use common\modules\shop\models\Delivery;
use common\modules\shop\models\frontend\Cart;
use common\modules\users\models\frontend\User;
use Yii;
use frontend\components\Controller;
use common\modules\shop\models\frontend\Order;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\debug\models\search\Profile;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use common\modules\shop\components\Invoice;

class OrderController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ]
            ]
        ];
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionOrder()
    {
        return $this->render('order', [
            'cityData' => Order::deliveryInfo(),
            'minSumDelivery' => Delivery::find()->select('price_to')->where('id=9')->asArray()->one(),
        ]);
    }

    public function actionCheckout()
    {
        $cart = Cart::cartGet();
        if (Order::errors($cart, Yii::$app->request->post('time'))) {
            $this->redirect(\Yii::$app->urlManager->createUrl("/order/"));
        } else {
            Order::create($cart, [
                'type' => Yii::$app->request->post('type'),
                'time' => Yii::$app->request->post('time'),
                'city_id' => Yii::$app->request->post('city_id'),
                'city_title' => Yii::$app->request->post('city_title'),
                'delivery' => Yii::$app->request->post('delivery'),
                'checkout_order' => Yii::$app->request->post('checkout_order'),
            ]);

            $file = fopen($_SERVER['DOCUMENT_ROOT'] . "/counter.txt", "a");//////////////////////////////
            fwrite($file, date('d.m.Y H:i:s') . ' ---Yii::$app->request->post(\'checkout_order\')--- ' . serialize(Yii::$app->request->post('checkout_order')) . PHP_EOL);///////////////////////
            fwrite($file, date('d.m.Y H:i:s') . ' ---Yii::$app->request->post()--- ' . serialize(Yii::$app->request->post()) . PHP_EOL);///////////////////////
            fclose($file);/////////////////

            return Yii::$app->getResponse()->redirect('/my/orders/');
        }
    }

    // Личный кабинет Клиента


    public function actionOrders()
    {
        $id = Yii::$app->request->post()['order'];
        if ($id) {
            Order::updateStatus($id, 10, 7);
            Order::updateStock('', $id);
        };

        return $this->render('index', [
            'dataProvider' => new ActiveDataProvider([
                'query' => Order::find()->where('id_user = :id_user', [':id_user' => Yii::$app->user->id])->orderBy(['created' => SORT_DESC]),
            ]),
            'manager' => (new Query())
                ->select('u.email, p.*')
                ->from('tbl_users u')
                ->leftJoin('tbl_shop_client_manager cm', 'u.id = cm.id_manager')
                ->leftJoin('tbl_profiles p', 'p.user_id = u.id')
                ->where('cm.id_client = :id_client', [':id_client' => Yii::$app->user->id])->one()
        ]);
    }


    public function actionViewOrder($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionDeleteOrder($id)
    {
        $model = $this->findModel($id);
        Order::deleteOrder($model);
        return Yii::$app->getResponse()->redirect('/my/orders/');
    }

    public function actionInvoicePdf($id)
    {
        echo Invoice::pdf($id);
    }

    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}