<?php
namespace common\modules\catalog\components\category;

use common\modules\catalog\components\Helper;
use Yii;
use yii\db\Query;
use yii\db\Expression;
use yii\helpers\Html;

class CategoryPage extends CategoryAbstract
{


    public function buildProductsGroupYes()
    {
        $this->buildProductsGroup();
    }

    public function buildProductsGroupNo()
    {
        $this->buildProductsGroup();
    }


    public function buildProductsGroup()
    {

        $where = $this->_category->getWhere();
        $category = $this->_category->getCategory();
        $priceStr = $this->_category->getSqlPrice();
        $manufacturerStr = $this->_category->getSqlManufacturer();
        $order = $this->_category->getSqlOrder();
        $offset = $this->_category->getOffset();
        $limit = $this->_category->getLimit();

        // 1. Получить список товаров удовлетворяющих фильтру. В результате будут id самостоятельных товаров или же предков найденных моделей.

        if (count($where) > 0) $whereStr = 'AND (' . implode(' OR ', $where) . ')';
        if (count($where) > 1) $having = "HAVING count(*)='" . count($where) . "'"; else $having = "";
        if ($order != 'hit desc') $and = "ce.price <> 0 AND ce.price IS NOT NULL AND ";


        $sql = "
            SELECT `c2`.`alias`
            FROM `tbl_catalog_category` `c1`
            LEFT JOIN `tbl_catalog_category` `c2` ON `c1`.`id` = `c2`.`id_parent` 
            WHERE `c2`.`parent_filter` IN (1,2) AND c1.alias ='" . $category . "'";

        $command = \Yii::$app->db->createCommand($sql);
        $cat_childs = $command->queryAll();

        if ($cat_childs) {
            $category = '';
            foreach ($cat_childs as $key => $cat_child) {
                $category .= '\',\'' . $cat_childs[$key]['alias'];
            }
        }

//        if ($category == 'vanny') { ////////////////временно
//            $category = ('chugunnye-vanny\',\'stalnye-vanny\',\'akrilovye-vanny\',\'gidromassaghnye-vanny\',\'vanny-iz-iskusstvennogo-mramora\',\'kvarilovye-vanny');
//        }
//        if ($category == 'komplektuyuschie-dlya-vann') { ////////////////временно
//            $category = ('ekrany-dlya-vann\',\'shtorki-na-vanny\',\'plintusy-ugolki-bordyury\',\'karnizy-dlya-vann\',\'podgolovniki\',\'ruchki\',\'noghki-i-karkas\',\'prochee-dlya-vann`');
//        }
//        if ($category == 'bide') { ////////////////временно
//            $category = ('bide-podvesnye\',\'bide-napolnye');
//        }

        //sql WHERE (" . $and . "ce.published=1 AND cc.alias IN ('" . $category . "') AND ce.in_stock > 0 " . $priceStr . $manufacturerStr . $whereStr . ")
        $parentsSql = "
            SELECT *
            FROM (
                SELECT IFNULL(r.id_element_parent, ce.id) as id_main, ce.id, ce.article, ce.guarantee, ce.title as name, ce.title_model as model, ce.alias as `key`, 
                        ce.desc_mini as micro_description,ce.in_stock, ce.desc_full as description, ce.hit as hit, m.title as title_manufacturer, 
                        m.perekup as perekup_manufacturer, m.alias as alias_manufacturer, ccou.ico as ico_country, ccou.title as title_country, ce.price as price, 
                        ce.price_old as price_old, cc.alias as alias_category, cc.id as id_category, ce.id_category_1c, ce.in_status, ce.in_action, ce.in_new, ce.halva
                FROM `tbl_catalog_element` `ce`
                LEFT JOIN `tbl_catalog_category` `cc` ON ce.id_category = cc.id
                LEFT JOIN `tbl_catalog_manufacturer` `m` ON ce.id_manufacturer = m.id
                
                LEFT JOIN `tbl_catalog_manufacturer_country` `cmc` ON cmc.id_manufacturer = m.id
                LEFT JOIN `tbl_catalog_country` `ccou` ON cmc.id_country = ccou.id
                
                LEFT JOIN `tbl_catalog_field_element_value_rel` `cfev` ON cfev.id_element = ce.id
                LEFT JOIN `tbl_catalog_field_value` `cfv` ON cfev.id_value = cfv.id
                LEFT JOIN `tbl_catalog_field` `cf` ON cfev.id_field = cf.id
                LEFT JOIN `tbl_catalog_model_rel` `r` ON r.id_element_children = ce.id
                WHERE (" . $and . "ce.published=1 AND cc.published=1 AND cc.alias IN ('" . $category . "') AND price > 0 " . $priceStr . $manufacturerStr . $whereStr . ")
GROUP BY ce.id
                " . $having . "
            ) t
            GROUP BY id_main
            ORDER BY in_stock=0 ASC, " . $order . "
            LIMIT " . $limit . "
            OFFSET " . $offset;
//        GROUP BY ce.id
//        ORDER BY `r`.`id_element_parent` ASC


        $parentsTotalSql = "
            SELECT COUNT(*) as count
            FROM (
                SELECT COUNT(*)
                FROM (
                    SELECT IFNULL(r.id_element_parent, ce.id) as id_main, ce.id, ce.title as name, ce.title_model as model, ce.alias as `key`,
                            ce.desc_mini as micro_description,ce.in_stock, ce.desc_full as description, m.title as title_manufacturer,
                            m.perekup as perekup_manufacturer, m.alias as alias_manufacturer, ce.price as price, cc.alias as alias_category,
                            cc.id as id_category, ce.id_category_1c
                    FROM `tbl_catalog_element` `ce`
                    LEFT JOIN `tbl_catalog_category` `cc` ON ce.id_category = cc.id
                    LEFT JOIN `tbl_catalog_manufacturer` `m` ON ce.id_manufacturer = m.id
                    LEFT JOIN `tbl_catalog_field_element_value_rel` `cfev` ON cfev.id_element = ce.id
                    LEFT JOIN `tbl_catalog_field_value` `cfv` ON cfev.id_value = cfv.id
                    LEFT JOIN `tbl_catalog_field` `cf` ON cfev.id_field = cf.id
                    LEFT JOIN `tbl_catalog_model_rel` `r` ON r.id_element_children = ce.id
                    WHERE (ce.published=1 AND cc.published=1 AND cc.alias IN ('" . $category . "') AND price > 0 " . $priceStr . $manufacturerStr . $whereStr . ")
GROUP BY ce.id
                    " . $having . "
                ) tt
                GROUP BY id_main
            ) t";
//        GROUP BY ce.id

//        $parents = Yii::$app->db->createCommand($parentsSql, [':alias' => $category])->queryAll(); ////////////////временно
        $parents = Yii::$app->db->createCommand($parentsSql, [])->queryAll();
        $parentsTotal = Yii::$app->db->createCommand($parentsTotalSql, [':alias' => $category])->queryOne()['count'];


//        $file = fopen($_SERVER['DOCUMENT_ROOT'] . "/counter1.txt", "a");//////////////////////////////
//        fwrite($file, date('H:i:s') . '---- $parentsSql ---------- ' . serialize($parentsSql) . PHP_EOL);//////////////////
//        fwrite($file, date('H:i:s') . '---- $parents ---------- ' . serialize($parents) . PHP_EOL);//////////////////
//        fclose($file);//////////////////////////


        // 2. Дети товаров из п.1. если есть.

        $parentsIds = array_column($parents, 'id_main');

        if (count($parentsIds) > 0) {

            //sql WHERE ce.published=1 AND ce.in_stock > 0 AND ce.price > 0  AND cm.id_element_parent IN (' . implode(",", $parentsIds) . ')
            $childrensSql = 'SELECT ce.id, cm.id_element_parent as id_parent, ce.title as name, title_model as model, ce.alias as `key`, 
                  ce.desc_mini as micro_description, ce.in_stock, ce.desc_full as description, ce.title_model as title_model, m.title as title_manufacturer, 
                  m.perekup as perekup_manufacturer, m.alias as alias_manufacturer, 
                  ce.price as price, cc.alias as alias_category, cc.id as id_category, ce.id_category_1c
                FROM `tbl_catalog_element` `ce`
                LEFT JOIN `tbl_catalog_category` `cc` ON cc.id = ce.id_category
                LEFT JOIN `tbl_catalog_manufacturer` `m` ON m.id = ce.id_manufacturer
                LEFT JOIN `tbl_catalog_model_rel` `cm` ON cm.id_element_children = ce.id
                WHERE ce.published=1 AND cc.published=1 AND ce.price > 0  AND cm.id_element_parent IN (' . implode(",", $parentsIds) . ')
                ORDER BY  `ce`.price';
            //ORDER BY  `ce`.sort';

            $childrens = Yii::$app->db->createCommand($childrensSql)->queryAll();

            // Обновленный
            $this->_category->setProducts([
                'parent' => $parents,
                'children' => $childrens,
                'photos' => (new Query())->from('{{%catalog_photo}}')->where([
                    'in',
                    'id_element',
                    array_merge(
                        $parentsIds,
                        array_column($parents, 'id'),
                        array_column($childrens, 'id_parent')
                    )
                ])->andWhere('is_cover = 1')->all()
            ]);
        }


        // Всего товаров
        $resultTmp = $this->_category->getResult();
        $resultTmp['total'] = $parentsTotal;
        $this->_category->setResult($resultTmp);
    }

