<?php
namespace common\modules\catalog\components\category;

use Yii;
use yii\web\HttpException;
use yii\db\Query;
use common\modules\shop\models\frontend\UserDiscount1c;

abstract class CategoryAbstract
{

    /**
     * @var Category $_category
     */
    protected $_category;

    public function createNewCategory()
    {
        $this->_category = new Category();
    }

    public function getCategory()
    {
        return $this->_category->getResult();
    }


    public function buildQuery()
    {
        $query = is_array(Yii::$app->request->get()) ? Yii::$app->request->get() : [];
//        $query = Yii::$app->request->get();
//        $strPathInfo = explode('/', $query['category']);
//        if (count($strPathInfo) == 2) $query['category'] = $strPathInfo[0];
//        if (count($strPathInfo) == 3) $query['category'] = $strPathInfo[1];

        $query = self::addslashes_array($query);
        $this->_category->setQuery($query);
    }

    public function buildCategory()
    {
        $query = $this->_category->getQuery();
        $strPathInfo = explode('/', $query['category']);
        if (count($strPathInfo) == 2) $query['category'] = $strPathInfo[0];
        if (count($strPathInfo) == 3) $query['category'] = $strPathInfo[1];


        if ($category = $query['category']) {
            $this->_category->setCategory($category);
        } else {
            throw new HttpException(404, 'Категория не указана.');
        }
    }

    public function buildManufacturer()
    {
        $query = $this->_category->getQuery();
        $strPathInfo = explode('/', $query['category']);
        if (count($strPathInfo) == 2) $query['category'] = $strPathInfo[0];
        if (count($strPathInfo) == 3) $query['category'] = $strPathInfo[1];

        if ($manufacturers = $query['mfr']) {
            $this->_category->setMfr($manufacturers);
        }
    }

    public function buildPrice()
    {
        $query = $this->_category->getQuery();
        $strPathInfo = explode('/', $query['category']);
        if (count($strPathInfo) == 2) $query['category'] = $strPathInfo[0];
        if (count($strPathInfo) == 3) $query['category'] = $strPathInfo[1];

        if ($price = $query['price']) {
            $this->_category->setPrice($price);
        }
    }

    public function buildGroup()
    {
        $query = $this->_category->getQuery();
        $strPathInfo = explode('/', $query['category']);
        if (count($strPathInfo) == 2) $query['category'] = $strPathInfo[0];
        if (count($strPathInfo) == 3) $query['category'] = $strPathInfo[1];

        if ($group = $query['group']) {
            $this->_category->setGroup($group);
        }
    }


    public function buildPageAndOffset()
    {
        $query = $this->_category->getQuery();
        $strPathInfo = explode('/', $query['category']);
        if (count($strPathInfo) == 2) $query['category'] = $strPathInfo[0];
        if (count($strPathInfo) == 3) $query['category'] = $strPathInfo[1];

        $page = $query['page'];
        if ($page) {
            $this->_category->setPage($page);
            $this->_category->setOffset(($page - 1) * $this->_category->getLimit());
        }
    }

    public function buildSqlOrder()
    {
        $query = $this->_category->getQuery();
        $strPathInfo = explode('/', $query['category']);
        if (count($strPathInfo) == 2) $query['category'] = $strPathInfo[0];
        if (count($strPathInfo) == 3) $query['category'] = $strPathInfo[1];

        $order = $query['order'];
        if (!in_array($order, ['', 'hit:desc', 'price:desc', 'price:asc',])) {
            throw new HttpException(404, 'Недопустимая сортировка.');
        }
        if ($order == '') $order = $this->_category->getSqlOrder();
        $order = explode(':', $order);
        $order = $order['0'] . ' ' . $order['1'];
        $this->_category->setSqlOrder($order);
    }

    public function buildSqlManufacturer()
    {
        $manufacturer = $this->_category->getMfr();
        if (count($manufacturer) > 0) {
            $items = [];
            foreach ($manufacturer as $item) {
                $items[] = '"' . $item . '"';
            }
            $sql = ' AND m.alias IN (' . implode(',', $items) . ') ';
            $this->_category->setSqlManufacturer($sql);
        }
    }

    public function buildSqlPrice()
    {
        $price = $this->_category->getPrice();
        $sql = '';
        if (array_key_exists('from', $price) && array_key_exists('to', $price)) {
            $from = str_pad($price['from'], mb_strlen($price['from']) + 2, "0");
            $to = str_pad($price['to'], mb_strlen($price['to']) + 2, "0");
            $sql = ' AND ce.price BETWEEN ' . $from . ' AND ' . $to . ' ';
        } else if (array_key_exists('from', $price)) {
            $from = str_pad($price['from'], mb_strlen($price['from']) + 2, "0");
            $sql = ' AND ce.price >= ' . $from . ' ';
        } else if (array_key_exists('to', $price)) {
            $to = str_pad($price['to'], mb_strlen($price['to']) + 2, "0");
            $sql = ' AND ce.price <= ' . $to . ' ';
        }
        $this->_category->setSqlPrice($sql);
    }

