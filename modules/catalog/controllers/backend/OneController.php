<?php

namespace common\modules\catalog\controllers\backend;


use common\modules\shop\models\backend\Order;
use Yii;
use backend\components\Controller;
use yii\filters\VerbFilter;
use \yii\web\Response;

use common\modules\catalog\components\import\onec\Api;

ini_set("max_execution_time","3600");

class OneController extends Controller
{

    /**
     * post - /one/prices/                   - обновить цены и наличие по коду 1с
     * get  - /one/orders/{status}           - получить заказы со статусом status
     * post - /one/order/{id}{status}        - установить заказу статус
     * post - /one/invoices/{id}{type=pdf}  - загрузить на сайт счёт на оплату
     *
     * put  - /one/manager/                  - Создать менеджера
     * post - /one/manager/{id}              - Изменить менеджера
     *
     * put  - /one/cm/                       - Создать связь клиент-менеджер
     * post - /one/cm/{fio_manager}{ynp}     - Изменить связь клиент-менеджер
     *
     * post - /one/discount/{id}{discount}   - Назначение клиенту скидки
     *
     * post - /one/change-order/{data}       - Изменение информации о заказе. Количество, цена.
     */

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['prices', 'order', 'orders', 'invoices', 'manager', 'cm', 'discount', 'change-order', 'tovs', 'change-in-stock', 'invoice-number', 'change-invoice', 'category1c', 'discount1c', 'user', 'bonus-order','new-card', 'new-card-status'],
                'roles' => ['@','?'],
            ]
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'prices' => ['post','get'],
                'order' => ['post'],
                'orders' => ['get'],
                'invoices' => ['post'],
                'discount' => ['post'],
                'change-order' => ['post'],
                'tovs' => ['post'],
                'change-in-stock' => ['post'],
                'invoice-number' => ['post'],
                'change-invoice' => ['post'],
                'category1c' => ['post','get'],
                'discount1c' => ['post','get'],
                'user' => ['post','get'],
                'bonus-order' => ['post','get'],
                'new-card' => ['post','get'],
                'new-card-status' => ['post','get'],
            ]
        ];

        return $behaviors;
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        if (true) { //if (Api::login()) {
            return parent::beforeAction($action);
        } else {
            Yii::$app->response->format = Response::FORMAT_JSON;
            die(json_encode('Неверный логин или пароль'));
        }
    }

    public function actionPrices()
    {
//        $data = 'a:1:{i:0;O:8:"stdClass":7:{s:4:"code";s:6:"129668";s:5:"price";s:5:"17184";s:9:"price_old";s:3:"000";s:8:"currency";s:3:"BYN";s:6:"status";s:1:"3";s:5:"halva";s:1:"0";s:8:"in_stock";i:5;}}';
//        $data = 'a:1:{i:0;O:8:"stdClass":7:{s:4:"code";s:5:"10000";s:5:"price";s:5:"10230";s:9:"price_old";s:5:"13983";s:8:"currency";s:3:"BYN";s:6:"status";s:1:"3";s:5:"halva";s:1:"0";s:8:"in_stock";i:5;}}';
//        $products = unserialize($data);
//        $products = (json_decode($data, true));//удалить



        $products = json_decode(Yii::$app->request->post('data'));////////////////////////////
//        $file = fopen($_SERVER['DOCUMENT_ROOT'] . "/counter.txt", "a");///////////////
//        fwrite($file, date('d.m.Y H:i:s') . ' -------ImportPrices . $products -------- ' . serialize($products) . PHP_EOL);/////////////////
//        fwrite($file, date('d.m.Y H:i:s') . ' -------ImportPrices . count($products) -------- ' . count($products) . PHP_EOL);/////////////////
//        fclose($file);////////////////////


        Api::ImportPrices($products);
    }

    public function actionOrders()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Api::getOrders();
    }

    public function actionOrder()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Api::setOrder();
    }

    public function actionInvoices()
    {
        return Api::addInvoices();
    }

    public function actionManager()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->isPut) {
            return Api::createManager();
        } else if (Yii::$app->request->isPost) {
            return Api::updateManager();
        } else {
            return [
                'msg' => 'Метод передачи должен быть post или put',
                'error' => true,
            ];
        }
    }

    public function actionDiscount() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->isPost) {
            return Api::updateDiscount();
        } else {
            return [
                'msg' => 'Метод передачи должен быть post или put',
                'error' => true,
            ];
        }
    }

    public function actionCm()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->isPut) {
            return Api::createCM();
        } else if (Yii::$app->request->isPost) {
            return Api::updateCM();
        } else {
            return [
                'msg' => 'Метод передачи должен быть post или put',
                'error' => true,
            ];
        }
    }

    public function actionChangeOrder()
    {
        Api::changeOrder();
    }


    //Выгрузка из 1С недостающих товаров
    public function actionTovs()
    {
        Api::getTovs();
    }

    public function actionChangeInStock()
    {
        Api::getChangeInStock();

    }

    public function actionInvoiceNumber()
    {
        Api::getInvoiceNumber();

    }

    public function actionChangeInvoice()
    {
        Api::getchangeInvoice();
    }

    public function actionCategory1c()
    {
        Api::ImportCategory1c();
    }

    public function actionDiscount1c()
    {
        Api::ImportDiscount1c();
    }



    public function actionUser()
    {
//        Yii::$app->response->format = Response::FORMAT_JSON;
        return Api::createUser();

    }

    public function actionBonusOrder()
    {
        //Yii::$app->response->format = Response::FORMAT_JSON;
        return Api::addBonusOrder();

    }



    public function actionNewCard()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Api::getNewCard();
    }

    public function actionNewCardStatus()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Api::setNewCardStatus();
    }



}
