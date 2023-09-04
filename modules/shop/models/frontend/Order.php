<?php

namespace common\modules\shop\models\frontend;

use common\modules\catalog\models\backend\Element;
use common\modules\shop\models\backend\ClientManager;
use Yii;
use common\modules\users\models\Profile;
use common\modules\shop\models\UserCity;
use DateTime;
use common\modules\catalog\components\Helper;
use yii\db\Expression;
use yii\db\Query;

class Order extends \common\modules\shop\models\Order
{

    public static function create($cart, $delivery)
    {
        $order = self::orderInfoHtml($cart, $delivery);
        $cost = self::cost($cart);
        self::addOrderToDb($order, $cart, $cost, $delivery);
        self::clearCart();
        if (!isset($delivery['checkout_order'])) {
            self::updateStock($cart);
            self::managerMail('<p>Ваш клиент совершил онлайн заказ. Посмотреть детали заказа можно в 1с.</p>' . $order, 'Ваш клиент совершил онлайн заказ.');
            self::clientMail('<p>Добрый день, Вы совершили заказ на сайте shop.by.</p>' . $order, 'Ваш заказ');
        } else {
            self::managerMail('<p>Вашему клиенту требуется согласование онлайн заказа. Посмотреть детали заказа можно в 1с.</p>' . $order, 'Вашему клиенту требуется согласование онлайн заказа.');
            self::clientMail('<p>Добрый день, Вы совершили предварительный заказ на сайте shop.by. Ваш заказ будет откорректирован менеджером. Об измении статуса заказа Вы получите дополнительное уведомление.</p>' . $order, 'Ваш предварительный заказ');
        }
        self::clientSms();
    }

    public static function updateStatus($id, $statusOld, $statusNew)
    {
        Order::updateAll(['status' => $statusNew], 'id = :id', [':id' => $id]);
    }

    public static function cost($cart)
    {
        $sum = 0;
        foreach ($cart as $product) {
            //$sum += $product['Price'] * $product['Quantity'];
            if ($product['Quantity'] && $product['Price']) {
                $sum += round(round(round(self::comma($product['Price']) / 1.2, 2) * $product['Quantity'], 2) * 1.2, 2);
            }
        }
        $sum = number_format($sum, 2);
        return str_replace(".", "", $sum);
    }

    public static function comma($number)
    {
        //10024
        $kop = (int)substr($number, -2, 2); //24
        $kop_len = strlen($kop);
        if ($kop_len == 0) {
            $kop = '00';
        } else if ($kop_len == 1) {
            $kop = '0' . $kop;
        }
        $rub = substr($number, 0, mb_strlen($number) - 2); //100
        if ($rub == '') $rub = 0;
        return $rub . '.' . $kop;
    }

