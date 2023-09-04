<?php

namespace common\modules\catalog\models\frontend;

use common\modules\catalog\components\product\BuilderProductJson;
use common\modules\catalog\components\product\ProductBuilder;
use common\modules\catalog\components\category\CategoryPage;
use common\modules\catalog\components\category\CollectionPage;
use common\modules\catalog\components\category\CategoryBuilder;
use common\modules\catalog\components\Product;
use common\modules\catalog\models\Collection;
use common\modules\shop\models\backend\UserCity;
use Yii;
use yii\db\Query;
use yii\web\HttpException;


class Element extends \common\modules\catalog\models\Element
{

    public static function getProductPage($category, $manufacturer = '', $product)
    {
        $item = (new Query())
            ->select('ce.id as id_element, ce.code_1c, ce.title, ce.price, ce.price_old, ce.guarantee, ce.in_status, ce.in_action, ce.in_new, ce.halva, ce.in_stock,
            cc.alias as alias_category, cm.perekup, cm.alias as alias_manufacturer, ce.seo_title, ce.seo_keyword, ce.seo_desc, cm.title as product_manufacturer')
            ->from('{{%catalog_element}} ce')
            ->leftJoin('{{%catalog_category}} cc', 'cc.id = ce.id_category')
            ->leftJoin('{{%catalog_manufacturer}} cm', 'cm.id = ce.id_manufacturer')
            ->where('ce.alias = :alias_product', [':alias_product' => $product])
            ->andWhere('ce.published = 1')
            ->andWhere('cc.published = 1')
            ->andWhere('ce.price > 0')
            ->limit(1)->one();


        if ($item['id_element'] === null) throw new HttpException(404, 'Товар не найден.');
        if ($item['alias_category'] != $category) throw new HttpException(404, 'Категория не найдена.');
//        if ($item['alias_manufacturer'] != $manufacturer) throw new HttpException(404,'Производитель не найден.');

        $userCity = null;
        if (!Yii::$app->user->isGuest) {
            $userCity = UserCity::find()
                ->alias('us')
                ->select('us.day, us.city')
                ->leftJoin('{{%profiles}} p', 'us.id = p.id_city')
                ->where('p.user_id = :user_id', [':user_id' => Yii::$app->user->id])
                ->asArray()
                ->one();
        }


        if (empty($item['seo_title'])) $item['seo_title'] = $item['title']. ' купить оптом в Минске';
        if (empty($item['seo_desc'])) $item['seo_desc'] = $item['title']. ' купить оптом в Минске на самых выгодных условиях от официального дилера! Доставка по всей Беларуси! ☎ +375 (29) 669-59-11';

        return [
            'productId' => $item['id_element'],
            'code' => $item['code_1c'],
            'title' => $item['title'],
            'price' => $item['price'],
            'price_old' => $item['price_old'],
            'in_stock' => $item['in_stock'],
            'guarantee' => $item['guarantee'],
            'status' => $item['in_status'],
            'action' => $item['in_action'],
            'new' => $item['in_new'],
            'halva' => $item['halva'],
            'perekup_manufacturer' => $item['perekup'],
            'brand' => $item['product_manufacturer'],
            'day' => $userCity['day'],
            'city' => $userCity['city'],
            'seo_title' => $item['seo_title'],
            'seo_keyword' => $item['seo_keyword'],
            'seo_desc' => $item['seo_desc']
        ];
    }


    public static function getProduct($id, $id_kit = null)
    {
        $builderProductJson = new BuilderProductJson();
        $builderProductJson->createNewProduct($id, $id_kit);
        $productBuilder = new ProductBuilder();
        $productBuilder->setBuilderProduct($builderProductJson);
        $productBuilder->constructProduct();
        return $productBuilder->getProduct();
    }


    public static function getCategoryProducts()
    {
        $categoryPage = new CategoryPage();
        $categoryPage->createNewCategory();
        $categoryBuilder = new CategoryBuilder();
        $categoryBuilder->setBuilderCategory($categoryPage);
        $categoryBuilder->constructCategory();
        return $categoryBuilder->getCategory();
    }


    public static function getCollectionsProducts()
    {
        $categoryPage = new CollectionPage();
        $categoryPage->createNewCategory();
        $categoryBuilder = new CategoryBuilder();
        $categoryBuilder->setBuilderCategory($categoryPage);
        $categoryBuilder->constructCategory();
        return $categoryBuilder->getCategory();
    }


    public static function getCollection()
    {

        $url = explode('/', Yii::$app->request->url);

        $collection = Element::find()->select('id, title, desc_full as desc')->where('alias = :alias', [':alias' => $url['3']])->asArray()->one();

        $photos = [];
        $vals = Photo::find()->select('name')->where('id_element = :id_element', [':id_element' => $collection['id']])->orderBy('sort ASC')->asArray()->all();
        foreach ($vals as $val) {
            $photos[] = $val['name'];
        }

        $elements = (new Query())
            ->select('e.title_before, e.title, e.alias, p.name as photo, cm.alias as manufacturer, cm.title as manufacturerTitle, cc.alias as category, price')
            ->from('{{%catalog_collection}} c')
            ->leftJoin('{{%catalog_collection_rel}} cr', 'c.id = cr.id_collection')
            ->leftJoin('{{%catalog_element}} e', 'cr.id_element = e.id')
            ->leftJoin('{{%catalog_category}} cc', 'cc.id = e.id_category')
            ->leftJoin('{{%catalog_manufacturer}} cm', 'cm.id = e.id_manufacturer')
            ->leftJoin('{{%catalog_photo}} p', 'e.id = p.id_element')
            ->where('c.alias = :alias', [':alias' => $url['3']])
            ->groupBy('e.id')
            ->orderBy('p.is_cover DESC')
            ->all();

        return [
            'category' => [
                'alias' => $url['2'],
                'title' => Category::find()->select('title')->where('alias = :alias', [':alias' => $url['2']])->one()['title'],
            ],
            'collection' => [
                'id' => $collection['id'],
                'title' => $collection['title_before'] . ' ' . $collection['title'],
                'alias' => $url['3'],
                'desc' => $collection['desc'],
                'photos' => $photos,
            ],
            'elements' => $elements,
        ];
    }


    public static function getActionsProducts()
    {
        $products = (new Query())
            ->select(['ce.id', 'ce.article', 'ce.title as title', 'ce.price', 'ce.price_old', 'ce.desc_mini as desc', 'ce.guarantee',
                'ce.in_status', 'ce.in_action', 'ce.halva',
                'cm.title as brand', 'cm.alias as brandAlias', 'cm.perekup as perekup_brand', 'cc.title as brandCountry', 'cc.ico as brandIco', 'cp.name as productIco',
                "CONCAT_WS('/', '',cc4.alias,cc3.alias, cc2.alias, cc1.alias, ce.alias, '') AS url"])
            ->from('{{%catalog_element}} ce')
            ->leftJoin('{{%catalog_manufacturer}} cm', 'cm.id = ce.id_manufacturer')
            ->leftJoin('{{%catalog_manufacturer_country}} cmc', 'cmc.id_manufacturer = cm.id')
            ->leftJoin('{{%catalog_country}} cc', 'cc.id = cmc.id_country')
            ->leftJoin('{{%catalog_photo}} cp', 'cp.id_element = ce.id')
            ->leftJoin('{{%catalog_category}} cc1', 'cc1.id = ce.id_category')
            ->leftJoin('{{%catalog_category}} cc2', 'cc2.id = cc1.id_parent')
            ->leftJoin('{{%catalog_category}} cc3', 'cc3.id = cc2.id_parent')
            ->leftJoin('{{%catalog_category}} cc4', 'cc4.id = cc3.id_parent')
            ->where('ce.in_action=1')
            ->andWhere('is_cover=1')
            ->andWhere('ce.in_stock>0')
            ->all();
        return $products;
    }


    public static function getNewProducts()
    {
        $products = (new Query())
            ->select(['ce.id', 'ce.article', 'ce.title as title', 'ce.price', 'ce.price_old', 'ce.desc_mini as desc', 'ce.guarantee',
                'ce.in_status', 'ce.in_action', 'ce.halva',
                'cm.title as brand', 'cm.alias as brandAlias', 'cm.perekup as perekup_brand', 'cc.title as brandCountry', 'cc.ico as brandIco', 'cp.name as productIco',
                "CONCAT_WS('/', '',cc4.alias,cc3.alias, cc2.alias, cc1.alias, ce.alias, '') AS url"])
            ->from('{{%catalog_element}} ce')
            ->leftJoin('{{%catalog_manufacturer}} cm', 'cm.id = ce.id_manufacturer')
            ->leftJoin('{{%catalog_manufacturer_country}} cmc', 'cmc.id_manufacturer = cm.id')
            ->leftJoin('{{%catalog_country}} cc', 'cc.id = cmc.id_country')
            ->leftJoin('{{%catalog_photo}} cp', 'cp.id_element = ce.id')
            ->leftJoin('{{%catalog_category}} cc1', 'cc1.id = ce.id_category')
            ->leftJoin('{{%catalog_category}} cc2', 'cc2.id = cc1.id_parent')
            ->leftJoin('{{%catalog_category}} cc3', 'cc3.id = cc2.id_parent')
            ->leftJoin('{{%catalog_category}} cc4', 'cc4.id = cc3.id_parent')
            ->where('ce.in_new=1')
            ->andWhere('is_cover=1')
            ->andWhere('ce.in_stock>0')
            ->all();
        return $products;
    }

    public static function getCollectionProducts($collection)
    {
        $products = Element::find()
            ->select(['ce.id', 'ce.article', 'ce.title as title', 'ce.price', 'ce.price_old', 'ce.in_stock', 'ce.desc_mini as desc', 'ce.guarantee',
                'ce.in_status', 'ce.in_action', 'ce.halva',
                'cm.title as brand', 'cm.alias as brandAlias', 'cm.perekup as perekup_brand', 'cc.title as brandCountry', 'cc.ico as brandIco', 'cp.name as productIco',
                "CONCAT_WS('/', '',cc4.alias,cc3.alias, cc2.alias, cc1.alias, ce.alias, '') AS url"])
            ->from('{{%catalog_element}} ce')
            ->leftJoin('{{%catalog_manufacturer}} cm', 'cm.id = ce.id_manufacturer')
            ->leftJoin('{{%catalog_manufacturer_country}} cmc', 'cmc.id_manufacturer = cm.id')
            ->leftJoin('{{%catalog_country}} cc', 'cc.id = cmc.id_country')
            ->leftJoin('{{%catalog_photo}} cp', 'cp.id_element = ce.id')
            ->leftJoin('{{%catalog_category}} cc1', 'cc1.id = ce.id_category')
            ->leftJoin('{{%catalog_category}} cc2', 'cc2.id = cc1.id_parent')
            ->leftJoin('{{%catalog_category}} cc3', 'cc3.id = cc2.id_parent')
            ->leftJoin('{{%catalog_category}} cc4', 'cc4.id = cc3.id_parent')
            ->leftJoin('{{%catalog_collection_rel}} ccr', 'ccr.id_element = ce.id')
            ->leftJoin('{{%catalog_collection}} c', 'c.id = ccr.id_collection')
            ->where('c.alias=:alias', [':alias' => $collection])
            ->andWhere('ce.published=1')
            ->andWhere('cp.is_cover=1')
            ->andWhere('ce.price > 0')
            ->orderBy('ce.title ASC')
            ->asArray()
            ->all();
        return $products;
    }

    public static function minutToTime($ts)
    {

        $years = floor($ts / 483840);
        if ($years) $ts -= $years * 483840;

        $month = floor($ts / 40320);
        if ($month) $ts -= $month * 40320;

        $week = floor($ts / 10080);
        if ($week) $ts -= $week * 10080;

        $days = floor($ts / 1440);
        if ($days) $ts -= $days * 1440;

        $hour = floor($ts / 60);
        if ($hour) $ts -= $hour * 60;

        $min = floor($ts / 60);

        $str = '';
        if ($years) $str .= self::sklonen($years, 'год', 'года', 'лет', false) . ' ';
        if ($month) $str .= self::sklonen($month, 'месяц', 'месяца', 'месяцев', false) . ' ';
        if ($week) $str .= self::sklonen($week, 'неделя', 'недели', 'недель', false) . ' ';
        if ($days) $str .= self::sklonen($days, 'день', 'дня', 'дней', false) . ' ';
        if ($hour) $str .= self::sklonen($hour, 'час', 'часа', 'часов', false) . ' ';
        if ($min) $str .= self::sklonen($min, 'минута', 'минуты', 'минут', false) . ' ';
        return $str;
    }

    public static function sklonen($n, $s1, $s2, $s3, $b = false)
    {
        $m = $n % 10;
        $j = $n % 100;
        if ($m == 1) $s = $s1;
        if ($m >= 2 && $m <= 4) $s = $s2;
        if ($m == 0 || $m >= 5 || ($j >= 10 && $j <= 20)) $s = $s3;
        if ($b) $n = '<b>' . $n . '</b>';
        return $n . ' ' . $s;
    }

}