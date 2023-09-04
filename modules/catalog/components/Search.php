<?php

namespace common\modules\catalog\components;

use common\modules\catalog\models\frontend\Category;
use common\modules\catalog\models\frontend\Element;
use Yii;
use yii\web\HttpException;
use common\modules\shop\models\frontend\UserDiscount1c;

class Search
{

    protected static $itemsPerPage = 30;

    public static function searchProducts($search, $page, $sort, $category)
    {
        $search = self::deleteSpaceInNumber($search); // Костыль поиска артикула 23340 000 и 23340000 как один товар

        $data = self::getProducts($search, $page, $sort, $category);
        $arr['products'] = $data['products'];
        $arr['categories'] = $data['categories'];
        $arr['count'] = self::getProductsCount($search, $page, $category);
        if (ceil($arr['count'] / self::$itemsPerPage) > 0) {
            $arr['pageAll'] = range(1, ceil($arr['count'] / self::$itemsPerPage), 1);
        } else {
            $arr['pageAll'] = [];
        }
        return $arr;
    }

    public static function getProducts($search, $page, $sort, $category)
    {

        $discounts = UserDiscount1c::getUserDiscount1c(Yii::$app->user->id);

        if (!in_array($sort, ['title asc', 'title desc', 'price asc', 'price desc']))
            throw new HttpException(400, $sort);

        if ($page < 0)
            throw new HttpException(400, $page);

        if (strlen($search['0']) < 3)
            return [];

        switch ($sort) {
            case 'title asc':
                $order = 'ce.title asc';
                break;
            case 'title desc':
                $order = 'ce.title desc';
                break;
            case 'price asc':
                $order = 'ce.price asc';
                break;
            case 'price desc':
                $order = 'ce.price desc';
                break;
        }

        $itemsPerPage = self::$itemsPerPage;

        // @todo: Сортировка по цене будет не верная при наличии скидок. Потом учесть это.

        // Получить товары
//        $sql = '
//            SELECT ce.id, CONCAT(cc.title_pre," ",m.title," ",ce.title," ",ce.code_1c) as title, ce.price, ce.price_old,
//            cc.alias as category_alias, cc.id as category_id, ce.id_category_1c, cc.title as category_title, cc.ico as category_img, m.alias as manufacturer_alias, ce.alias as product_alias
//            FROM `tbl_catalog_category` `cc`
//            LEFT JOIN `tbl_catalog_element` `ce` ON cc.id = ce.id_category
//            LEFT JOIN `tbl_catalog_manufacturer` `m` ON m.id = ce.id_manufacturer
//            WHERE ce.published = 1
//            AND ce.code_1c < 9000000
//            AND ce.price > 0
//            AND ce.in_stock > 0';

        $sql = '
            SELECT ce.id, CONCAT(cc.title_pre," ",m.title," ",ce.title," ",ce.code_1c) as title, ce.vendor_code, ce.price, ce.price_old, cc.alias as category_alias, 
            cc.id as category_id, ce.id_category_1c, cc.title as category_title, cc.ico as category_img, m.alias as manufacturer_alias, ce.alias as product_alias
            FROM `tbl_catalog_category` `cc`
            LEFT JOIN `tbl_catalog_element` `ce` ON cc.id = ce.id_category
            LEFT JOIN `tbl_catalog_manufacturer` `m` ON m.id = ce.id_manufacturer
            WHERE ce.published = 1
            AND ce.price > 0';

        if ($category) {
            $sql .= '
           AND ce.id_category = ' . $category;
        }
        $sql .= '
            AND CONCAT_WS(" ", cc.title_pre,m.title,ce.title,ce.desc_mini,ce.code_1c, ce.vendor_code) LIKE :search';
        if ($search['1'] != '') {
            $sql .= '
            OR CONCAT_WS(" ", cc.title_pre,m.title,ce.title,ce.desc_mini,ce.code_1c, ce.vendor_code) LIKE :search2';
        }
        $sql .= '
            ORDER BY ' . $order . '
            LIMIT :from, :to';

        $search['0'] = "%" . $search['0'] . "%";
        $from = ($page - 1) * $itemsPerPage;

        $command = \Yii::$app->db->createCommand($sql);
        $command->bindParam(':search', $search['0'], \PDO::PARAM_STR);

        $command->bindParam(':from', $from, \PDO::PARAM_INT);
        $command->bindParam(':to', $itemsPerPage, \PDO::PARAM_INT);

        if ($search['1'] != '') {
            $search['1'] = "%" . $search['1'] . "%";
            $command->bindParam(':search2', $search['1'], \PDO::PARAM_STR);
        }

        $products = $command->queryAll();

        // Учёт цены со скидкой
        foreach ($products as $k => $product) {
            $arr_id_category_1c = json_decode($product['id_category_1c']);
            if (is_array($arr_id_category_1c)) {
                foreach ($arr_id_category_1c as $id_category_1c) {
                    if (array_key_exists($id_category_1c, $discounts)) {
                        if ($products[$k]['price']) {
                            $products[$k]['price'] = round($products[$k]['price'] / 100 * (100 - $discounts[$id_category_1c]), 0);
                        }
//if ($products[$k]['price_old']) {$products[$k]['price_old'] = round($products[$k]['price_old'] / 100 * (100 - $discounts[$id_category_1c]), 0);}  ////////////////////////////
                    }
                }
            }
        }

        // Данные о категориях
        $categoriesIds = [];
        $categories = [];
        foreach ($products as $k => $product) {
            if (!in_array($product['category_id'], $categoriesIds)) {
                $categoriesIds[] = $product['category_id'];
                $categories[] = [
                    'id' => $product['category_id'],
                    'title' => $product['category_title'],
                    'img' => '/statics/catalog/category/images/' . $product['category_img'],
                ];
            }
        }

        $arr['products'] = $products;

        $ids = [];
        foreach ($products as $product) {
            $ids[] = $product['id'];
        }

        // Получить их фото. Надо было одним запросом, но уже ладно
        $photos = [];
        if (count($ids) > 0) {
            $sql = '
            SELECT p.id_element, p.name
            FROM `tbl_catalog_photo` `p`
            WHERE p.is_cover = 1
            AND id_element IN (' . implode(',', $ids) . ')';
            $command = \Yii::$app->db->createCommand($sql);
            $photos = $command->queryAll();
        }

        // Соединяем фото с товарами
        $p = [];
        foreach ($products as $k => $product) {
            $p[$k] = $product;
            $cover_isset = 0;
            foreach ($photos as $photo) {
                if ($product['id'] == $photo['id_element']) {
                    $p[$k]['photo'] = '/statics/catalog/photo/images/' . $photo['name']; //cover
                    $cover_isset++;
                }
            }
            if ($cover_isset == 0) {
                $p[$k]['photo'] = '/statics/catalog/photo/images/_no_photo.jpg';
            }

//            $p[$k]['url'] = '/catalog/' . $p[$k]['category_alias'] . '/' . $p[$k]['manufacturer_alias'] . '/' . $p[$k]['product_alias'] . '/';
//            $category_parent = '/';
            $category_parent = '';
//            $category_p = Category::find()->select('id_parent')->where(['=', 'alias', $p[$k]['category_alias']])->asArray()->all();
            $cat = $p[$k]['category_alias'];
            do {
                $sql = "
            SELECT `c1`.`alias`, `c1`.`id_parent`
            FROM `tbl_catalog_category` `c1`
            LEFT JOIN `tbl_catalog_category` `c2` ON `c1`.`id` = `c2`.`id_parent` 
            WHERE c2.alias ='" . $cat . "'";

                $command = \Yii::$app->db->createCommand($sql);
                $cat_parent = $command->queryAll();

                if (isset($cat_parent[0]['alias'])) {
                    $category_parent = $cat_parent[0]['alias'] . '/' . $category_parent;
                    $cat = $cat_parent[0]['alias'];
                }
            } while ($cat_parent[0]['id_parent'] > 0);

//            if (in_array($p[$k]['category_alias'], ['chugunnye-vanny', 'stalnye-vanny', 'akrilovye-vanny', 'gidromassaghnye-vanny', 'vanny-iz-iskusstvennogo-mramora', 'kvarilovye-vanny'])) {///////////////////временно
//                $category_parent = '/vanny/';///////////////////временно
//            }


            $p[$k]['url'] = '/' . $category_parent . $p[$k]['category_alias'] . '/' . $p[$k]['product_alias'] . '/';

            unset($p[$k]['id']);
            unset($p[$k]['category_alias']);
            unset($p[$k]['manufacturer_alias']);
            unset($p[$k]['product_alias']);
            unset($p[$k]['category_id']);
        }

        return [
            'products' => $p,
            'categories' => $categories
        ];

    }