    public static function orderInfoHtml($cart, $delivery)
    {
        $model = Profile::findByUserId(Yii::$app->user->id);
        $city = UserCity::find()->where('id = :id', [':id' => $delivery['city_id']])->one();

        $sum = self::cost($cart);

        $date = date('Y-m-d H:i:s');
        $deliveryTime = $delivery['time'];
        $deliveryType = ($delivery['type'] == 1) ? 'Доставка' : 'Самовывоз';

        $html = '
                <div>
                    <p><b>Дата заказа:</b> ' . $date . '</p><br/>
                    <p><b>ФИО: </b>' . $model->surname . ' ' . $model->name . ' ' . $model->patronymic . '</p>
                    <p><b>' . $model->getAttributeLabel('firmName') . ': </b>' . $model->firmName . '</p>
                    <p><b>' . $model->getAttributeLabel('ynp') . ': </b>' . $model->ynp . '</p>
                    <p><b>' . $model->getAttributeLabel('legal_address') . ': </b>' . $model->legal_address . '</p>
                    <p><b>' . $model->getAttributeLabel('settlement_account') . ': </b>' . $model->settlement_account . '</p>
                    <p><b>' . $model->getAttributeLabel('phone_company') . ': </b>' . $model->phone_company . '</p>
                    <p><b>' . $model->getAttributeLabel('phone_director') . ': </b>' . $model->phone_director . '</p>
                    ';

        if ($delivery['type'] == 1) {
            $html .=
                '<p><b>Город: </b>' . $city->city . '</p>
                    <p><b>Область: </b>' . $city->region . '</p>
                    <p><b>День доставки: </b>' . $city->day . '</p>
                    <p><b>Адрес доставки: </b>' . $delivery['delivery'] . '</p>';
        }

        $html .= '
                    <p><b>' . $deliveryType . ': </b>' . $deliveryTime . '</p><br/>
                </div>

                <table  border="1">
                    <tr>
                        <th>№</th>
                        <th>Код товара</th>
                        <th>Наименование товара</th>
                        <th>Количество</th>
                        <th>Цена с НДС, шт</th>
                        <th>Цена с НДС, итого</th>
                    </tr>';
        $i = 1;
        foreach ($cart as $product) {
            $html .= '
                    <tr>
                        <td>'. $i . '</td>
                        <td>' . $product['Code_1c'];

            // Сборки
            if (count($product['Kits'])) {
                foreach ($product['Kits'] as $kit) {
                    $html .= '</br>' . $kit['code_1c'];
                }
            }

            $html .= '</td>
                        <td>' . $product['Product'];

            // Сборки
            if (count($product['Kits'])) {
                $html .= ' (Сборка)';
                foreach ($product['Kits'] as $kit) {
                    $html .= '</br>+ ' . $kit['title'];
                }
            }

            $product['Price'] = number_format($product['Price'], 0, '.', '');

            $html .= '</td>
                        <td>' . $product['Quantity'] . '</td>
                        <td>' . Helper::formatPrice($product['Price']) . '</td>
                        <td>' . Helper::formatPrice(number_format(round(round(round(self::comma($product['Price']) / 1.2, 2) * $product['Quantity'], 2) * 1.2, 2), 2)) . '</td>
                    </tr>';
            $i++;
        }
        $html .= '
                    <tr style="text-align: right;">
                        <th colspan="6">Итого: ' . Helper::formatPrice($sum) . '</th>
                    </tr>
                </table>
        ';

        return $html;
    }

    public static function addOrderToDb($html, $cart, $cost, $delivery)
    {
        $deliveryTime = $delivery['time'];
        $deliveryType = ($delivery['type'] == 1) ? 'Доставка' : 'Самовывоз';

        $cost = str_replace(' ', '', trim($cost));
        $cost = str_replace(',', '', trim($cost));
        $cost = str_replace('.', '', trim($cost));

        $order = new Order();
        $order->id_user = Yii::$app->user->id;
        $order->created = time();
        $order->data = $html;

        $file = fopen($_SERVER['DOCUMENT_ROOT'] . "/counter.txt", "a");//////////////////////////////
        fwrite($file, date('d.m.Y H:i:s') . ' ---$delivery--- ' . serialize($delivery) . PHP_EOL);///////////////////////
        fclose($file);/////////////////

        if ($delivery['checkout_order']) {
            $order->status = 9;
        } else {
            $order->status = 0;
        }
        $order->one_data = serialize(self::idCount($cart)['arr']);
        $order->cost = $cost;
        $order->id_manager = self::getIdManager();
        $order->delivery = serialize([
            'type' => $deliveryType,
            'time' => $deliveryTime,
            'city' => $delivery['city_title']
        ]);
        if (!$order->save()) {
            Yii::$app->mailer->compose()->setTo(Yii::$app->params['errorEmail'])->setFrom(Yii::$app->params['adminEmail'])->setSubject('Ошибка заказа!!!')->setHtmlBody(serialize($order))->send();
            die('Ошибка! С вами свяжутся.');
        }
    }

    public static function getIdManager()
    {
        return (int)ClientManager::find()->select('id_manager')->where('id_client = :id_client', [':id_client' => Yii::$app->user->id])->asArray()->one()['id_manager'];
    }

    public static function clearCart()
    {
        Cart::deleteAll('id_user = :id_user', [':id_user' => Yii::$app->user->id]);
    }

