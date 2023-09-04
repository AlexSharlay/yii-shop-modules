<?php
namespace common\modules\catalog\components\import\excell;

use common\modules\catalog\models\backend\ComplectRel;
use common\modules\catalog\models\backend\Element;
use common\modules\catalog\models\backend\ModelRel;
use dosamigos\transliterator\TransliteratorHelper;
use Yii;
use  common\modules\catalog\models\backend\KitRel;


class Product
{

    // Определяем тип товара - главный, комплект, модель, сборка
    public static function getProductType($row)
    {
        $str = trim($row['A']);
        if (empty($str)) return ['type' => 'main'];
        if (strpos($str, 'Комплект') !== false) return ['type' => 'complect'];
        if (strpos($str, 'Модель') !== false) return ['type' => 'model'];
        if (strpos($str, 'Сборка') !== false) return ['type' => 'kit', 'i' => array_pop(explode(' ', $str))];
        else {
            Log::instance()->add(['msg' => ['function' => 'getProductType', 'errors' => ['Не удалось определить тип'], 'data' => $row], 'type' => 'error']);
            return false;
        }
    }

    // Выбор режима. Добавление и/или обновление товара.
    public static function product($product, $type, $import_type)
    {

        $id = Element::find()->select('id')->where('code_1c = :code_1c', [':code_1c' => $product['I']])->one()['id'];

        // Посчитать цену из комплектов
        if (in_array($import_type, [1, 2]) && is_null($id)) {
            // @todo: пока подсчёт цены идёт на уровне получения карточки товара
        }


        // Добавлять новые, изменять существующие
        if ($import_type == 1) {
            if (is_null($id)) {
                self::productAdd($product, $type);
            } else {
                self::productUp($product, $type, $id);
            }
        } // Добавлять новые, не изменять существующие
        else if ($import_type == 2 && is_null($id)) {
            self::productAdd($product, $type);
        } // Изменять существующие, не добавлять новые
        else if ($import_type == 3 && !is_null($id)) {
            self::productUp($product, $type, $id);
        }

    }

    // Выбор типа товара для добавления записей о нём в бд
    public static function productAdd($product, $type = [])
    {
        if ($type['type'] == 'main') {
            $id = self::productCreate($product);
            Storage::instance()->setMain($id);
        } else if ($type['type'] == 'complect') {
            $id = self::productCreate($product);
            self::complectRel($id);
        } else if ($type['type'] == 'model') {
            $id = self::productCreate($product, 'model');
            Storage::instance()->setModel($id);
            self::modelRel($id);
        } else if ($type['type'] == 'kit') {
            $id = self::productCreate($product);
            self::kitRel($id, $type['i']);
        }
    }

    public static function productUp($product, $type = [], $id)
    {
        if ($type['type'] == 'main') {
            self::productUpdate($product);
            Storage::instance()->setMain($id);
        } else if ($type['type'] == 'complect') {
            self::productUpdate($product);
            self::complectRel($id);
        } else if ($type['type'] == 'model') {
            self::productUpdate($product, 'model');
            Storage::instance()->setModel($id);
            self::modelRel($id);
        } else if ($type['type'] == 'kit') {
            self::productUpdate($product);
            self::kitRel($id, $type['i']);
        }
    }