    public function buildFields()
    {
        $query = $this->_category->getQuery();
        $sql = "
            SELECT `c1`.`alias`, `c1`.`id_parent`, `c2`.`parent_filter`
            FROM `tbl_catalog_category` `c1`
            LEFT JOIN `tbl_catalog_category` `c2` ON `c1`.`id` = `c2`.`id_parent`
            WHERE c2.alias ='" . $query['category'] . "'";
        $command = \Yii::$app->db->createCommand($sql);
        $cat_parent = $command->queryAll();
        if (isset($cat_parent[0]['alias']) AND ($cat_parent[0]['parent_filter'])==1) {
            $query['category'] = $cat_parent[0]['alias'];
        }

        // if ($parent_filter==1) то берём родительскую категорию



        $strPathInfo = explode('/', $query['category']);
        if (count($strPathInfo) == 2) $query['category'] = $strPathInfo[0];
//        if (count($strPathInfo) == 3) $query['category'] = $strPathInfo[1];
        if (count($strPathInfo) == 3) $query['category'] = $strPathInfo[0];

        $fields = (new Query())
            ->select('cf.*, cfg.title as group')
            ->from('{{%catalog_field}} cf')
            ->leftJoin('{{%catalog_field_group}} cfg', 'cf.id_group = cfg.id')
            ->leftJoin('{{%catalog_category}} cc', 'cfg.id_category = cc.id')
//            ->where('cc.alias=:alias', ['alias' => $this->_category->getCategory()]) /////////////////////////
            ->where('cc.alias=:alias', ['alias' => $query['category']])//////////////////////////////////////////////////////////////////
//            ->where('cc.alias=:alias', ['alias' => 'vanny'])//////////////////////////////////////////////////////////////////
            ->all();
        $this->_category->setFields($fields);
    }


    public function buildWhere()
    {

        $fields = $this->_category->getQuery();

        $query = $this->_category->getQuery();
        $strPathInfo = explode('/', $fields['category']);
        if (count($strPathInfo) == 2) $query['category'] = $strPathInfo[0];
        if (count($strPathInfo) == 3) $query['category'] = $strPathInfo[1];

        unset($fields['category']);
        unset($fields['mfr']);
        unset($fields['price']);
        unset($fields['group']);
        unset($fields['page']);
        unset($fields['order']);

        // @todo: Проверка. Все фильтры должны быть в бд.

        $where = [];
        $filters = $this->_category->getFields();
        if (count($fields)) {
            foreach ($fields as $field_key => $field_val) {
                if (is_array($fields[$field_key])) {
                    if (array_key_exists('from', $fields[$field_key]) || array_key_exists('to', $fields[$field_key])) {
                        if (array_key_exists('from', $fields[$field_key]) && array_key_exists('to', $fields[$field_key])) {
                            $where[] = ' ( cf.alias="' . $field_key . '" AND ( cfv.value >= ' . (int)$fields[$field_key]['from'] . ' AND cfv.value <= ' . (int)$fields[$field_key]['to'] . ' ) ) ';
                        } else if (array_key_exists('from', $fields[$field_key])) {
                            $where[] = ' ( cf.alias="' . $field_key . '" AND cfv.value >= ' . (int)$fields[$field_key]['from'] . ' ) ';
                        } else if (array_key_exists('to', $fields[$field_key])) {
                            $where[] = ' ( cf.alias="' . $field_key . '" AND cfv.value <= ' . (int)$fields[$field_key]['to'] . ' ) ';
                        }
                    } elseif (array_key_exists('0', $fields[$field_key])) {
                        unset($fields[$field_key]['operation']);
                        $items = [];
                        $id_field = 0;
                        foreach ($fields[$field_key] as $item) { // $item - алиасы значений поля в запросе
                            foreach ($filters as $filter) {
                                if ($field_key == $filter['alias']) {
                                    $vars = unserialize($filter['variant']);
                                    foreach ($vars as $var) { // $var - варианты значений поля в бд
                                        if ($var['alias'] == $item) {
                                            $items[] = $var['db_val'];
                                        }
                                    }
                                    $id_field = $filter['id'];
                                    continue;
                                }
                            }
                        }
                        if (count($items) > 0) {
                            $where[] = ' ( cfev.id_field="' . $id_field . '" AND cfv.value IN (' . implode(',', $items) . ') ) ';
                        }
                    }
                } else { // Тип 1 with_one
                    foreach ($filters as $filter) {
                        if ($field_key == $filter['alias']) {
                            $dop = unserialize($filter['dop']);
                            if ($dop['check_var'] == 'with_one') {
                                if ($fields[$field_key] == 1) {
                                    $where[] = ' ( cfev.id_field="' . $filter['id'] . '" AND cfv.value = 1 ) ';
                                } else if ($fields[$field_key] == 0) {
                                    $where[] = ' ( cfev.id_field="' . $filter['id'] . '" AND cfv.value = 0 ) ';
                                }
                            }
                            continue;
                        }
                    }

                }
            }
        }
        $this->_category->setWhere($where);
    }