    public function buildProducts()
    {
        $result = [];
        $products = $this->_category->getProducts();

        $photos = $products['photos'];
        $parents = $products['parent'];
        $childrens = $products['children'];


        // --
        $category_parent = '';
        if (isset($parents[0]['alias_category'])) {
            $cat = $parents[0]['alias_category'];
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
        }
        // -/


        if (is_array($parents)) {

            $rs = (new Query())
                ->select('catalog_element_id, rating')
                ->from('{{%mods_reviews}}')
                ->where(['in', 'catalog_element_id', array_column($parents, 'id')])
                ->all();
            $ratings = [];
            foreach ($rs as $r) {
                $ratings[$r['catalog_element_id']][] = $r['rating'];
            }

            foreach ($parents as $key_product => $parent) {

                // id, name, full_name
                $result[$key_product]['id'] = $parent['id'];
                $result[$key_product]['article'] = $parent['article'];
                $result[$key_product]['name'] = $parent['name'];
                $result[$key_product]['full_name'] = $parent['title_manufacturer'] . ' ' . $parent['name'];
                $result[$key_product]['guarantee'] = $parent['guarantee'];

                $result[$key_product]['title_manufacturer'] = $parent['title_manufacturer'];
                $result[$key_product]['alias_manufacturer'] = $parent['alias_manufacturer'];
                $result[$key_product]['perekup_manufacturer'] = $parent['perekup_manufacturer'];
                $result[$key_product]['ico_country'] = '/statics/web/catalog/country/images/' . $parent['ico_country'];
                $result[$key_product]['title_country'] = $parent['title_country'];

                // description, micro_description
                $result[$key_product]['description'] = Html::decode($parents[$key_product]['description']);
                $result[$key_product]['micro_description'] = Html::decode($parents[$key_product]['micro_description']);

                // url

//                $category_parent = '';
//
//                $cat = $parents[$key_product]['alias_category'];
//                do {
//                    $sql = "
//            SELECT `c1`.`alias`, `c1`.`id_parent`
//            FROM `tbl_catalog_category` `c1`
//            LEFT JOIN `tbl_catalog_category` `c2` ON `c1`.`id` = `c2`.`id_parent`
//            WHERE c2.alias ='" . $cat . "'";
//
//                    $command = \Yii::$app->db->createCommand($sql);
//                    $cat_parent = $command->queryAll();
//
//                    if (is_array($cat_parent)) {
//                        $category_parent = $cat_parent[0]['alias'] . '/' . $category_parent;
//                        $cat = $cat_parent[0]['alias'];
//                    }
//                } while ($cat_parent[0]['id_parent'] > 0);

// //               $result[$key_product]['url'] = '/catalog/' . $parents[$key_product]['alias_category'] . '/' . $parents[$key_product]['alias_manufacturer'] . '/' . $parents[$key_product]['key'] . '/';
//                if (in_array($parents[$key_product]['alias_category'], ['chugunnye-vanny', 'stalnye-vanny', 'akrilovye-vanny', 'gidromassaghnye-vanny', 'vanny-iz-iskusstvennogo-mramora', 'kvarilovye-vanny'])) {///////////////////временно
//                    $category_parent = '/vanny';///////////////////временно
//                }
//                if (in_array($parents[$key_product]['alias_category'], ['ekrany-dlya-vann','shtorki-na-vanny','plintusy-ugolki-bordyury','karnizy-dlya-vann','podgolovniki','ruchki','noghki-i-karkas','prochee-dlya-vann'])) {///////////////////временно
//                    $category_parent = '/vanny/komplektuyuschie-dlya-vann';///////////////////временно
//                }
//                if (in_array($parents[$key_product]['alias_category'], ['unitazy-kompakt', 'unitazy-podvesnye', 'komplekt-unitaz-podvesnoy-installyaciya', 'unitazy-pristavnye', 'napolnye-chashi'])) {///////////////////временно
//                    $category_parent = '/unitazy';///////////////////временно
//                }
//                if (in_array($parents[$key_product]['alias_category'], ['bide-podvesnye', 'bide-napolnye'])) {///////////////////временно
//                    $category_parent = '/bide';///////////////////временно
//                }
//                if (in_array($parents[$key_product]['alias_category'], ['installyacii-dlya-unitazov', 'installyacii-dlya-bide', 'installyacii-dlya-pissuarov', 'installyacii-dlya-rakovin', 'installyacii-dlya-pristennyh-trapov', 'bachki-skrytogo-montagha'])) {///////////////////временно
//                    $category_parent = '/installyacii';///////////////////временно
//                }
//                if (in_array($parents[$key_product]['alias_category'], ['klavishi-smyva','montazhnye-elementy','prochee-dlya-installyacij'])) {///////////////////временно
//                    $category_parent = '/installyacii/komplektuyushchie-dlya-installyacij';///////////////////временно
//                }
//                if (in_array($parents[$key_product]['alias_category'], ['pedestaly', 'polupedestaly', 'komplektuyushchie-dlya-umyvalnikov'])) {///////////////////временно
//                    $category_parent = '/umyvalniki';///////////////////временно
//                }
//                if (in_array($parents[$key_product]['alias_category'], ['moyki-iz-nerghaveyuschey-stali', 'moyki-chugunnye', 'moyki-stalnye-emalirovannye', 'moyki-iz-granitnoy-kroshki'])) {///////////////////временно
//                    $category_parent = '/moyki-kuhonnye';///////////////////временно
//                }
//                if (in_array($parents[$key_product]['alias_category'], ['dushevye-paneli', 'dushevye-garnitury', 'dushevye-stoyki', 'verhnie-dushi'])) {///////////////////временно
//                    $category_parent = '/dushevye-garnitury-i-paneli';///////////////////временно
//                }
//                if (in_array($parents[$key_product]['alias_category'], ['dlya-vanny-i-dusha', 'dlya-umyvalnika', 'dlya-kuhni', 'dlya-bide', 'nakladnye-paneli-i-smesiteli-skrytogo-montagha'])) {///////////////////временно
//                    $category_parent = '/smesiteli-i-komplektuyuschie';///////////////////временно
//                }
//                if (in_array($parents[$key_product]['alias_category'], ['shlangi', 'leyki', 'leyki-dlya-psevdobide', 'izlivy', 'kartridghi-i-zapchasti', 'derzhateli-dlya-leyak', 'dushevye-kronshtejny'])) {///////////////////временно
//                    $category_parent = '/smesiteli-i-komplektuyuschie/komplektuyushchie-dlya-smesitelej';///////////////////временно
//                }
//                if (in_array($parents[$key_product]['alias_category'], ['bokovoe-podklyuchenie', 'nighnee-podklyuchenie'])) {///////////////////временно
//                    $category_parent = '/polotencesushiteli';///////////////////временно
//                }

                $result[$key_product]['url'] = '/' . $category_parent . $parents[$key_product]['alias_category'] . '/' . $parents[$key_product]['key'] . '/';

                // status
                $result[$key_product]['status'] = 'active';

                $result[$key_product]['in_status'] = $parent['in_status'];
                $result[$key_product]['in_action'] = $parent['in_action'];
                $result[$key_product]['in_new'] = $parent['in_new'];
                $result[$key_product]['halva'] = $parent['halva'];
                $result[$key_product]['in_stock'] = $parent['in_stock'];

                // prices
                if ($parents[$key_product]['price'] > 0) {
                    $result[$key_product]['prices']['currency_sign'] = ''; //= 'руб.';
                    $result[$key_product]['prices']['min'] = $parents[$key_product]['price'];
                    $result[$key_product]['prices']['old'] = $parents[$key_product]['price_old'];
                }

                // images, image_size
                if (count($photos) > 0) {
                    foreach ($photos as $photo) {
                        if ($parent['id'] == $photo['id_element']) {
                            $result[$key_product]['images']['header'] = '/statics/catalog/photo/images_small/' . $photo['name'];
                            $result[$key_product]['image_size']['width'] = '';
                            continue;
                        }
                    }
                }

                if (!isset($result[$key_product]['images'])) {
                    $result[$key_product]['images']['header'] = '/statics/catalog/photo/images/_no_photo.jpg';
                    //list($width, $height) = getimagesize("img.jpg");
                    $result[$key_product]['image_size']['width'] = '';
                }

                // children
                $key_parent = $key_product;
                if (count($childrens) > 0) {

                    // Собираем детей
                    $chlds = [];
                    foreach ($childrens as $key_children => $children) {
                        if ($parents[$key_parent]['id_main'] == $children['id_parent']) {
                            $chlds[] = $children;
                        }
                    }

                    foreach ($chlds as $key_children => $children) {

                        $result[$key_parent]['children'][$children['id']]['id'] = $children['id'];
                        $result[$key_parent]['children'][$children['id']]['name'] = ($children['model']) ? $children['name'] . ' ' . $children['model'] : $children['name'];
                        $result[$key_parent]['children'][$children['id']]['full_name'] = $children['alias_manufacturer'] . ' ' . $children['name'] . ' ' . $children['model'];
                        $result[$key_parent]['children'][$children['id']]['description'] = Html::decode($children['title_model']); //($children['title_model'] != '') ? $children['title_model'] : $children['description'];
                        $result[$key_parent]['children'][$children['id']]['url'] = '/catalog/' . $children['alias_category'] . '/' . $children['alias_manufacturer'] . '/' . $children['key'] . '/';
                        $result[$key_parent]['children'][$children['id']]['status'] = 'active';
                        $result[$key_parent]['children'][$children['id']]['in_stock'] = $children['in_stock'];

                        if ($children['price'] > 0) {
                            $result[$key_parent]['children'][$children['id']]['prices']['min'] = $children['price'];
                            $result[$key_parent]['children'][$children['id']]['prices']['currency_sign'] = ''; //= 'руб.';
                        }

                        if (count($photos) > 0) {
                            foreach ($photos as $photo) {
                                if ($children['id'] == $photo['id_element']) {
                                    $result[$key_parent]['children'][$children['id']]['images'] = [
                                        'header' => '/statics/catalog/photo/images/' . $photo['name'],
                                    ];
                                    $result[$key_parent]['children'][$children['id']]['image_size'] = [
                                        'width' => '',
                                    ];
                                    continue;
                                }
                            }
                        }
                        if (!isset($result[$key_parent]['children'][$children['id']]['images'])) {
                            $result[$key_parent]['children'][$children['id']]['images'] = [
                                'header' => '/statics/catalog/photo/images/_no_photo.jpg',
                            ];
                            //list($width, $height) = getimagesize("img.jpg");
                            $result[$key_parent]['children'][$children['id']]['image_size'] = [
                                'width' => '',
                            ];
                        }
                    }
                    if (count($result[$key_parent]['children']) > 0) {
                        $result[$key_parent]['children'] = array_values($result[$key_parent]['children']);
                        // Поиск минимальной цены
//                        $price = $result[$key_parent]['prices']['min'];
                        ($result[$key_parent]['in_stocks'] > 0) ? $price = $result[$key_parent]['prices']['min'] : $price = 0;

                        foreach ($result[$key_parent]['children'] as $c) {
                            if ($c['in_stock'] > 0) {
                                if ($price > $c['prices']['min'] || $price == 0) {
                                    $price = $c['prices']['min'];
                                }
                            }
                        }

                        // Цены из 1234 в 12.34
                        foreach ($result[$key_parent]['children'] as $key_c => $c) {
                            $result[$key_parent]['children'][$key_c]['prices']['min'] = $c['prices']['min'];
                        }
//                        if ($price > 0) {
                            $result[$key_parent]['prices']['after'] = 'от ';
                            $result[$key_parent]['prices']['min'] = $price;
                            $result[$key_parent]['prices']['currency_sign'] = ''; //= 'руб.';
//                        }
                    } else {
                        if ($price = $result[$key_parent]['prices']['min']) {
                            $result[$key_parent]['prices']['min'] = $price;
                        }
                    }
                }

                // rating
                if (array_key_exists($parent['id'], $ratings)) {
                    $count = count($ratings[$parent['id']]);
                    $sum = array_sum($ratings[$parent['id']]);
                    $rating = round($sum / $count, 1) * 10;
                    $result[$key_product]['review']['count'] = $count;
                    $result[$key_product]['review']['rating'] = $rating;
                    $result[$key_product]['review']['html_url'] = '';
                }
            }
        }

        $this->_category->setResult(array_merge($this->_category->getResult(), ['products' => $result]));
    }