    // Добавить товар в бд
    public static function productCreate($product, $type = '')
    {

        // Если существует, вернуть его id
        $id = Element::find()->select('id')->where('code_1c = :code_1c', [':code_1c' => $product['I']])->one()['id'];

        if (isset($id)) {
            return $id;
        } else {
            $p = new Element();
            if (isset($product['AB'])) {
                $p->alias = $product['AB'];
            } else {
                $p->alias = ($product['I']) ? self::slug($product['E'] . '_' . $product['F'] . '_' . $product['I']) : self::slug($product['E'] . '_' . uniqid(3));
            }
            $p->title = (string)$product['E'];
            $p->title_before = (string)$product['D'];
            $p->title_model = (string)$product['F'];
            if ($type == 'model') {
                $p->is_model = 1;
            }
            $p->sort = $product['G'];
            $p->id_category = $product['B'];
            $p->id_manufacturer = $product['manufacturers'][mb_strtolower($product['C'])];
            $p->id_measurement = 3;
//            $p->article         = ((string)$product['H'] != '') ? (string)$product['H'] : (string)$product['I'];
            $p->article = (string)$product['I'];
            $p->code_1c = $product['I'];
            $p->in_stock = $product['J'];
            $p->published = ($product['K']);
            $p->price_1c = $product['L'] * 100;
            $p->price = $product['L'] * 100;

            $p->guarantee = $product['M'];
            $p->life_time = $product['N'];
            $p->info_manufacturer = $product['O'];
            $p->info_importer = $product['P'];
            $p->info_service = $product['Q'];
            $p->tp_onliner_by_url = $product['R'];
            $p->tp_1k_by_url = $product['S'];
            $p->tp_shop_by_url = $product['T'];
            $p->tp_market_yandex_by_url = $product['U'];
            $p->tp_unishop_by_url = $product['V'];

            $p->in_status = $product['W'];
            $p->in_action = $product['X'];
            $p->in_new = $product['Y'];
            $p->halva = $product['Z'];
            $p->price_old = $product['AA'] * 100;
            $p->vendor_code = $product['AC'];

            $p->save();

            if (count($p->errors) > 0) {
                Log::instance()->add(['msg' => ['function' => 'addProduct', 'errors' => $p->errors, 'data' => $product], 'type' => 'error']);
                return false;
            } else {
                $msg = 'Добавлен. Товар: ' . $product['E'] . ', Код 1С: ' . $product['I'];
                Log::instance()->add(['msg' => $msg, 'type' => 'success']);
                return Yii::$app->db->getLastInsertID();
            }
        }
    }

    // Изменить товар в бд
    public static function productUpdate($row, $type = '')
    {
        $arrUpdate = [];

        if ($row['B']) $arrUpdate['id_category'] = $row['B'];
        if ($row['C']) {
            foreach ($row['manufacturers'] as $m => $k) {
                if ($m == mb_strtolower($row['C'])) {
                    $arrUpdate['id_manufacturer'] = $k;
                    break;
                }
            }
        }

        if ($type == 'model') {
            $arrUpdate['is_model'] = 1;
        }

        if ($row['D']) $arrUpdate['title_before'] = $row['D'];
        if ($row['E']) $arrUpdate['title'] = $row['E'];
        if ($row['F']) $arrUpdate['title_model'] = $row['F'];

        if ($row['G']) $arrUpdate['sort'] = $row['G'];

        if ($row['H']) $arrUpdate['article'] = $row['H'];
        if (isset($row['J'])) $arrUpdate['in_stock'] = $row['J'];
        if (isset($row['K'])) $arrUpdate['published'] = $row['K'];
        if ($row['L'] === 0 || $row['L']) {
            $arrUpdate['price_1c'] = ($row['L'] * 1000)/10;
            $arrUpdate['price'] = ($row['L'] * 1000)/10;
        }
//        if ($row['L'] === 0 || $row['L']) {
//            $arrUpdate['price_1c'] = (5.02 * 1000)/10;
//            $arrUpdate['price'] = (5.02 * 1000)/10;
//        }

        if ($row['M']) $arrUpdate['guarantee'] = $row['M'];
        if ($row['N']) $arrUpdate['life_time'] = $row['N'];
        if (isset($row['O'])) $arrUpdate['info_manufacturer'] = $row['O'];
        if ($row['P']) $arrUpdate['info_importer'] = $row['P'];
        if ($row['Q']) $arrUpdate['info_service'] = $row['Q'];
        if ($row['R']) $arrUpdate['tp_onliner_by_url'] = $row['R'];
        if ($row['S']) $arrUpdate['tp_1k_by_url'] = $row['S'];
        if ($row['T']) $arrUpdate['tp_shop_by_url'] = $row['T'];
        if ($row['U']) $arrUpdate['tp_market_yandex_by_url'] = $row['U'];
        if ($row['V']) $arrUpdate['tp_unishop_by_url'] = $row['V'];

        if (isset($row['W'])) $arrUpdate['in_status'] = $row['W'];
        if (isset($row['X'])) $arrUpdate['in_action'] = $row['X'];
        if (isset($row['Y'])) $arrUpdate['in_new'] = $row['Y'];
        if (isset($row['Z'])) $arrUpdate['halva'] = $row['Z'];
        if (isset($row['AA'])) $arrUpdate['price_old'] = $row['AA'] * 100;
        if (isset($row['AB'])) $arrUpdate['alias'] = $row['AB'];
        if (isset($row['AC'])) $arrUpdate['vendor_code'] = $row['AC'];

        
//        echo'<pre>';
//        print_r($row);
//        echo'</pre>';

        if (count($arrUpdate)) {
            Element::updateAll($arrUpdate, 'code_1c = :code_1c', [':code_1c' => $row['I']]);
            $msg = 'Изменён. Товар: ' . $row['E'] . ', Код 1С: ' . $row['I'];
            Log::instance()->add(['msg' => $msg, 'type' => 'info']);
        }
    }



