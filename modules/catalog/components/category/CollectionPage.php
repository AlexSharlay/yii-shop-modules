<?php
namespace common\modules\catalog\components\category;

use Yii;
use yii\db\Query;

class CollectionPage extends CategoryAbstract
{

    // Для коллекций группировка не нужна
    public function buildProductsGroupYes() {
        $this->buildProductsGroup();
    }

    public function buildProductsGroupNo() {
        $this->buildProductsGroup();
    }

    public function buildProductsGroup() {

        $where = $this->_category->getWhere();
        $category = $this->_category->getCategory();
        $priceStr = $this->_category->getSqlPrice();
        $manufacturerStr = $this->_category->getSqlManufacturer();
        $order = 'ce.title ASC'; //$this->_category->getSqlOrder();
        $offset = $this->_category->getOffset();
        $limit = $this->_category->getLimit();

        $whereStr = (count($where) > 0) ? 'AND (' . implode(' OR ', $where) . ')' : '';

        // Товаров всего
        $products_count = (new Query())
            ->select(' COUNT(*) count FROM ( SELECT count(distinct(ce.id))')
            ->from('{{%catalog_element}} ce')
            ->leftJoin('{{%catalog_category}} cc', 'ce.id_category = cc.id')
            ->leftJoin('{{%catalog_manufacturer}} m', 'ce.id_manufacturer = m.id')
            ->leftJoin('{{%catalog_field_element_value_rel}} cfev', 'cfev.id_element = ce.id')
            ->leftJoin('{{%catalog_field_value}} cfv', 'cfev.id_value = cfv.id')
            ->leftJoin('{{%catalog_field}} cf', 'cfev.id_field = cf.id');

        if (count($where) > 1) {
            $products_count = $products_count
                ->where('(ce.published=1 AND cc.alias=:alias AND ce.in_stock > 0 ' . $priceStr . $manufacturerStr . $whereStr . ')', [':alias' => $category])
                ->groupBy('ce.id')
                ->having('count(*)=' . count($where) . ' ) as count');
        } else {
            $products_count = $products_count
                ->where('(ce.published=1 AND cc.alias=:alias AND ce.in_stock > 0 ' . $priceStr . $manufacturerStr . $whereStr . ')' . ' GROUP BY ce.id ) as count', [':alias' => $category]);
        }
        $products_count = $products_count->one()['count'];


        // Товары
        $products = (new Query())
            ->select('distinct(ce.id), ce.title as name, ce.alias as alias_collection, cc.alias as alias_category')
            ->from('{{%catalog_element}} ce')
            ->leftJoin('{{%catalog_category}} cc', 'ce.id_category = cc.id')
            ->leftJoin('{{%catalog_manufacturer}} m', 'ce.id_manufacturer = m.id')
            ->leftJoin('{{%catalog_field_element_value_rel}} cfev', 'cfev.id_element = ce.id')
            ->leftJoin('{{%catalog_field_value}} cfv', 'cfev.id_value = cfv.id')
            ->leftJoin('{{%catalog_field}} cf', 'cfev.id_field = cf.id')
            ->where('(ce.published=1 AND cc.alias=:alias AND ce.in_stock > 0 ' . $priceStr . $manufacturerStr . $whereStr . ')', array(':alias' => $category))
            ->groupBy('ce.id');

        $products = (count($where) > 1) ? $products->having('count(*)=' . count($where)) : $products;

        $products = $products
            ->orderBy($order)
            ->offset($offset)
            ->limit($limit)
            //->createCommand()->sql
            ->all();


        foreach($products as $key=>$product) {
            $products[$key]['name'] = $products[$key]['name'].' '.$products[$key]['model'];
        }

        // Всего товаров
        $resultTmp = $this->_category->getResult();
        $resultTmp['total'] = $products_count;
        $this->_category->setResult($resultTmp);

        // Обновленный
        $this->_category->setProducts([
            'parent' => $products,
            'photos' => (new Query())->from('{{%catalog_photo}}')->where(['in', 'id_element', array_column($products, 'id')])->orderBy('sort ASC')->all()
        ]);

    }

    public function buildProducts() {
        $result = [];
        $products = $this->_category->getProducts();

        $photos = $products['photos'];
        $parents = $products['parent'];

        if (is_array($parents)) {
            foreach ($parents as $key_product => $parent) {

                // name, full_name
                $result[$key_product]['name'] =  $parent['name'];
                $result[$key_product]['url'] = '/collections/' . mb_substr( $parents[$key_product]['alias_category'], 0, -11) . '/' . $parents[$key_product]['alias_collection'] . '/';
                $result[$key_product]['status'] = ''; // @todo: на случай если нужны будут метки товару акция, скидка, бесплатная доставка и т.д. можно использовать

                // images, image_size
                if (count($photos) > 0) {
                    $i = 1;
                    foreach ($photos as $photo) {
                        if ($i <= 3 && $parent['id'] == $photo['id_element']) {
                            $result[$key_product]['images'][] = '/statics/catalog/photo/images/' . $photo['name'];
                            $i++;
                        }
                    }
                }

                if (!isset($result[$key_product]['images'])) {
                    $result[$key_product]['images']['0'] = '/statics/catalog/photo/images/_no_photo.jpg';
                }

            }
        }

        $this->_category->setResult(array_merge($this->_category->getResult(),['products' => $result]));
    }

}