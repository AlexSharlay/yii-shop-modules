<?php
namespace common\modules\catalog\components\import\excell;

use common\modules\catalog\models\backend\Category;
use common\modules\catalog\models\frontend\Element;
use common\modules\catalog\models\Manufacturer;
use yii\web\HttpException;

class ImportExcell
{

    public function run($file, $import_type)
    {

        // Данные xlsx файла
        $objPHPExcel = \PHPExcel_IOFactory::load($file);
        $rows = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

//        if ($rows[1]['B'] = 'Id категории'){
//            unset($rows[1]);
//        }


        //
        $categories = self::getAllCategories();
        $manufacturers = self::getAllManufacturers();
        //$codes = self::getAllCodes(); - возможно в будущем оптимизировать

        // Все производители
        $m = [];
        foreach($manufacturers as $key=>$manufacturer) {
            $m[$manufacturer] = $key;
        }


        if (in_array($import_type,[1,2]))
        {
            // Создаю массив где каждый элемент это массив главного и дочерних ему
            $arr = [];
            $i = 0;
            $check = 0;

            foreach ($rows as $row) {
                $type =  Product::getProductType($row);
                if ($type['type'] == 'main') $check++;
                if ($type['type'] == 'main' && $check != 1) $i++;
                $arr[$i][] = $row;
            }

            unset($arr['0']);
            //
            foreach($arr as $rows) {

                Storage::instance()->clear();

                foreach($rows as $key=>$row) {

                    // Ошибка в главном товаре
                    $issetError = Log::instance()->getIssetError();
                    if ($issetError && $key == 0) Log::instance()->setIsset(0); //Если это следующий главный товар, то снимаем ошибку.
                    if ($issetError && $key != 0) break; // Если есть ошибка в главном товаре, то пропускаем этот(дочерний) товар. //@todo: товар пропущен лог

                    // Ошибки категорий и производителей
                    if (!array_key_exists((string)$row['B'],$categories)) throw new HttpException(400, 'Категория не найдена: '.$row['B']);
                    if (!in_array(mb_strtolower($row['C']),$manufacturers)) throw new HttpException(400, 'Производитель не найден: '.$row['C']);


//                    $a1 = array_merge($row,['manufacturers'=>$m]);
//                    $a2 = Product::getProductType($row);
//                    $a3 = $import_type;
//                    $a4 = $rows;

//                    Product::product(array_merge($row,['manufacturers'=>$m]),Product::getProductType($row),$import_type,$rows);
                    Product::product(array_merge($row,['manufacturers'=>$m]),Product::getProductType($row),$import_type);

                }

            }

        }
        else // При типе 3, только код_1с - цена. Больше полей не обновлять, только цена
        {
            Storage::instance()->clear();
            foreach ($rows as $key => $row) {
//                Product::product(array_merge($row, ['manufacturers' => $m]), Product::getProductType($row), $import_type, $rows);
                Product::product(array_merge($row, ['manufacturers' => $m]), Product::getProductType($row), $import_type);
            }
        }

        return Log::instance()->getAll();
    }

    private function getAllCategories() {
        $categories = Category::find()->select('id, title')->where('id_parent <> 0 AND id_parent <> 1')->asArray()->all(); //->where('published = 1')
        $arr = [];
        foreach ($categories as $category) {
            $arr[$category['id']] = $category['title'];
        }
        return $arr;
    }

    private function getAllManufacturers() {
        $manufacturers = Manufacturer::find()->select('id, title')->asArray()->all();
        $arr = [];
        foreach($manufacturers as $manufacturer) {
            $arr[$manufacturer['id']] = mb_strtolower($manufacturer['title']);
        }
        return $arr;
    }

    private function getAllCodes() {
        $codes = Element::find()->select('DISTINCT(article)')->where('published = 1')->asArray()->all();
        $arr = [];
        foreach($codes as $code) {
            if (trim($code['article']) != '')
                $arr[] = $code['article'];
        }
        return $arr;
    }

}