<?php
namespace common\modules\catalog\components\export\marketplace;

use Yii;
use \yii\db\Query;
use DOMImplementation;

class Marketplace
{

    private static $nodes = [];

    private static function addNode($list, $node) {
        self::$nodes[$list][] = $node;
    }

    private static function getNodes($list) {
        return ($list) ? self::$nodes[$list] : self::$nodes;
    }

    public static function generateXml() {

        $currencies = [
            'BYN' => '1',
        ];
        $categories = self::getCategories();

        $products = self::getProducts();

        foreach($products as $product) {

            $builder = new Builder();

            $builder->setBuilder(new BuilderOnliner());
            $builder->constructProduct();
            self::addNode('onliner', $builder->getProduct($product));

            $builder->setBuilder(new BuilderYandexOnekby());
            $builder->constructProduct();
            self::addNode('yandex_1k.by', $builder->getProduct($product));

            $builder->setBuilder(new BuilderYandexShopby());
            $builder->constructProduct();
            self::addNode('yandex_shop.by', $builder->getProduct($product));

            $builder->setBuilder(new BuilderYandexUnishopBy());
            $builder->constructProduct();
            self::addNode('yandex_unishop.by', $builder->getProduct($product));

            $builder->setBuilder(new BuilderYandexOnekby());
            $builder->constructProduct();
            self::addNode('yandex_market.yandex.by', $builder->getProduct($product));

        }

        // Отправляем новую информацию онлайнеру
        //self::onlinerUpdate();

        // Генерируем xml
        self::yandexUpdate(
            [
                'yandex_1k.by',
                'yandex_shop.by',
                'yandex_unishop.by',
                'yandex_market.yandex.by'
            ],
            $categories,
            $currencies
        );

    }

    private static function getCategories() {

        $categories = (new Query())
            ->select('DISTINCT(c.id), c.id as id, c.id_parent as id_parent, c.title as title')
            ->from('{{%catalog_element}} e')
            ->leftJoin('{{%catalog_category}} c', 'c.id = e.id_category')
            ->where('e.published = 1') // Опубликован
            ->andWhere('e.price > 0') // С ценой
            ->andWhere('e.is_defect <> 1') // Не брак
            ->orderBy('c.title ASC')
            ->all();

        $categoriesAll = (new Query())
            ->select('DISTINCT(c.id), c.id as id, c.id_parent as id_parent, c.title as title')
            ->from('{{%catalog_category}} c')
            ->orderBy('c.title ASC')
            ->all();

        $results = $categories;
        $resultsIds = array_merge([0],array_column($categories, 'id'));
        $resultsIdsParent = array_column($categories, 'id_parent');

        $ids = array_diff($resultsIdsParent, $resultsIds);

        while (count($ids)) {
            foreach ($categoriesAll as $category) {
                if (in_array($category['id'],$ids)) {
                    $results[] = $category;
                    $resultsIds[] = $category['id'];
                    $resultsIdsParent[] = $category['id_parent'];
                    $ids = array_diff($resultsIdsParent, $resultsIds);
                }
            }
        }

        array_multisort(array_column($results, 'id_parent'), SORT_ASC, $results);

        return $results;
    }

    private static function getProducts() {
        return (new Query())
            ->select('
                e.id,
                e.title,
                e.title_before,
                e.alias,
                e.price,
                e.price_old,
                e.code_1c,
                c.id as category_id,
                c.title as category_title,
                m.alias as manufacturer_alias,
                m.title as manufacturer_title,
                p.name as photo,

                e.`tp_onliner_by_title` as `tp_onliner_by_title`,
                e.`tp_1k_by_title` as `tp_1k_by_title`,
                e.`tp_shop_by_title` as `tp_shop_by_title`,
                e.`tp_unishop_by_title` as `tp_unishop_by_title`,
                e.`tp_market_yandex_by_title` as `tp_market_yandex_by_title`
            ')
            ->from('{{%catalog_element}} e')
            ->leftJoin('{{%catalog_category}} c', 'c.id = e.id_category')
            ->leftJoin('{{%catalog_manufacturer}} m', 'm.id = e.id_manufacturer')
            ->leftJoin('{{%catalog_photo}} p', 'm.id = e.id_manufacturer')
            ->where('e.published = 1') // Опубликован
            ->andWhere('e.price > 0') // С ценой
            ->andWhere('e.is_defect <> 1') // Не брак
            ->groupBy('e.id')
            ->orderBy('p.is_cover DESC')
            ->all();
    }

    private static function onlinerUpdate() {
        $process = curl_init("https://b2bapi.onliner.by/pricelists");
        curl_setopt(
            $process,
            CURLOPT_HTTPHEADER,
            array(
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Bearer RECEIVED_TOKEN_STRING'
            )
        );
        curl_setopt($process, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($process, CURLOPT_POSTFIELDS, json_encode(self::getNodes('onliner')));
        $result = curl_exec($process);
        curl_close($process);
    }

    private static function yandexUpdate($marketplaces, $categories, $currencies) {
        foreach($marketplaces  as $marketplace) {
            $generator = new YmlGeneratorKranikby();
            $generator->run($marketplace, $currencies, $categories, $products = self::getNodes($marketplace));
        }
    }

}