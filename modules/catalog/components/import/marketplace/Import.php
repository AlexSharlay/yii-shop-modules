<?php
namespace common\modules\catalog\components\import\marketplace;

use common\modules\catalog\models\backend\PhotoInsert;
use Yii;
use yii\db\Query;
use common\modules\catalog\models\backend\Element;

class Import
{

    public static function onliner()
    {
        $i = 0;
        $products = (new Query())->select('id, tp_onliner_by_url')->from('{{%catalog_element}}')->where('tp_onliner_by_url IS NOT NULL')->andWhere('tp_onliner_by_alias IS NULL')->all();

        foreach($products as $product) {

            $html = file_get_contents($product['tp_onliner_by_url']);
            $document = \phpQuery::newDocumentHTML($html);

            $manufacturer = '';
            $descMini = '';

            foreach (pq($document)->find(".b-offers-desc__info-specs p") as $p) {
                if (pq($p)->hasClass('complementary')) {
                    $manufacturer = trim(pq($p)->text());
                    $manufacturer = trim(str_replace("Производитель: ", "", $manufacturer));
                } else {
                    $descMini = trim(pq($p)->text());
                }
            }

            $title = '';
            foreach (pq($document)->find("img#device-header-image") as $h2) {
                $str2 = trim(pq($h2)->attr('title'));
                $title = trim(pq($h2)->attr('alt'));
            }

            $alias = '';
            foreach (pq($document)->find(".b-offers-desc__leave-review.btn-green.btn-small") as $a) {
                $href = trim(pq($a)->attr('href'));
                $href = explode('/',$href);
                $alias = $href[5];

            }

            foreach (pq($document)->find("h2.catalog-masthead__title") as $h2) {
                $str1 = trim(pq($h2)->text());
            }

            $titleBefore = trim(str_replace($str2, "", $str1));

            Element::updateAll(
                [
                    'info_manufacturer' => $manufacturer,
                    'desc_mini' => $descMini,
                    'title' => $title,
                    'alias' => $alias,
                    'title_before' => $titleBefore,
                    'tp_onliner_by_title' => $title,
                    'tp_onliner_by_alias' => $alias,
                ],
                'id = :id',
                [
                    ':id' => $product['id']
                ]
            );

            $i++;

        }

        return $i;
    }

    public static function yandex() {
        $i = 0;
        $products = (new Query())->select('id, tp_market_yandex_by_url')->from('{{%catalog_element}}')->where('tp_market_yandex_by_url IS NOT NULL')->andWhere('tp_market_yandex_by_alias IS NULL')->all();

        foreach($products as $product) {

            $html = file_get_contents($product['tp_market_yandex_by_url']);
            $document = \phpQuery::newDocumentHTML($html);

            $title = trim((pq($document)->find(".col-i h1.big")->text()));
            $alias = trim((pq($document)->find("a.small_link.add_review")->attr('href')));
            $alias = array_pop(explode('/',$alias));
            $alias = array_shift(explode('.html',$alias));

            Element::updateAll(
                [
                    'tp_market_yandex_by_title' => $title,
                    'tp_market_yandex_by_alias' => $alias,
                ],
                'id = :id',
                [
                    ':id' => $product['id']
                ]
            );

            $i++;

        }

        return $i;
    }

    public static function onek() {
        $i = 0;
        $products = (new Query())->select('id, tp_1k_by_url')->from('{{%catalog_element}}')->where('tp_1k_by_url IS NOT NULL')->andWhere('tp_1k_by_alias IS NULL')->all();

        foreach($products as $product) {

            $html = file_get_contents($product['tp_1k_by_url']);

            $document = \phpQuery::newDocumentHTML($html);

            $title = pq($document)->find("span.product_pic img")->attr('title');
            $alias = pq($document)->find("#informproducterror")->attr('data-url');

            $alias = explode('/',$alias);
            $alias = $alias[3];
            $alias = explode('-',$alias);
            $alias = $alias[0];

            if ($title && $alias) {
                Element::updateAll(
                    [
                        'tp_1k_by_title' => $title,
                        'tp_1k_by_alias' => $alias,
                    ],
                    'id = :id',
                    [
                        ':id' => $product['id']
                    ]
                );

                $i++;
            }

        }

        return $i;
    }


    public static function modelPhoto()
    {
        // Получаем коды 1С без картинок
        $products = (new Query())
            ->select('e1.id AS parent, e2.id AS children, p1.name as photo')
            ->from('{{%catalog_element}} e1')
            ->leftJoin('{{%catalog_model_rel}} r', 'r.id_element_parent = e1.id')
            ->leftJoin('{{%catalog_element}} e2', 'r.id_element_children = e2.id')
            ->leftJoin('{{%catalog_photo}} p1', 'p1.id_element = e1.id')
            ->leftJoin('{{%catalog_photo}} p2', 'p2.id_element = e2.id')
            ->where('r.id_element_children IS NOT NULL
                AND p1.id IS NOT NULL
                AND p1.is_cover = 1
                AND p2.id IS NULL')
            //->createCommand()->sql;
            ->all();

        foreach ($products as $product) {

            $name = explode('.', $product['photo']);
            $name = uniqid() . '.' . array_pop($name);

            $file = Yii::getAlias('@statics') . '/web/catalog/photo/images/'.$product['photo'];
            $newfile = Yii::getAlias('@statics') . '/web/catalog/photo/images/'.$name;

            if (!copy($file, $newfile)) {
                echo "Не удалось скопировать $name...<br/>";
            } else {
                $modelPhoto = new PhotoInsert();
                $modelPhoto->id_element = $product['children'];
                $modelPhoto->name = $name;
                $modelPhoto->sort = 1;
                $modelPhoto->is_cover = 1;
                if (!$modelPhoto->save()) {
                    echo "Фото не сохранилось в бд $name...<br/>";
                }
            }
        }

    }

    public static function modelPhotoMain()
    {
        // Получаем коды 1С без картинок
        $products = (new Query())
            ->select('e1.id AS parent, e2.id AS children, p2.name as photo')
            ->from('{{%catalog_element}} e1')
            ->leftJoin('{{%catalog_model_rel}} r', 'r.id_element_parent = e1.id')
            ->leftJoin('{{%catalog_element}} e2', 'r.id_element_children = e2.id')
            ->leftJoin('{{%catalog_photo}} p1', 'p1.id_element = e1.id')
            ->leftJoin('{{%catalog_photo}} p2', 'p2.id_element = e2.id')
            ->where('r.id_element_children IS NOT NULL
                AND p2.id IS NOT NULL
                AND p2.is_cover = 1
                AND p1.id IS NULL')
            ->orderBy('p2.is_cover desc')
            ->groupBy('e1.id')
            //->createCommand()->sql;
            ->all();

        foreach ($products as $product) {

            $name = explode('.', $product['photo']);
            $name = uniqid() . '.' . array_pop($name);

            $file = Yii::getAlias('@statics') . '/web/catalog/photo/images/'.$product['photo'];
            $newfile = Yii::getAlias('@statics') . '/web/catalog/photo/images/'.$name;

            if (!copy($file, $newfile)) {
                echo "Не удалось скопировать $name...<br/>";
            } else {
                $modelPhoto = new PhotoInsert();
                $modelPhoto->id_element = $product['parent'];
                $modelPhoto->name = $name;
                $modelPhoto->sort = 1;
                $modelPhoto->is_cover = 1;
                if (!$modelPhoto->save()) {
                    echo "Фото не сохранилось в бд $name...<br/>";
                }
            }
        }

    }
}