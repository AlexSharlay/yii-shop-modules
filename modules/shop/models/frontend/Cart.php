<?php

namespace common\modules\shop\models\frontend;

use common\modules\users\models\Profile;
use Yii;
use yii\db\Query;
use common\modules\shop\components\Helper;

class Cart extends \common\modules\shop\models\Cart
{

    public static function cartDelete($id)
    {
        Cart::deleteAll('id_user = :id_user AND id = :id', [':id_user' => Yii::$app->user->id, ':id' => $id]);
    }

    public function getKitPriceByIdForJson($id_kit, $id_element_parent)
    {

        $command = Yii::$app->db->createCommand("
            SELECT sum(e.price) as price
            FROM {{%catalog_kit_rel}} k
            JOIN {{%catalog_element}} e ON k.id_element_children = e.id
            WHERE k.id_kit = :id_kit
            AND k.id_element_parent = :id_element_parent")
            ->bindValue(':id_kit', $id_kit)
            ->bindValue(':id_element_parent', $id_element_parent);
        return $command->queryOne()['price'];
    }

    public static function cartGet($json = 0)
    {

        $products = (new Query())
            ->select('sc.id, ckr.id as id_kit, ckr.id_kit as num_kit, e.id as id_element, e.title, e.code_1c, e.title_model, e.price, sc.count,  p.name, e.is_model, e2.title as parent_title,
                e.alias as alias_product, c.id as id_category, e.id_category_1c, c.alias as alias_category, m.alias as alias_manufacturer, e.id as num')/*ud.discount,*/
            ->from('{{%shop_cart}} sc')
            ->leftJoin('{{%catalog_element}} e', 'e.id = sc.id_element')
            ->leftJoin('{{%catalog_category}} c', 'c.id = e.id_category')
            //->leftJoin('{{%shop_user_discount}} ud', 'ud.id_category = c.id')
            ->leftJoin('{{%catalog_photo}} p', 'p.id_element = e.id')
            ->leftJoin('{{%catalog_manufacturer}} m', 'm.id = e.id_manufacturer')
            ->leftJoin('{{%catalog_model_rel}} mr', 'mr.id_element_children = e.id')
            ->leftJoin('{{%catalog_element}} e2', 'mr.id_element_parent = e2.id')
            ->leftJoin('{{%catalog_kit_rel}} ckr', 'sc.id_kit = ckr.id')
            ->where('sc.id_user = :id_user AND (p.is_cover = 1 OR p.is_cover IS NULL)', [':id_user' => Yii::$app->user->id])
            ->distinct('sc.id')
            ->all();
        // $products = $products->createCommand();
        //echo $products = $products->rawSql;

        // Скидка для корзины
        $categories = array_column($products, 'id_category_1c');
        if (count($categories)) {
            $discounts = (new Query())->select('*')->from('{{%shop_user_discount1c}}')->where('id_user = :id_user', [':id_user' => Yii::$app->user->id])->all();
            if (count($discounts)) {
                foreach ($discounts as $discount) {
                    foreach ($products as $key => $product) {
                        $arr_id_category_1c = json_decode($product['id_category_1c']);
                        if (is_array($arr_id_category_1c)) {
                            foreach ($arr_id_category_1c as $id_category_1c) {
                                if ($discount['id_category'] == $id_category_1c) {
                                    $products[$key]['discount'] = $discount['discount'];
                                }
                            }
                        }
                    }
                }
            }
        }


        /*        // Стоимость со скидкой
                foreach ($products as $key => $product) {
                    if (!empty($product['discount'])) {
                        $products[$key]['price'] = round($product['price'] / 100 * (100 - $product['discount']), 0);
                    }
                }*/

        // Добавить информацию о сборках
        $products = self::getKits($products);

        if ($json) {
            Helper::jsonHeader();
            echo json_encode(self::createCartArr($products));
        } else {
            return self::createCartArr($products);
        }
    }

    public static function cartAdd($id, $kit_id, $count)
    {
        if ($count) {
            // Если такая запись уже есть, то + count
            if ($count_db = Cart::find()->select('count')->where('id_user = :id_user AND id_element = :id_element AND id_kit = :id_kit')->addParams([':id_user' => Yii::$app->user->id, ':id_element' => $id, ':id_kit' => $kit_id,])->one()['count']) {
                Cart::updateAll(
                    ['count' => $count_db + $count,],
                    'id_user = :id_user AND id_element = :id_element AND id_kit = :id_kit',
                    [':id_user' => Yii::$app->user->id, ':id_element' => $id, ':id_kit' => $kit_id,]
                );
            } else {
                $model = new Cart();
                $model->id_user = Yii::$app->user->id;
                $model->id_element = $id;
                $model->id_kit = $kit_id;
                $model->count = $count;
                $model->save();
            }
        }
    }

    public static function getKits($products)
    {
        $where = '1 = 1';
        if (count($products)) {
            $where = [];
            foreach ($products as $product) {
                if ($product['id_kit']) {
                    $where[] = '(ckr.id_element_parent = ' . $product['id_element'] . ' AND ckr.id_kit = ' . $product['id_kit'] . ')';
                } else {
                    $where[] = 'ckr.id_element_parent = ' . $product['id_element'];
                }
            }
            $where = implode(' AND ', $where);
        }

        // Получить информацию по сборкам.
        $kits = (new Query())
            ->select('DISTINCT(ckr.id_element_parent) as id_parent, e.id, e.code_1c, e.id_category, e.id_category_1c, e.title, e.price, ckr.id_kit')//ud.discount,
            ->from('{{%catalog_kit_rel}} ckr')
            ->leftJoin('{{%catalog_element}} e', 'ckr.id_element_children = e.id')
            ->leftJoin('{{%catalog_category}} c', 'c.id = e.id_category')
            //->leftJoin('{{%shop_user_discount}} ud', 'ud.id_category = c.id')
            ->where($where)
            ->all();

        // Скидка по сборкам для корзины
        $categories = array_column($kits, 'id_category_1c');
        if (count($categories)) {
            $discounts = (new Query())->select('*')->from('{{%shop_user_discount1c}}')->where('id_user = :id_user', [':id_user' => Yii::$app->user->id])->all();
            if (count($discounts)) {
                foreach ($discounts as $discount) {
                    foreach ($kits as $key => $kit) {
                        if ($discount['id_category'] == $kit['id_category_1c']) {
                            $kits[$key]['discount'] = $discount['discount'];
                        }
                    }
                }
            }
        }

        // Добавить товарам информацию о сборках
        foreach ($products as $key => $product) {
            foreach ($kits as $kit) {
                if ($product['id_element'] == $kit['id_parent'] && $product['id_kit']) {
                    $price = round($kit['price'] / 100 * (100 - $kit['discount']), 0);
                    $products[$key]['kits'][] = [
                        'id' => $kit['id'],
                        'code_1c' => $kit['code_1c'],
                        'title' => $kit['title'],
                        //'price' => $price,
                    ];
                    $products[$key]['price'] += $price;
                }
            }
        }
        return $products;
    }

    public static function createCartArr($products)
    {
        $res = [];
        foreach ($products as $product) {
            $res[] = [
                'Id' => $product['id'],
                'Num' => $product['num'],
                'Code_1c' => $product['code_1c'],
                'Product' => ($product['parent_title']) ? $product['parent_title'] . ' ' . $product['title_model'] : $product['title'],
                'Quantity' => $product['count'],
                'Price' => round($product['price'] / 100 * (100 - $product['discount']), 0),
                'Img' => ($product['name']) ? '/statics/catalog/photo/images/' . $product['name'] : '',
                'Kit' => ($product['num_kit']) ? $product['num_kit'] + 1 : 0,
                'Url' => '/catalog/' . $product['alias_category'] . '/' . $product['alias_manufacturer'] . '/' . $product['alias_product'] . '/',
                'Kits' => (count($product['kits'])) ? $product['kits'] : [],
            ];
        }
        return $res;
    }

    public static function changeQuantity($id, $quantity)
    {
        if ($quantity < 0) $quantity = 0;
        Cart::updateAll(
            ['count' => $quantity],
            'id_user = :id_user AND id = :id',
            [':id_user' => Yii::$app->user->id, ':id' => $id]
        );
    }

    public static function deliveryDay()
    {
        return (new Query())
            ->select('suc.day as day')
            ->from('{{%profiles}} p')
            ->leftJoin('{{%shop_user_city}} suc', 'p.id_city = suc.id')
            ->where('p.user_id = :user_id', [':user_id' => Yii::$app->user->id])
            ->one()['day'];
    }

    // При оформлении заказа танцы с адресом доставки

    public static function deliveryGet($json = 0)
    {
        $deliveries = (new Query())
            ->select('legal_address, delivery_address')
            ->from('{{%profiles}}')
            ->where('user_id = :user_id', [':user_id' => Yii::$app->user->id])
            ->one();

        if ($json) {
            Helper::jsonHeader();
            echo json_encode(self::createDeliveryArr($deliveries));
        } else {
            return self::createDeliveryArr($deliveries);
        }
    }

    public static function createDeliveryArr($deliveries)
    {
        $res[] = [
            'Id' => 0,
            'Title' => $deliveries['legal_address'],
        ];
        if (!empty($deliveries['delivery_address'])) {
            $ds = unserialize($deliveries['delivery_address']);
            if (is_array($ds) && count($ds)) {
                foreach ($ds as $key => $d) {
                    $res[] = [
                        'Id' => ++$key,
                        'Title' => $d,
                    ];
                }
            }
        }
        return $res;
    }

    public static function deliveryDelete($id)
    {
        $delivery_address = array_column(self::deliveryGet(), 'Title');
        unset($delivery_address['0']);
        unset($delivery_address[$id]);
        $delivery_address = array_values($delivery_address);
        Profile::updateAll(
            ['delivery_address' => serialize($delivery_address)],
            'user_id = :user_id',
            [':user_id' => Yii::$app->user->id]
        );
    }

    public static function deliveryAdd($delivery)
    {
        $delivery_address = array_column(self::deliveryGet(), 'Title');
        unset($delivery_address['0']);
        $delivery_address[] = $delivery;
        $delivery_address = array_values($delivery_address);
        Profile::updateAll(
            ['delivery_address' => serialize($delivery_address)],
            'user_id = :user_id',
            [':user_id' => Yii::$app->user->id]
        );
    }
}