    /*
     * Есть заголовки типа 110x50, 32x50, 50x50
     * и это сортируем по первой части
     */
    public static function sortModels($chlds)
    {

        // Берём первую часть заголовков до 'x' и в массивчик
        $firsts = [];
        foreach ($chlds as $chld) {
            $pos = self::sortModelsSubTitle($chld['title_model']);
            if ($pos !== false) {
                $firsts[] = substr($chld['title_model'], 0, $pos - 1);
            }
        }
        // Удиляем дубли и сортируем его
        $firsts = array_unique($firsts);
        sort($firsts);


        // Если оставшаяся часть число, а не 123х123, то тоже в массив
        $check = false;
        $seconds = [];
        foreach ($chlds as $chld) {
            $pos = self::sortModelsSubTitle($chld['title_model']);
            $last = mb_substr($chld['title_model'], $pos + 1);
            if (is_numeric($last)) {
                $seconds[] = $last;
                $check = true;
            }
        }
        // Удиляем дубли и сортируем его
        $seconds = array_unique($seconds);
        sort($seconds);

        // Создаём новый массив где товары упорядочены по полученным предыдущим значениям
        $results = [];
        if ($check) {
            foreach ($firsts as $first) {
                foreach ($seconds as $second) {
                    foreach ($chlds as $chld) {
                        $pos = self::sortModelsSubTitle($chld['title_model']);
                        if ($pos !== false) {
                            $before = mb_substr($chld['title_model'], 0, $pos - 1);
                            $after = mb_substr($chld['title_model'], $pos + 1);
                            if ($before == $first && $after == $second) {
                                $results[] = $chld;
                            }
                        }
                    }
                }
            }
        } else {
            foreach ($firsts as $first) {
                foreach ($chlds as $chld) {
                    $pos = self::sortModelsSubTitle($chld['title_model']);
                    if ($pos !== false) {
                        $before = mb_substr($chld['title_model'], 0, $pos - 1);
                        if ($before == $first) {
                            $results[] = $before;
                        }
                    }
                }
            }
        }


        return $results;
    }