    public function buildProductsGroup()
    {
        ($this->_category->getGroup()) ? static::buildProductsGroupYes() : static::buildProductsGroupNo();
    }

// Скидки для категорий из админки

//    public function buildDiscounts() {
//        $discounts = UserDiscount::getUserDiscount(Yii::$app->user->id);
//        $products = $this->_category->getProducts();
//        if ($products['parent']) {
//            foreach ($products['parent'] as $k => $product) {
//                if (array_key_exists($product['id_category'], $discounts)) {
//                    if ($products['parent'][$k]['price']) {
//                        $products['parent'][$k]['price'] = round($products['parent'][$k]['price'] / 100 * (100 - $discounts[$product['id_category']]), 0);
//                    }
//                }
//            }
//        }
//        if ($products['children']) {
//            foreach ($products['children'] as $k => $product) {
//                if (array_key_exists($product['id_category'], $discounts)) {
//                    if ($products['children'][$k]['price']) {
//                        $products['children'][$k]['price'] = round($products['children'][$k]['price'] / 100 * (100 - $discounts[$product['id_category']]), 0);
//                    }
//                }
//            }
//        }
//
//
//        $file = fopen($_SERVER['DOCUMENT_ROOT']."/counter.txt","a");
//        fwrite($file,date('H:i:s').' '.serialize($discounts).PHP_EOL);
//        fwrite($file,date('H:i:s').' '.serialize($products).PHP_EOL);
//        fclose($file);
//
//
//        $this->_category->setProducts($products);
//    }


// Скидки для категорий из 1С
    public function buildDiscounts()
    {
        $discounts = UserDiscount1c::getUserDiscount1c(Yii::$app->user->id);
        $products = $this->_category->getProducts();

        if ($products['parent']) {
            foreach ($products['parent'] as $k => $product) {
                $arr_id_category_1c = json_decode($product['id_category_1c']);
                if (is_array($arr_id_category_1c)) {
                    foreach ($arr_id_category_1c as $id_category_1c) {
                        if (array_key_exists($id_category_1c, $discounts)) {
                            if ($products['parent'][$k]['price']) {
                                $products['parent'][$k]['price'] = round($products['parent'][$k]['price'] / 100 * (100 - $discounts[$id_category_1c]), 0);
                            }
                        }
                    }
                }

            }
        }
        if ($products['children']) {
            foreach ($products['children'] as $k => $product) {
                $arr_id_category_1c = json_decode($product['id_category_1c']);
                if (is_array($arr_id_category_1c)) {
                    foreach ($arr_id_category_1c as $id_category_1c) {
                        if (array_key_exists($id_category_1c, $discounts)) {
                            if ($products['children'][$k]['price']) {
                                $products['children'][$k]['price'] = round($products['children'][$k]['price'] / 100 * (100 - $discounts[$id_category_1c]), 0);
                            }
                        }
                    }
                }
            }
        }

        $this->_category->setProducts($products);
    }


    public function buildPagination()
    {
        $result = $this->_category->getResult();
        $limit = $this->_category->getLimit();
        $page = $this->_category->getPage();
        $total = $result['total'];

        $result['page']['limit'] = $limit; // Шаг
        $result['page']['current'] = $page; // Номер текущей страницы
        $result['page']['last'] = intval(($total - 1) / $limit) + 1; // Всего страниц
        // Элементов на текущей странице
        if ($result['page']['last'] - $page > 0) {
            $result['page']['items'] = $limit;
        } else {
            $result['page']['items'] = $total - (($result['page']['last'] - 1) * $limit);
        }

        $this->_category->setResult($result);
    }

    abstract function buildProducts();

    public static function addslashes_array($a)
    {
        if (is_array($a)) {
            foreach ($a as $n => $v) {
                $b[$n] = self::addslashes_array($v);
            }
            return $b;
        } else {
            return addslashes($a);
        }
    }

}