    public static function complectRel($id)
    {
        $id_parent = Storage::instance()->getNow();
        if (!ComplectRel::find()->where('id_element_parent = :id_element_parent AND id_element_children = :id_element_children', [':id_element_parent' => $id_parent, ':id_element_children' => $id])->exists()) {
            $c = new ComplectRel();
            $c->id_element_parent = $id_parent;
            $c->id_element_children = $id;
            $c->save();
            if (count($c->errors) > 0)
                Log::instance()->add(['msg' => ['function' => 'complectRel', 'errors' => $c->errors, 'data' => ['id_element_parent' => $id_parent, 'id_element_children' => $id]], 'type' => 'error']);
        }
    }

    public static function modelRel($id_children)
    {
        $id_parent = Storage::instance()->getMain();

        $id = ModelRel::find()->select('id_element_children')->where('id_element_parent = :id_element_parent AND id_element_children = :id_element_children', [':id_element_parent' => $id_parent, ':id_element_children' => $id_children])->one()['id_element_children'];
        if ($id > 0) {
            return $id;
        } else {
            $c = new ModelRel();
            $c->id_element_parent = $id_parent;
            $c->id_element_children = $id_children;
            $c->save();

            if (count($c->errors) > 0)
                Log::instance()->add(['msg' => ['function' => 'modelRel', 'errors' => $c->errors, 'data' => ['id_element_parent' => $id_parent, 'id_element_children' => $id_children]], 'type' => 'error']);
        }
    }

    public static function kitRel($id, $id_kit)
    {
        $id_parent = Storage::instance()->getNow();
        if (!KitRel::find()->where('id_element_parent = :id_element_parent AND id_element_children = :id_element_children AND id_kit =:id_kit', [':id_element_parent' => $id_parent, ':id_element_children' => $id, ':id_kit' => $id_kit])->exists()) {
            $k = KitRel::AddToKit($id_parent, $id, $id_kit);
            if (count($k->errors) > 0)
                Log::instance()->add(['msg' => ['function' => 'kitRel', 'errors' => $k->errors, 'data' => ''], 'type' => 'error']);
        }
    }


    public static function slugAlias($a, $b)
    {
        $a = self::slug($a);
        $b = self::slug($b);
        $str = '';
        ($a != '') ? $str .= $a : $str .= uniqid();
        $str .= '_';
        ($b != '') ? $str .= $b : $str .= uniqid();
        return $str;
    }

    public static function slug($string)
    {
        return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
    }

}