    public static function getProductsCount($search, $page, $category)
    {

        if (strlen($search['0']) < 3)
            return 0;

        $itemsPerPage = self::$itemsPerPage;

        // Получить товары
        //AND ce.in_stock > 0
        $sql = '
            SELECT COUNT(*) as count
            FROM `tbl_catalog_category` `cc`
            LEFT JOIN `tbl_catalog_element` `ce` ON cc.id = ce.id_category
            LEFT JOIN `tbl_catalog_manufacturer` `m` ON m.id = ce.id_manufacturer
            WHERE ce.published = 1
            AND ce.price > 0
            ';
        if ($category) {
            $sql .= '
           AND ce.id_category = ' . $category;
        }

        if ($search['1'] != '') {
            $sql .= '
            AND ce.price > 0
            AND (
                CONCAT_WS(" ", cc.title_pre,m.title,ce.title,ce.code_1c) LIKE \'%' . $search['0'] . '%\'
                OR
                CONCAT_WS(" ", cc.title_pre,m.title,ce.title,ce.code_1c) LIKE \'%' . $search['1'] . '%\'
                )';
        } else {
            $sql .= '
            AND ce.price > 0
            AND CONCAT_WS(" ", cc.title_pre,m.title,ce.title,ce.code_1c) LIKE \'%' . $search['0'] . '%\'';
        }

        $command = \Yii::$app->db->createCommand($sql);

        $products = $command->queryOne();

        return $products['count'];

    }

    public static function deleteSpaceInNumber($search)
    {
//        $code = true;
//        $len = strlen($search);
//        for ($i = 0; $i < $len; $i++) {
//            if (!in_array($search[$i], ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'])) {
//                $code = false;
//                break;
//            }
//        }
//        if ($code && substr($search, -3, 3) == '000') {
//            return [substr($search, 0, strlen($search) - 3), $search];
//        } else if ($code && substr($search, -2, 2) == '00') {
//            return [substr($search, 0, strlen($search) - 2), $search];
//        } else if ($code && substr($search, -1, 1) == '0') {
//            return [substr($search, 0, strlen($search) - 1), $search];
//        } else {
//            return [$search];
//        }
        return [$search];
    }
}