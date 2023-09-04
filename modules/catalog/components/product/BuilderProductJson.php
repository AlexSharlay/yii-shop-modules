<?php
namespace common\modules\catalog\components\product;

use common\modules\catalog\models\Category;
use common\modules\catalog\models\frontend\Manufacturer;
use common\modules\mods\mods_reviews\models\Review;
use common\modules\shop\models\frontend\UserDiscount1c;
use Yii;
use yii\db\Query;
//use common\modules\shop\models\frontend\UserDiscount;
use common\modules\shop\models\backend\Delivery;
use yii\web\Cookie;
use common\modules\catalog\components\Helper;

class BuilderProductJson extends BuilderProduct
{

    public function buildInfo()
    {
        $element = (new Query())->select('
                ce.title, ce.title_before, ce.alias, ce.desc_full, ce.vendor_code, ce.info_manufacturer, ce.code_1c, ce.title_model, ce.price, ce.price_old, 
                ce.guarantee, ce.in_stock, ce.id_category_1c, 
                cc.id category_id, cc.title category_title, cc.alias catalog_alias,
                cm.id manufacturer_id, cm.title manufacturer_title, cm.alias manufacturer_alias, cm.ico manufacturer_img,
                cme.id measurement_id, cme.title measurement_title, cme.code measurement_code,
                ccou.title manufacturer_country, ccou.ico country_ico
                ')
            ->where('ce.id=:id', [':id' => $this->_product->getId()])
            ->from('{{%catalog_element}} ce')
            ->leftJoin('{{%catalog_category}} cc', 'cc.id = ce.id_category')
            ->leftJoin('{{%catalog_manufacturer}} cm', 'cm.id = ce.id_manufacturer')
            ->leftJoin('{{%catalog_manufacturer_country}} cmc', 'cmc.id_manufacturer = cm.id')
            ->leftJoin('{{%catalog_country}} ccou', 'ccou.id = cmc.id_country')
            ->leftJoin('{{%catalog_measurement}} cme', 'cme.id = ce.id_measurement')
            ->limit(1)->one();
        if ($element === null) {
            throw new HttpException(404);
        } else {
            $this->_product->setTitle($element['title']);
            $this->_product->setTitleBefore($element['title_before']);
            $this->_product->setAlias($element['alias']);
            $this->_product->setDescFull($element['desc_full']);
            $this->_product->setCode1c($element['code_1c']);
            $this->_product->setVendorCode($element['vendor_code']);
            $this->_product->setInfoManufacturer($element['info_manufacturer']);
            $this->_product->setTitleModel($element['title_model']);
            $this->_product->setPrice($element['price']);
            $this->_product->setPriceOld($element['price_old']);
            $this->_product->setGuarantee($element['guarantee']);
            $this->_product->setInStock($element['in_stock']);
            $this->_product->setCategoryId($element['category_id']);
            $this->_product->setCategoryId($element['id_category_1c']);
            $this->_product->setCategoryTitle($element['category_title']);
            $this->_product->setCategoryAlias($element['catalog_alias']);
            $this->_product->setManufacturerId($element['manufacturer_id']);
            $this->_product->setManufacturerTitle($element['manufacturer_title']);
            $this->_product->setManufacturerAlias($element['manufacturer_alias']);
            $this->_product->setManufacturerCountry($element['manufacturer_country']);
            $this->_product->setManufacturerImg($element['manufacturer_img']);
            $this->_product->setCountryIco($element['country_ico']);
            $this->_product->setMeasurementId($element['measurement_id']);
            $this->_product->setMeasurementTitle($element['measurement_title']);
            $this->_product->setMeasurementCode($element['measurement_code']);
        }
    }

    public function buildPhoto()
    {
        $photos = (new Query())
            ->select('name, is_cover')
            ->from('{{%catalog_photo}}')
            ->where('id_element = :id_element', [':id_element' => $this->_product->getId()])
            ->orderBy('is_cover DESC, sort ASC')
            ->all();

        if ($photos !== null && count($photos)) {
            foreach ($photos as $photo) {
                $size = getimagesize($_SERVER['DOCUMENT_ROOT'] . '/statics/web/catalog/photo/images/' . $photo['name']);
                $this->_product->setPhoto([
                    'name' => '/statics/catalog/photo/images/' . $photo['name'],
                    'name_small' => '/statics/catalog/photo/images_small/' . $photo['name'],
                    'w' => $size['0'],
                    'h' => $size['1'],
                ]);
            }
        } else {
            $size = getimagesize($_SERVER['DOCUMENT_ROOT'] . '/statics/web/catalog/photo/images/_no_photo.jpg');
            $this->_product->setPhoto([
                'name' => '/statics/catalog/photo/images/_no_photo.jpg',
                'w' => $size['0'],
                'h' => $size['1'],
            ]);
        }
    }


    public function buildInstruction()
    {
        $instructions = (new Query())
            ->select('name')
            ->from('{{%catalog_instruction}}')
            ->where('id_element = :id_element', [':id_element' => $this->_product->getId()])
            ->orderBy('sort ASC')
            ->all();
        if ($instructions !== null && count($instructions)) {
            foreach ($instructions as $instruction) {
                $this->_product->setInstruction([
                    'name' => $instruction['name'],
                    'url' => '/statics/web/catalog/media/pdf/' . $instruction['name'],
                ]);
            }
        }
    }

    public function buildModelChildren()
    {
//        ->andWhere('e.in_stock > 0')
        $models = (new Query())
            ->select(['e.*', "CONCAT_WS('/', '', cc4.alias, cc3.alias, cc2.alias, cc1.alias, e.alias, '') AS url", 'cp.name AS photo'])
            ->from('{{%catalog_model_rel}} r')
            ->leftJoin('{{%catalog_element}} e', 'e.id = r.id_element_children')

            ->leftJoin('{{%catalog_category}} cc1', 'cc1.id = e.id_category')
            ->leftJoin('{{%catalog_category}} cc2', 'cc2.id = cc1.id_parent')
            ->leftJoin('{{%catalog_category}} cc3', 'cc3.id = cc2.id_parent')
            ->leftJoin('{{%catalog_category}} cc4', 'cc4.id = cc3.id_parent')
            ->leftJoin('{{%catalog_photo}} cp', 'cp.id_element = e.id')
            ->where('r.id_element_parent = :id', [':id' => $this->_product->getId()])
            ->andWhere('e.published = 1')
            ->andWhere('e.price > 0')
            ->andWhere('cp.is_cover = 1')
            ->all();
        if ($models !== null) {
//        if (!empty($models)) {
            foreach ($models as $model) {
                $this->_product->setModelChildren($model);
            }
        }
    }

    public function buildModelParent()
    {
        // Парент
        $model = (new Query())
            ->select('e.*')
            ->from('{{%catalog_model_rel}} r')
            ->leftJoin('{{%catalog_element}} e', 'e.id = r.id_element_parent')
            ->where('r.id_element_children = :id', [':id' => $this->_product->getId()])
            ->one();
        if ($model !== null) {
            $this->_product->setModelParent($model);
        }
        // Дети
//        ->andWhere('e.in_stock > 0')
        $models = (new Query())
            ->select(['e.*', "CONCAT_WS('/', '',cc4.alias, cc3.alias, cc2.alias, cc1.alias, e.alias, '') AS url", 'cp.name AS photo'])
            ->from('{{%catalog_model_rel}} r')
            ->leftJoin('{{%catalog_model_rel}} r2', 'r2.id_element_parent = r.id_element_parent')
            ->leftJoin('{{%catalog_element}} e', 'e.id = r2.id_element_children')
            ->leftJoin('{{%catalog_category}} cc1', 'cc1.id = e.id_category')
            ->leftJoin('{{%catalog_category}} cc2', 'cc2.id = cc1.id_parent')
            ->leftJoin('{{%catalog_category}} cc3', 'cc3.id = cc2.id_parent')
            ->leftJoin('{{%catalog_category}} cc4', 'cc4.id = cc3.id_parent')
            ->leftJoin('{{%catalog_photo}} cp', 'cp.id_element = e.id')
            ->where('r.id_element_children = :id', [':id' => $this->_product->getId()])
            ->andWhere('e.published = 1')
            ->andWhere('e.price > 0')
            ->andWhere('cp.is_cover = 1')
            ->all();
        if ($models !== null) {
            foreach ($models as $model) {
//                $this->_product->setModelParent($model);//было так,?
                $this->_product->setModelchildren($model);
            }
        }
    }

    public function buildComplectChildren()
    {
        $complects = (new Query())
            ->select(['e.*', "CONCAT_WS('/', '', cc4.alias, cc3.alias, cc2.alias, cc1.alias, e.alias, '') AS url",
                'cp.name AS img', 'cm.title AS title_manufacturer', 'cm.ico AS img_manufacturer', 'ccoun.title AS country_manufacturer', 'ccoun.ico AS country_img'])
            ->from('{{%catalog_complect_rel}} r')
            ->leftJoin('{{%catalog_element}} e', 'e.id = r.id_element_children')
            ->leftJoin('{{%catalog_category}} cc1', 'cc1.id = e.id_category')
            ->leftJoin('{{%catalog_category}} cc2', 'cc2.id = cc1.id_parent')
            ->leftJoin('{{%catalog_category}} cc3', 'cc3.id = cc2.id_parent')
            ->leftJoin('{{%catalog_category}} cc4', 'cc4.id = cc3.id_parent')
            ->leftJoin('{{%catalog_photo}} cp', 'cp.id_element = e.id')
            ->leftJoin('{{%catalog_manufacturer}} cm', 'cm.id = e.id_manufacturer')
            ->leftJoin('{{%catalog_manufacturer_country}} cmc', 'cmc.id_manufacturer = cm.id')
            ->leftJoin('{{%catalog_country}} ccoun', 'ccoun.id = cmc.id_country')
            ->where('r.id_element_parent = :id', [':id' => $this->_product->getId()])
            ->andWhere('e.published = 1')
            ->andWhere('cp.is_cover = 1')
            ->andWhere('e.price > 0')
            ->orderBy(['e.hit' => SORT_DESC])
            ->limit(10)
            ->all();
        if ($complects !== null) {
            foreach ($complects as $complect) {
                $this->_product->setComplectChildren($complect);
            }
        }
    }

    public function buildComplectParent()
    {
        $complects = (new Query())
            ->select(['e.*', "CONCAT_WS('/', '',cc4.alias, cc3.alias, cc2.alias, cc1.alias, e.alias, '') AS url",
                'cp.name AS img', 'cm.title AS title_manufacturer', 'cm.ico AS img_manufacturer', 'ccoun.title AS country_manufacturer', 'ccoun.ico AS country_img'])
            ->from('{{%catalog_complect_rel}} r')
            ->leftJoin('{{%catalog_element}} e', 'e.id = r.id_element_parent')
            ->leftJoin('{{%catalog_category}} cc1', 'cc1.id = e.id_category')
            ->leftJoin('{{%catalog_category}} cc2', 'cc2.id = cc1.id_parent')
            ->leftJoin('{{%catalog_category}} cc3', 'cc3.id = cc2.id_parent')
            ->leftJoin('{{%catalog_category}} cc4', 'cc4.id = cc3.id_parent')
            ->leftJoin('{{%catalog_photo}} cp', 'cp.id_element = e.id')
            ->leftJoin('{{%catalog_manufacturer}} cm', 'cm.id = e.id_manufacturer')
            ->leftJoin('{{%catalog_manufacturer_country}} cmc', 'cmc.id_manufacturer = cm.id')
            ->leftJoin('{{%catalog_country}} ccoun', 'ccoun.id = cmc.id_country')
            ->where('r.id_element_children = :id', [':id' => $this->_product->getId()])
            ->andWhere('e.published = 1')
            ->andWhere('cp.is_cover = 1')
            ->andWhere('e.price > 0')
            ->orderBy(['e.hit' => SORT_DESC])
            ->limit(10)
            ->all();
        if ($complects !== null) {
            foreach ($complects as $complect) {
                $this->_product->setComplectParent($complect);
            }
        }
    }

    public function buildKitChildren()
    {
//        ->andWhere('e.in_stock > 0')
        $kits = (new Query())
            ->select('*')
            ->from('{{%catalog_kit_rel}} r')
            ->leftJoin('{{%catalog_element}} e', 'e.id = r.id_element_children')
            ->where('r.id_element_parent = :id', [':id' => $this->_product->getId()])
            ->andWhere('e.published = 1')
            ->andWhere('e.price > 0')
            ->orderBy('r.id_kit ASC')
            ->all();
        if ($kits !== null) {
            foreach ($kits as $kit) {
                $this->_product->setKitChildren($kit);
            }
        }
    }

    public function buildFields()
    {
        $fields = (new Query())
            ->select('cf.name, cf.description, cf.variant, cf.type, cf.dop, cf.unit, cfg.title as group, cfv.value as v_value, cfv.dop as v_dop, cfv.text as v_text, cfev.id_field')
            ->from('{{%catalog_field_element_value_rel}} cfev')
            ->leftJoin('{{%catalog_field}} cf', 'cfev.id_field = cf.id')
            ->leftJoin('{{%catalog_field_group}} cfg', 'cf.id_group = cfg.id')
            ->leftJoin('{{%catalog_field_value}} cfv', 'cfev.id_value = cfv.id')
            ->where('cfev.id_element=:id_element', ['id_element' => $this->_product->getId()])
            ->orderBy('cfg.sort, cf.sort ASC')
            //->createCommand()->rawSql;
            ->all();
        $this->_product->setFields($fields);
    }

    // Скидка на странице товара

    /*    public function buildDiscounts() {
            $this->_product->setDiscounts(UserDiscount::getUserDiscount(Yii::$app->user->id));
            $this->priceWithDiscount();
            $this->priceWithKitDiscount();
        }*/

    public function buildDiscounts()
    {
        $this->_product->setDiscounts(UserDiscount1c::getUserDiscount1c(Yii::$app->user->id));
        $this->priceWithDiscount();
        $this->priceWithKitDiscount();
    }

    // Скидка на основной товар
    public function priceWithDiscount()
    {
        $arr_id_category_1c = json_decode($this->_product->getcategoryId());
        $price = $this->_product->getPrice();

        if (is_array($arr_id_category_1c)) {
            foreach ($arr_id_category_1c as $id_category_1c) {
                $discount = (new Query())
                    ->select('discount')
                    ->from('{{%shop_user_discount1c}}')
                    ->where('id_user = :id_user AND id_category = :id_category', [':id_user' => Yii::$app->user->id, ':id_category' => $id_category_1c])
                    ->one()['discount'];
                if ($discount > 0) {
                    $newPrice = round($price / 100 * (100 - $discount), 0);
                    $this->_product->setPrice($newPrice);
                }
            }
        }
    }

    public function priceWithKitDiscount()
    {
        $id_kit = $this->_product->getIdKit();
        if ($id_kit) {
            $this->_product->setPrice($this->_product->getPrice() + $this->priceKitWithDiscount());
        }
    }

    public function priceKitWithDiscount()
    {
        $id_kit = $this->_product->getIdKit();
        $id_element_parent = $this->_product->getId();
        $discounts = $this->_product->getDiscounts();

        $items = (new Query)
            ->select('e.price, e.id_category_1c')
            ->from('{{%catalog_kit_rel}} k')
            ->leftJoin('{{%catalog_element}} e', 'k.id_element_children = e.id')
            ->where('k.id_kit = :id_kit', [':id_kit' => $id_kit])
            ->andWhere('k.id_element_parent = :id_element_parent', [':id_element_parent' => $id_element_parent])
            ->all();


        // Применяем скидки если нужно
        $arr_id_category_1c = json_decode($this->_product->getcategoryId());    //массив категорий родительского товара
        if (is_array($arr_id_category_1c)) {
            foreach ($items as $key => $item) {
                foreach ($arr_id_category_1c as $id_category_1c) {
                    if (array_key_exists($id_category_1c, $discounts)) {
                        $items[$key]['price'] = round($items[$key]['price'] / 100 * (100 - $discounts[$id_category_1c]), 0);
                    }
                }

            }
        }

        $price = array_sum(array_column($items, 'price'));
        $this->_product->setPrice($price);
    }

    // Доставка с учётом скидок

    public
    function buildDelivery()
    {
        $delivery = null;
        $site = Yii::$app->params['site'];
        // Варианты доставки и оплат для этой цены
        if ($site == 'opt') {
            $delivery = Delivery::find()
                ->joinWith('deliveryPayment')
                ->joinWith('deliveryPayment.payment')
                ->andWhere('{{%shop_delivery}}.price_from <= :price AND {{%shop_delivery}}.price_to >= :price', [':price' => $this->_product->getPrice()])
                ->asArray()
                ->all();
        } else if ($site == 'retail') {

        }
        $this->_product->setDelivery($delivery);
    }

    // Результат, полные данные о продукте $productFull

    public
    function getProductJson()
    {
        //скидки
        $discounts = $this->_product->getDiscounts();

        $result = [
            'id' => $this->_product->getId(),
            'title' => $this->_product->getTitle(),
            'title_before' => $this->_product->getTitleBefore(),
            'title_model' => $this->_product->getTitleModel(),
            'alias' => $this->_product->getAlias(),
            'description' => $this->_product->getDescFull(),
            'article' => $this->_product->getCode1c(),
            'vendor_code' => $this->_product->getVendorCode(),
            'info_manufacturer' => $this->_product->getInfoManufacturer(),
            'price' => ($this->_product->getPrice() == 0) ? null : $this->_product->getPrice(),
            'price_old' => $this->_product->getPriceOld(),
            'price_main' => ($this->_product->getPrice() == 0) ? null : $this->_product->getPrice(),
            'guarantee' => $this->_product->getGuarantee(),
            'category' => [
                'id' => $this->_product->getCategoryId(),
                'alias' => $this->_product->getCategoryAlias(),
                'title' => $this->_product->getCategoryTitle(),
            ],
            'manufacturer' => [
                'id' => $this->_product->getManufacturerId(),
                'alias' => $this->_product->getManufacturerAlias(),
                'url' => '/manufacturer/' . $this->_product->getManufacturerAlias() . '/',
                'title' => $this->_product->getManufacturerTitle(),
                'country' => $this->_product->getManufacturerCountry(),
                'img' => $this->_product->getManufacturerImg(),
                'ico' => $this->_product->getCountryIco(),
            ],
            'measurement' => [
                'id' => $this->_product->getMeasurementId(),
                'code' => $this->_product->getMeasurementCode(),
                'title' => $this->_product->getMeasurementTitle(),
            ],
//            'in_stock' => 99999, //$product['in_stock'], Сказали скрыть
            'in_stock' => $this->_product->getInStock(),
            'photos' => $this->_product->getPhoto(),
            'instructions' => $this->_product->getInstruction(),
            'models' => [],
            'complects' => [],
            'delivery' => [],
            'kits' => [],
            'reviews' => [],
            'rating' => [],
        ];

        /*
         * Модели
         */

        // Если товар главный, а все остальные модели
        if (count($this->_product->getModelChildren())) {
            // Главный товар к которому назначаем модели
//            $result['models'][] = [
//                'id' => $this->_product->getId(),
//                'title' => $this->_product->getTitle(),
//                'isActive' => 1,
//            ];
            // Модели
            $products = $this->_product->getModelChildren();
            foreach ($products as $product) {
//                if ($product['in_stock'] > 0 && $product['published'] != 0) {
                if ($product['published'] != 0) {
                    $result['models'][] = [
                        'id' => $product['id'],
//                        'title' => ($this->_product->getId() == $product['id']) ? $product['title'] : $product['title_model'],
                        'title_name' => $product['title'],
                        'article' => $product['article'],
                        'title' => $product['title_model'],
                        'price' => $product['price'],
                        'price_old' => $product['price_old'],
                        'in_stock' => $product['in_stock'],
                        'url' => $product['url'],
                        'photo' => $product['photo'],
                        'isActive' => ($this->_product->getId() == $product['id']) ? 1 : 0,
                    ];
                }
            }
        } // Иначе вывести все модели родительского товара
        else if (count($this->_product->getModelParent())) {
            $products = $this->_product->getModelParent();
            foreach ($products as $product) {
//                if ($product['in_stock'] > 0 && $product['published'] != 0) {
//                if ($product['price'] > 0 && $product['published'] != 0) {
                if ($product['published'] != 0) {
                    $result['models'][] = [
                        'id' => $product['id'],
//                        'title' => ($product['title_model'] == '') ? $product['title'] : $product['title_model'],
                        'title' => $product['title_model'],
                        'url' => $product['url'],
                        'isActive' => ($this->_product->getId() == $product['id']) ? 1 : 0,
                    ];
                }
            }
        }



        /*
         * Комплекты
         */

        // Если комплектующие в товаре не в наличии или не опубликованы, то price комплекта = 0
        $price = true; //Непонятно?
        // Если Комплект главный, а все остальные модели ????
        // @todo: Не проверял, т.к. скорее всего этот функционал не будем использовать


        if (count($this->_product->getComplectChildren()) > 0) {
            $products = $this->_product->getComplectChildren();

            foreach ($products as $product) {
//                if ($product['in_stock'] == 0 || $product['published'] == 0) {//Непонятно?
//                    $price = false;//Непонятно?
//                }//Непонятно?
                $result['complects'][] = [
                    'id' => $product['id'],
                    'title' => $product['title'],
                    'title_before' => ($product['title_before']),
                    'vendor_code' => $product['vendor_code'],
                    'info_manufacturer' => $product['info_manufacturer'],
                    'code_1c' => $product['code_1c'],
                    'price' => $product['price_1c'],
                    'price_old' => $product['price_old'],
                    'in_stock' => $product['in_stock'],
                    'hit' => $product['hit'],
                    'in_status' => $product['in_status'],
                    'in_action' => $product['in_action'],
                    'in_new' => $product['in_new'],
                    'halva' => $product['halva'],
                    'img' => $product['img'],
                    'title_manufacturer' => $product['title_manufacturer'],
                    'img_manufacturer' => $product['img_manufacturer'],
                    'country_manufacturer' => $product['country_manufacturer'],
                    'country_img' => $product['country_img'],
                    'isActive' => ($this->_product->getId() == $product['id']) ? 1 : 0,
                    'url' => $product['url'],
                ];
            }
        } else if (count($this->_product->getComplectParent()) > 0) {
            $products = $this->_product->getComplectParent();

            foreach ($products as $product) {
                $result['complects'][] = [
                    'id' => $product['id'],
                    'title' => $product['title'],
                    'vendor_code' => $product['vendor_code'],
                    'info_manufacturer' => $product['info_manufacturer'],
                    'code_1c' => $product['code_1c'],
                    'price' => $product['price_1c'],
                    'price_old' => $product['price_old'],
                    'in_stock' => $product['in_stock'],
                    'hit' => $product['hit'],
                    'in_status' => $product['in_status'],
                    'in_action' => $product['in_action'],
                    'in_new' => $product['in_new'],
                    'halva' => $product['halva'],
                    'img' => $product['img'],
                    'title_manufacturer' => $product['title_manufacturer'],
                    'img_manufacturer' => $product['img_manufacturer'],
                    'country_manufacturer' => $product['country_manufacturer'],
                    'country_img' => $product['country_img'],
                    'url' => $product['url'],
                    //'isActive' => ($id==$element['id']) ? 1 : 0,
                ];
            }
        }
//        if ($price === false) $result['price'] = 0;//Непонятно?


        /*
         * Сборки
         */

        if (count($this->_product->getKitChildren()) > 0) {
            //Добавим пустышку
            $result['kits']['0'] = [
                'id_product' => $this->_product->getId(),
                'id_kit' => '0',
                'isActive' => ($this->_product->getIdKit()) ? 0 : 1,
                'price_sum' => $result['price_main'],
            ];

            //И реальные сборки
            $products = $this->_product->getKitChildren();
            $arr_id_category_1c = json_decode($this->_product->getcategoryId());    //массив категорий родительского товара

            foreach ($products as $product) {
                $id_kit_orig = $product['id_kit'];
                $result['kits'][$id_kit_orig]['id_product'] = $this->_product->getId();
                $result['kits'][$id_kit_orig]['id_kit'] = $id_kit_orig;
                $result['kits'][$id_kit_orig]['isActive'] = ($this->_product->getIdKit() == $id_kit_orig) ? 1 : 0;
                if ($result['kits'][$id_kit_orig]['price_sum'] == 0)
                    $result['kits'][$id_kit_orig]['price_sum'] = $result['price'];

                $result['kits'][$id_kit_orig]['item'][] = [
                    'id' => $product['id'],
                    'code' => $product['code_1c'],
                    'title' => $product['title'],
                    'vendor_code' => $product['vendor_code'],
                    'price' => $product['price'],
                    'price_old' => $product['price_old'],
                    'in_stock' => $product['in_stock'],
                ];


                // Применяем скидки если нужно
                $priceWithDiscount = $product['price'];

                if (is_array($arr_id_category_1c)) {
                    foreach ($arr_id_category_1c as $id_category_1c) {
                        if (array_key_exists($id_category_1c, $discounts)) {
                            $priceWithDiscount = round($priceWithDiscount / 100 * (100 - $discounts[$id_category_1c]), 0);
                        }
                    }
                }

                $result['kits'][$id_kit_orig]['price'] += $priceWithDiscount;
                $result['kits'][$id_kit_orig]['price_sum'] += $priceWithDiscount;
                if ($this->_product->getIdKit() == $id_kit_orig) {
                    $result['price'] += $priceWithDiscount;
                }

            }
            // Сброс индексов
            $result['kits'] = array_values($result['kits']);
        }

        //$result['price'] = number_format($result['price'], 0, ',', ' ');

        /*
         * Доставка и оплата
         */

        $site = Yii::$app->params['site'];
        if ($site == 'opt') {

        } else if ($site == 'retail') {
            if ($result['price'] > 0) {
                $deliveryPayments = $this->_product->getDelivery();
                foreach ($deliveryPayments as $dp) {
                    $payments = [];
                    foreach ($dp['deliveryPayment'] as $p) {
                        $payments[] = [
                            'title' => $p['payment']['title'],
                            'desc' => $p['payment']['desc']
                        ];
                    }
                    if (count($payments) > 0) {
                        $result['delivery'][] = [
                            'title' => $dp['title'],
                            'desc' => $dp['desc'],
                            'price' => ($dp['price'] == 0) ? 'бесплатно' : $dp['price'],
                            'payments' => $payments,
                        ];
                    }
                }
            }
        }

        /*
         * Поля
         */

        $fieldsNew = [];
        $fields = $this->_product->getFields();
        foreach ($fields as $key => $field) {
            if (is_null($fieldsNew[$field['group']])) {
                $fieldsNew[$field['group']] = [];
            }
            if (!array_key_exists($field['name'], $fieldsNew[$field['group']])) {
                $fieldsNew[$field['group']][$field['name']] = [
                    'description' => $field['description'],
                    'variant' => $field['variant'],
                    'type' => $field['type'],
                    'dop' => $field['dop'],
                    'unit' => $field['unit']
                ];
            }
            $fieldsNew[$field['group']][$field['name']]['vars'][] = [
                'value' => $field['v_value'],
                'dop' => $field['v_dop'],
                'text' => $field['v_text'],
            ];
        }
        foreach ($fieldsNew as $k_group => $group) {
            foreach ($group as $k_field => $field) {

                $type = $field['type'];
                $vars = unserialize($field['variant']);
                $dop = unserialize($field['dop']);

                if ($type == '1') {
                    if (isset($dop['check_var']) && $dop['check_var'] == 'kb') {
                        foreach ($field['vars'] as $var) {
                            if ($var['value'] >= 1048575) {
                                $fieldsNew[$k_group][$k_field]['result'] = ((float)$var['value'] / 1024 / 1024) . ' Гб ' . $var['text'];
                            } else if ($var['value'] >= 1024) {
                                $fieldsNew[$k_group][$k_field]['result'] = ((float)$var['value'] / 1024) . ' Мб ' . $var['text'];
                            } else {
                                $fieldsNew[$k_group][$k_field]['result'] = ((float)$var['value']) . ' Кб ' . $var['text'];
                            }
                        }
                    } else if (isset($dop['check_var']) && $dop['check_var'] == 'mass') {
                        foreach ($field['vars'] as $var) {
                            if ($var['value'] >= 1000000) {
                                $fieldsNew[$k_group][$k_field]['result'] = ((float)$var['value'] / 1000000) . ' т. ' . $var['text'];
                            } else if ($var['value'] >= 1000) {
                                $fieldsNew[$k_group][$k_field]['result'] = ((float)$var['value'] / 1000) . ' кг ' . $var['text'];
                            } else {
                                $fieldsNew[$k_group][$k_field]['result'] = ((float)$var['value']) . ' гр ' . $var['text'];
                            }
                        }
                    } else if (isset($dop['check_var']) && $dop['check_var'] == 'time') {
                        $time_i = 1;
                        $time = '';
                        foreach ($field['vars'] as $var) {
                            if ($time_i == 2 && $var['value'] > 0) {
                                $time .= ' - ';
                            }
                            $time .= self::minutToTime((float)$var['value']);
                            $time_i++;
                        }
                    } else if (isset($dop['check_var']) && $dop['check_var'] == 'with_one') {
                        $res = [];
                        $res1 = '';
                        foreach ($field['vars'] as $f_k => $f_v) {
                            if ($f_v['value'] == 0 && $f_v['dop'] == 1) {
                                $res1 = '<span class="icon-minus3"></span> ';
                                unset($fieldsNew[$k_group][$k_field][$f_k]);
                            } else if ($f_v['value'] == 1 && $f_v['dop'] == 1) {
                                $res1 = '<span class="icon-checkmark3"></span> ';
                                unset($fieldsNew[$k_group][$k_field][$f_k]);
                            }
                        }
                        if ($res1 != '') $res[] = $res1;

                        // Поидее не может быть более 1 значения, ну да ладно всуну по аналогии с тип 3
                        $res2 = [];
                        if (is_array($field['vars'])) {
                            foreach ($field['vars'] as $var) {
                                if ($var['dop'] != 1) {
                                    $res2[] = (float)$var['value'];
                                }
                            }
                        }
                        if (is_array($res2) && count($res2) > 0) $res[] = implode(', ', $res2);

                        //
                        $res3 = [];
                        if ($field['unit'] != '' && count($res2)) {
                            $res3[] = $field['unit'];
                        }
                        if ($field['text']) {
                            $res3[] = $field['text'];
                        }
                        if (is_array($res3) && count($res3) > 0) $res[] = implode(' ', $res3);

                        $fieldsNew[$k_group][$k_field]['result'] = implode(' ', $res);
                    } else if (isset($dop['check_var']) && $dop['check_var'] == 'default') {
                        $res = [];

                        // Поидее не может быть более 1 значения, ну да ладно всуну по аналогии с тип 3
                        $res2 = [];
                        if (is_array($field['vars'])) {
                            foreach ($field['vars'] as $var) {
                                $res2[] = (float)$var['value'];
                            }
                        }
                        if (is_array($res2) && count($res2) > 0) $res[] = implode(',', $res2);

                        //
                        $res3 = [];
                        if ($field['unit'] != '') {
                            $res3[] = $field['unit'];
                        }
                        if ($field['text']) {
                            $res3[] = $field['text'];
                        }
                        if (is_array($res3) && count($res3) > 0) $res[] = implode(' ', $res3);

                        $fieldsNew[$k_group][$k_field]['result'] = implode(' ', $res);
                    } else {
                        $f = fopen($_SERVER['DOCUMENT_ROOT'] . "/log_err.txt", 'a');
//                        fwrite($f, 'Product.php. Поле: ' . $f['title'] . '. Тип 1. Не указан check_var.' . PHP_EOL);
                        fwrite($f, date('d.m.Y H:i:s') . ' - ' . 'Product.php. Поле: ' . $f['title'] . '. Тип 1. Не указан check_var.' . PHP_EOL);
                        fclose($f);
                    }

                } else if ($type == '3') {
                    // Сначало, если есть, да/нет
                    $res = [];
                    $res1 = '';
                    foreach ($field['vars'] as $f_k => $f_v) {
                        if ($f_v['value'] == 0 && $f_v['dop'] == 1) {
                            $res1 = '<span class="icon-minus3"></span> ';
                            unset($fieldsNew[$k_group][$k_field][$f_k]);
                        } else if ($f_v['value'] == 1 && $f_v['dop'] == 1) {
                            $res1 = '<span class="icon-checkmark3"></span> ';
                            unset($fieldsNew[$k_group][$k_field][$f_k]);
                        }
                    }
                    if ($res1 != '') $res[] = $res1;

                    // Теперь перечислим варианты, либо один вариант
                    $res2 = [];
                    // Теперь перечислим варианты, либо один вариант
                    if (count($field['vars'] > 0)) {
                        foreach ($field['vars'] as $value) {
                            foreach ($vars as $variant) {
                                if ((int)$value['value'] == (int)$variant['db_val']) {
                                    $res2[] = $variant['title'];
                                }
                            }
                        }
                        // Копии и значения в тексте не нужны, т.к. на стадии парса разобрали
                    }
                    if (is_array($res2) && count($res2) > 0) $res[] = implode(', ', $res2);

                    //
                    $res3 = [];
                    if ($field['unit'] != '') {
                        $res3[] = $field['unit'];
                    }
                    if ($field['vars']['0']['text']) {
                        $res3[] = $field['vars']['0']['text'];
                    }
                    if (is_array($res3) && count($res3) > 0) $res[] = implode(' ', $res3);

                    $fieldsNew[$k_group][$k_field]['result'] = implode(' ', $res);
                } else if ($type == '5') {
                    $fieldsNew[$k_group][$k_field]['result'] = $fieldsNew[$k_group][$k_field]['vars']['0']['text'];
                } else if ($type == '6') {
                    $razdelitel = $dop['razdelitel'];
                    if (isset($field['vars'][0]['value']) && isset($field['vars'][1]['value'])) {
                        $res[] = (float)$field['vars'][0]['value'] . " " . $razdelitel . " " . (float)$field['vars'][1]['value'];
                    }
                    if ($field['unit'] != '') {
                        $res[] = $field['unit'];
                    }
                    if ($field['vars']['0']['text']) {
                        $res[] = $field['vars']['0']['text'];
                    }
                    $fieldsNew[$k_group][$k_field]['result'] = implode(' ', $res);
                } else {
                    $f = fopen($_SERVER['DOCUMENT_ROOT'] . "/log_err.txt", 'a');
                    fwrite($f, 'Product.php. Ошибка типа: ' . serialize($field) . PHP_EOL);
                    fclose($f);
                }
            }
        }
        foreach ($fieldsNew as $k_group => $group) {
            foreach ($group as $k_field => $field) {
                unset($fieldsNew[$k_group][$k_field]['variant']);
                unset($fieldsNew[$k_group][$k_field]['type']);
                unset($fieldsNew[$k_group][$k_field]['dop']);
                unset($fieldsNew[$k_group][$k_field]['unit']);
                unset($fieldsNew[$k_group][$k_field]['vars']);
            }
        }
        $groups = [];
        // @todo: работает, но сделать нормально. Сейчас нет времени.
        foreach ($fieldsNew as $k_group => $group) {
            $groups[] = [
                'title' => $k_group,
                'fields' => $fieldsNew[$k_group],
            ];
        }
        $groups = array_values($groups);
        foreach ($groups as $k_group => $group) {
            foreach ($group['fields'] as $k_field => $field) {
                $groups[$k_group]['fields'][$k_field]['title'] = $k_field;
            }
        }
        foreach ($groups as $k_group => $group) {
            $groups[$k_group]['fields'] = array_values($groups[$k_group]['fields']);
        }

        $result['groups'] = $groups;

        /*
         * Отзывы
         */

        $result['reviews'] = (new Query())
            ->select('r.id, r.title, r.text, r.advantage, r.disadvantages, r.created_at, r.rating, p.name')
            ->from('{{%mods_reviews}} r')
            ->leftJoin('{{%profiles}} p', 'r.user_id = p.user_id')
            ->leftJoin('{{%catalog_element}} ce', 'r.catalog_element_id = ce.id')
            ->where('ce.id = :id', [':id' => $this->_product->getId()])
            ->orderBy('r.id DESC')
            ->all();

        /*
         * Оценка
         */

        $ratings = Review::find()->select('rating')->where('catalog_element_id = :id', [':id' => $this->_product->getId()])->asArray()->all();
        if (count($ratings)) {
            $ratingSum = 0;
            foreach ($ratings as $rating) {
                $ratingSum += $rating['rating'];
            }
            $result['rating'] = [
                'ratingCount' => count($ratings),
                'ratingValue' => $ratingSum / count($ratings),
                'bestRating' => 5,
            ];
        }


        return $result;
    }

    // Счётчик просмотров +1

    public
    function counter()
    {
        $alias = $this->_product->getAlias();
        $cookieName = 'get-product';
        $shouldCount = false;
        $views = Yii::$app->request->cookies->getValue($cookieName);

        if ($views !== null) {
            if (is_array($views)) {
                if (!in_array($alias, $views)) {
                    $views[] = $alias;
                    $shouldCount = true;
                }
            } else {
                $views = [$alias];
                $shouldCount = true;
            }
        } else {
            $views = [$alias];
            $shouldCount = true;
        }

        if ($shouldCount === true) {
            Yii::$app->db->createCommand('UPDATE tbl_catalog_element SET hit = hit + 1 WHERE alias = :alias')->bindValue(':alias', $alias)->execute();
            Yii::$app->response->cookies->add(new Cookie([
                'name' => $cookieName,
                'value' => $views,
                'expire' => time() + 86400 * 365
            ]));
        }
    }
}