    public static function updateStock($cart, $id = '')
    {
        if ($cart) {
            $idCount = self::idCount($cart);
            $ids = $idCount['ids'];
            $arr = $idCount['arr'];
        } else {
            $str = Order::find()->select('one_data')->where('id = :id', [':id' => $id])->asArray()->one();
            //кол-во товара в счёте
            $arr = unserialize($str['one_data']);
            //ids товара
            $ids = [];
            foreach ($arr as $item) {
                $ids[] = $item['id'];
            }
        }
        //кол-во товара в БД
        $products = Element::find()->select('id, in_stock')->where(['in', 'id', $ids])->asArray()->all();

        foreach ($arr as $item) {
            foreach ($products as $product) {
                if ($item['id'] == $product['id']) {
                    $stockNew = $product['in_stock'] - $item['count'];
                    Element::updateAll(['in_stock' => $stockNew], 'id = :id', [':id' => $product['id']]);
                }
            }
        }
    }

    public static function idCount($cart)
    {
        $ids = [];
        $arr = [];
        foreach ($cart as $product) {
            if (in_array($product['Num'], $ids)) {
                foreach ($arr as $key => $item) {
                    if ($product['Num'] = $item['id']) {
                        $arr[$key]['count'] += $product['Quantity'];

                        // Сборки
                        if (count($product['Kits'])) {
                            foreach ($product['Kits'] as $kit) {
                                if (in_array($kit['id'], $ids)) {
                                    foreach ($arr as $key => $item) {
                                        if ($kit['id'] = $item['id']) {
                                            $arr[$key]['count'] += $product['Quantity'];
                                        }
                                    }
                                } else {
                                    $arr[] = [
                                        'id' => $kit['id'],
                                        'count' => $product['Quantity'],
                                    ];
                                }
                            }
                        }
                        // ---

                    }
                }
            } else {
                $arr[] = [
                    'id' => $product['Num'],
                    'count' => $product['Quantity'],
                ];
                $ids[] = $product['Num'];

                // Сборки
                if (count($product['Kits'])) {
                    foreach ($product['Kits'] as $kit) {
                        if (in_array($kit['id'], $ids)) {
                            foreach ($arr as $key => $item) {
                                if ($kit['id'] = $item['id']) {
                                    $arr[$key]['count'] += $product['Quantity'];
                                }
                            }
                        } else {
                            $arr[] = [
                                'id' => $kit['id'],
                                'count' => $product['Quantity'],
                            ];
                        }
                    }
                }
                // ---

            }
        }
        return [
            'ids' => $ids,
            'arr' => $arr,
        ];
    }

    public static function clientSms()
    {

    }

    public static function managerMail($html, $title)
    {
        // Письмо манагеру чей клиент сделал заказ
        $expression = new Expression('m.email, CONCAT_WS(" ", p.surname, p.name, p.patronymic) fio');
        $email = (new Query())
            ->select($expression)
            ->from('{{%shop_client_manager}} cm')
            ->leftJoin('{{%users}} m', 'm.id = cm.id_manager')
            ->leftJoin('{{%profiles}} p', 'p.user_id = m.id')
            ->where('cm.id_client = :id_client', [':id_client' => Yii::$app->user->id])
            ->one();
        Yii::$app->mailer->compose()
            ->setTo($email['email'])
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setSubject($title)
            ->setHtmlBody($html)
            ->send();

        // Письма начальникам манагера
        foreach (Yii::$app->params['emailCopyOrder'] as $mailManager => $mails) {
            if ($mailManager == $email['email']) {
                foreach ($mails as $mail) {
                    Yii::$app->mailer->compose()
                        ->setTo($mail)
                        ->setFrom(Yii::$app->params['adminEmail'])
                        ->setSubject('Клиент менеджера ' . $email['fio'] . ' сделал онлайн заказ.')
                        ->setHtmlBody($html)
                        ->send();
                }
            }
        }

        // Письма ещё комуто
        foreach (Yii::$app->params['orderEmail'] as $mail) {
            Yii::$app->mailer->compose()
                ->setTo($mail)
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setSubject('Заказ на сайте.')
                ->setHtmlBody($html)
                ->send();
        }
    }