    /*
     * Есть ли в заголовке разделитель х
     * 4 потому что рус и англ маленькие и прописные
     */
    public static function sortModelsSubTitle($title)
    {
        $pos = false;
        if (strpos($title, 'х') !== false) {
            $pos = strpos($title, 'х');
        } else if (strpos($title, 'Х') !== false) {
            $pos = strpos($title, 'Х');
        } else if (strpos($title, 'x') !== false) {
            $pos = strpos($title, 'x');
        } else if (strpos($title, 'X') !== false) {
            $pos = strpos($title, 'X');
        }
        return $pos;
    }

    public static function categoryUrl($category)
    {
        $categoryUrl = (new Query())
            ->select([
                "CONCAT_WS('/', '',cc4.alias, cc3.alias, cc2.alias, cc1.alias, '') as url1",
                "CONCAT_WS('/', '',cc4.alias, cc3.alias, cc2.alias, '') AS url2",
                "CONCAT_WS('/', '',cc4.alias, cc3.alias, '') AS url3",
                "CONCAT_WS('/', '',cc4.alias, '') AS url4",
                'cc1.title as title1','cc2.title as title2','cc3.title as title3','cc4.title as title4',
                "CONCAT_WS('/', '', cc4.alias, cc3.alias, cc2.alias, cc1.alias, '') AS url"])
            ->from('{{%catalog_category}} cc1')
            ->leftJoin('{{%catalog_category}} cc2', 'cc2.id = cc1.id_parent')
            ->leftJoin('{{%catalog_category}} cc3', 'cc3.id = cc2.id_parent')
            ->leftJoin('{{%catalog_category}} cc4', 'cc4.id = cc3.id_parent')
            ->where('cc1.alias=:alias', [':alias' =>  $category])
            ->one();
        return $categoryUrl;
    }

}