    public static function clientMail($html, $title)
    {
        Yii::$app->mailer->compose()
            ->setTo(Yii::$app->user->identity->email)
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setSubject($title)
            ->setHtmlBody($html)
            ->send();
    }

    public static function addModel()
    {
        $products = (new Query())
            ->select('sc.id, sc.id_kit, e.title, e.price, sc.count, p.name, e.is_model, e2.title as parent_title,
                e.alias as alias_product, c.id as id_category, c.alias as alias_category, m.alias as alias_manufacturer, e.id as num')
            ->from('{{%shop_cart}} sc')
            ->leftJoin('{{%catalog_element}} e', 'e.id = sc.id_element')
            ->leftJoin('{{%catalog_category}} c', 'c.id = e.id_category')
            //->leftJoin('{{%shop_user_discount}} ud', 'ud.id_category = c.id') ud.discount,
            ->leftJoin('{{%catalog_photo}} p', 'p.id_element = e.id')
            ->leftJoin('{{%catalog_manufacturer}} m', 'm.id = e.id_manufacturer')
            ->leftJoin('{{%catalog_model_rel}} mr', 'mr.id_element_children = e.id')
            ->leftJoin('{{%catalog_element}} e2', 'mr.id_element_parent = e2.id')
            ->where('sc.id_user = :id_user AND (p.is_cover = 1 OR p.is_cover IS NULL)', [':id_user' => Yii::$app->user->id])
            ->all();

        // Скидки
        $categories = array_column($products, 'id_category');
        if (count($categories)) {
            $discounts = (new Query())->select('*')->from('{{%shop_user_discount}}')->where('id_user = :id_user', [':id_user' => Yii::$app->user->id])->all();
            if (count($discounts)) {
                foreach ($discounts as $discount) {
                    foreach ($products as $key => $product) {
                        if ($discount['id_category'] == $product['id_category']) {
                            $products[$key]['discount'] = $discount['discount'];
                        }
                    }
                }
            }
        }

        $res = [];
        foreach ($products as $product) {
            $res[] = [
                'Id' => $product['id'],
                'Num' => $product['num'],
                'Product' => ($product['parent_title']) ? $product['parent_title'] . ' ' . $product['title'] : $product['title'],
                'Quantity' => $product['count'],
                'Price' => round($product['price'] / 100 * (100 - $product['discount']), 0),
                //'Discount' => $product['discount'], Изменения. Нужно скрыть скидку
                'Img' => ($product['name']) ? '/statics/catalog/photo/images_small/' . $product['name'] : '',
                'Kit' => ($product['id_kit']) ? 1 : 0,
                'Url' => '/catalog/' . $product['alias_category'] . '/' . $product['alias_manufacturer'] . '/' . $product['alias_product'] . '/',
            ];
        }
        return $res;
    }

    // Проверка на ошибки

    public static function errors($cart, $datetime)
    {
        $error = false;

        // Профиль не активирован
        if (self::errorProfileActivation()) return true;

        // Профиль не заполнен
        if (self::errorProfileUpdate()) return true;

        // Цена ниже минимальной для доставки
        if (self::errorMinPrice($cart)) return true;

        // Количество товара в позиции должно быть более 0
        if (self::errorStockNull($cart)) return true;

        // Столько товаров нет на складе
        if (self::errorStock($cart)) return true;

        // Юзеру не назначили менеджера
        if (self::errorManager()) return true;

        // Дату подхитрожопили и она не вида ##-##-#### ##:##
        if (self::errorDatetimeMask($datetime)) return true;

        // Дату подхитрожопили и она не в допустимых пределах. Скажем заказали доставку на год назад.
        if (self::errorDatetime($datetime)) return true;

        // Заказ может делать только Клиент. Не администратор, контент-менеджер, менеджер по продажам и т.п.
        if (key(Yii::$app->authManager->getRolesByUser(Yii::$app->user->id)) != 'user') {
            Yii::$app->session->setFlash('danger', 'Оформлять заказы разрешено только пользователям с правами "user"');
            return true;
        }

        Yii::$app->session->setFlash('success', 'Заказ успешно оформлен.');

        return $error;
    }

    public static function errorMinPrice($cart)
    {
        $sum = 0;
        foreach ($cart as $product) {
            $sum += $product['Price'] * $product['Quantity'];
        }
        if ($sum < Yii::$app->params['minDeliveryPrice']) {
            Yii::$app->session->setFlash('danger', 'Оформление невозможно по причине низкой суммы заказа. Минимальная сумма заказа: ' . number_format(Yii::$app->params['minDeliveryPrice'] / 100, 2, '.', ' ') . ' BYN');
            return true;
        } else if ($sum > Yii::$app->params['maxDeliveryPrice']) {
            Yii::$app->session->setFlash('danger', 'Оформление невозможно по причине высокой суммы заказа. Максимальная сумма заказа: ' . number_format(Yii::$app->params['maxDeliveryPrice'] / 100, 2, '.', ' ') . ' BYN');
            return true;
        } else {
            return false;
        }

    }

    public static function errorProfileActivation()
    {
        $status = \common\modules\users\models\backend\User::find()->where('id = :id', [':id' => Yii::$app->user->id])->asArray()->one()['status_id'];
        if ($status == 0) {
            Yii::$app->session->setFlash('danger', 'На данный момент Вы не можете оформить заказ, так как Ваш аккаунт не активирован. Аккаунт будет активирован после проверки модератора.');
            return true;
        } else {
            return false;
        }
    }

    public static function errorProfileUpdate()
    {
        $profile = Profile::findByUserId(Yii::$app->user->id);
        if ($profile->name == '' ||
            $profile->surname == '' ||
            $profile->patronymic == '' ||
//            $profile->birthday == '' ||
//            $profile->card == '' ||
            $profile->firmName == '' ||
            $profile->ynp == '' ||
            $profile->legal_address == '' ||
            $profile->settlement_account == '' ||
            $profile->phone_company == '' ||
            $profile->phone_director == '' ||
            $profile->id_city == ''
        ) {
            Yii::$app->session->setFlash('danger', 'Перед оформлением заказа необходимо заполнить свой профиль. <a href="/my/settings/update/">Заполнить профиль</a>');
            return true;
        } else {
            return false;
        }
    }

    public static function errorStockNull($cart)
    {
        $flag = 0;
        foreach ($cart as $product) {
            if ($product['Quantity'] <= 0) {
                $flag = 1;
            }
        }

        if ($flag) {
            Yii::$app->session->setFlash('danger', 'Выберите количество товара более 0, или удалите данный товар.');
            return true;
        } else {
            return false;
        }
    }

    public static function errorStock($cart)
    {
        $ids = [];
        $arr = [];
        foreach ($cart as $product) {
            // На случай сборок
            if (in_array($product['Num'], $ids)) {
                foreach ($arr as $key => $item) {
                    if ($product['Num'] = $item['id']) {
                        $arr[$key]['quantity'] += $product['Quantity'];
                    }
                }
            } else {
                $arr[] = [
                    'id' => $product['Num'],
                    'quantity' => $product['Quantity'],
                ];
                $ids[] = $product['Num'];
            }
        }
        $products = Element::find()->select('id, title, in_stock')->where(['in', 'id', $ids])->asArray()->all();

        $pr = [];
        foreach ($arr as $item) {
            foreach ($products as $product) {
                if ($item['id'] == $product['id']) {
                    if ($item['quantity'] > $product['in_stock']) {
                        $pr[] = $product['title'];
                    }
                }
            }
        }
        if (count($pr)) {
            Yii::$app->session->setFlash('danger', 'Необходимого количества товара нет на складе:<br/> ' . implode('<br/>', $pr) . ' <br/><br/>Пожалуйста свяжитесь с менеджером для уточнения наличия. <a href="/contacts/">Перейти в контакты.</a> ');
            return true;
        } else {
            return false;
        }

    }

    public static function errorManager()
    {
        if ((new \yii\db\Query())
                ->select('m.id')
                ->from('tbl_users u')
                ->leftJoin('tbl_shop_client_manager cm', 'u.id = cm.id_client')
                ->leftJoin('tbl_users m', 'm.id = cm.id_manager')
                ->where('u.id = :id', [':id' => Yii::$app->user->id])
                ->one()['id'] === NULL
        ) {

            foreach (Yii::$app->params['errorEmail'] as $mail) {
                Yii::$app->mailer->compose()
                    ->setTo($mail)
                    ->setFrom(Yii::$app->params['adminEmail'])
                    ->setSubject(Yii::$app->name . " - Ошибка назначения менеджера.")
                    ->setHtmlBody('<html><body><a href="https://shop.by">Перейти на сайт</a></body></html>')
                    ->send();
            }

            Yii::$app->session->setFlash('danger', 'Вам не назначен менеджер. Мы уже оповещены об этом и в скором времени её исправим.');
            return true;
        } else {
            return false;
        }
    }

    public static function errorDatetimeMask($datetime)
    {
        $mask = '##-##-#### ##:##';
        if (preg_replace('/[^- :]/', '#', $datetime) != $mask) {
            Yii::$app->session->setFlash('danger', 'Неверно указана дата или время.');
            return true;
        } else {
            return false;
        }
    }

    public static function errorDatetime($datetime)
    {
        if (0) {
            Yii::$app->session->setFlash('danger', 'Неверно указана дата или время.');
            return true;
        } else {
            return false;
        }
    }

    // orders & order pages

    public static function getUserOrders()
    {
        return $orders = Order::find()
            ->where('id_user = :id_user', [':id_user' => Yii::$app->user->id])
            ->orderBy('created DESC')
            ->asArray()->all();
    }

    // delete order

    public static function deleteOrder($model)
    {
        $dateEnd = new DateTime();
        $dateEnd->setTimestamp($model->created)->modify('+3 day');
        $dateNow = new DateTime('NOW');
        $secondLeft = strtotime($dateEnd->format("d.m.Y H:i:s")) - strtotime($dateNow->format("d.m.Y H:i:s"));

        if (Yii::$app->user->id != $model->id_user || $model->status == 1 || $model->status == 2 || $model->status == 3) {
            Yii::$app->session->setFlash('danger', 'Ошибка. Возможные причины: вы пытаетесь удалить не свою заявку; время для удаления истекло; заявка оплачена.');
        } else {
            if (!in_array($model->status, range(8, 10))) {
                self::returnStock($model->id); //Разбронировать товар
            }
            //Удалить pdf
            @unlink($_SERVER['DOCUMENT_ROOT'] . '/statics/web/shop/invoices/pdf/' . Order::find()->select('invoice_pdf')->where('id = :id', [':id' => $model->id])->one()['invoice_pdf']);
            $model->delete();
        }
    }

    public static function returnStock($id)
    {
        $str = Order::find()->select('one_data')->where('id = :id', [':id' => $id])->asArray()->one()['one_data'];
        $arr = unserialize($str);
        $products = [];
        foreach ($arr as $a) {
            if ($a['count']) {
                $products[] = $a;
            }
        }
        foreach ($products as $product) {
            $element = Element::findOne($product['id']);
            $element->updateCounters(['in_stock' => $product['count']]);
        }
    }

    // Change delivery city

    public static function deliveryInfo()
    {
        $id = Yii::$app->request->post('id');
        if (Yii::$app->request->isPost && $id && self::cityIsset($id)) {
            return (new Query())
                ->select('uc.*')
                ->from('{{%shop_user_city}} uc')
                ->where('uc.id = :id', [':id' => $id])
                ->one();
        } else {
            return (new Query())
                ->select('uc.*')
                ->from('{{%shop_user_city}} uc')
                ->leftJoin('{{%profiles}} p', 'p.id_city = uc.id')
                ->where('p.user_id = :user_id', [':user_id' => Yii::$app->user->id])
                ->one();
        }
    }

    public static function cityIsset($id)
    {
        return UserCity::find()->select('id')->where('id = :id', [':id' => $id])->asArray()->one()['id'];
    }
}