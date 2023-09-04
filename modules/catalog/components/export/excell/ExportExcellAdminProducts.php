<?php
/**
 * Created by PhpStorm.
 * User: vitalbu
 * Date: 20.12.2017
 * Time: 8:56
 */

namespace common\modules\catalog\components\export\excell;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Worksheet_MemoryDrawing;
use Yii;
use \yii\db\Query;
use common\modules\catalog\components\File as sendFile;

class ExportExcellAdminProducts
{

    public static function run($products,$photos)
    {

        /*
            Производитель       'cm.title AS brand'
            Производитель Алиас 'cm.alias AS brand_alias'
            Категория           'cc1.title AS category'
            Категория Алиас     'cc1.alias AS category_alias'
            Префикс             'ce.title_before'
            Имя                 'ce.title'
            Имя Алиас           'ce.alias'
            Имя модели          'ce.title_model'
            Артикул             'ce.code_1c'
            Заводской артикул   'ce.vendor_code'
            Гарантия, мес       'ce.guarantee'
            URL продукта         url
            Описание краткое    'ce.desc_mini'
            Описание полное     'ce.desc_full'
            Изображения
        */



        // В файл
        $file = self::generateExcell($products,$photos);

        // Вернуть документ
        sendFile::file_force_download($file);

    }

    private function generateExcell($products,$photos)
    {
        $objPHPExcel = new PHPExcel();
        $page = $objPHPExcel->setActiveSheetIndex(0); // Делаем активной первую страницу и получаем её

        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9);

        /*
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(67);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(71);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(11);
        */

        $objPHPExcel->getActiveSheet()->getStyle("0:1")->getFont()->setBold(true);

        $row = 1;
        $col = 0;

        $page->setCellValueByColumnAndRow($col++, $row, 'Производитель');
        $page->setCellValueByColumnAndRow($col++, $row, 'Производитель Алиас');
        $page->setCellValueByColumnAndRow($col++, $row, 'Категория');
        $page->setCellValueByColumnAndRow($col++, $row, 'Категория Алиас');
        $page->setCellValueByColumnAndRow($col++, $row, 'Префикс');
        $page->setCellValueByColumnAndRow($col++, $row, 'Имя');
        $page->setCellValueByColumnAndRow($col++, $row, 'Имя Алиас');
        $page->setCellValueByColumnAndRow($col++, $row, 'Имя модели');
        $page->setCellValueByColumnAndRow($col++, $row, 'Артикул');
        $page->setCellValueByColumnAndRow($col++, $row, 'Заводской артикул');
        $page->setCellValueByColumnAndRow($col++, $row, 'Гарантия, мес');
        $page->setCellValueByColumnAndRow($col++, $row, 'URL продукта');
        $page->setCellValueByColumnAndRow($col++, $row, 'Описание краткое');
        $page->setCellValueByColumnAndRow($col++, $row, 'Описание полное');
        $page->setCellValueByColumnAndRow($col++, $row, 'Изображения');


        $row = 2;
        foreach ($products as $product) {
            $col = 0;
            $page->setCellValueByColumnAndRow($col++, $row, $product['brand']);
            $page->setCellValueByColumnAndRow($col++, $row, $product['brand_alias']);
            $page->setCellValueByColumnAndRow($col++, $row, $product['category']);
            $page->setCellValueByColumnAndRow($col++, $row, $product['category_alias']);
            $page->setCellValueByColumnAndRow($col++, $row, $product['title_before']);
            $page->setCellValueByColumnAndRow($col++, $row, $product['title']);
            $page->setCellValueByColumnAndRow($col++, $row, $product['alias']);
            $page->setCellValueByColumnAndRow($col++, $row, $product['title_model']);
            $page->setCellValueByColumnAndRow($col++, $row, $product['code_1c']);
            $page->setCellValueByColumnAndRow($col++, $row, $product['vendor_code']);
            $page->setCellValueByColumnAndRow($col++, $row, $product['guarantee']);
            $page->setCellValueByColumnAndRow($col++, $row, 'https:/kranik.by' . $product['url']);
            $page->setCellValueByColumnAndRow($col++, $row, $product['desc_mini']);
            $page->setCellValueByColumnAndRow($col++, $row, $product['desc_full']);

            $list_photos = '';
            foreach ($photos[$product['id']] as $photo) {
                $list_photos .= ',' . $photo;
            }
            $list_photos = substr($list_photos, 1);

            $page->setCellValueByColumnAndRow($col++, $row, $list_photos);
            $row++;

        }

        $page->setTitle("Экспорт");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); // Начинаем готовиться к записи информации в xlsx-файл
        $name = 'export_Kranik_products_' . date('d.m.Y_H-i-s') . '.xlsx';
        $name = iconv('utf-8', 'windows-1251', $name);
        $objWriter->save(Yii::getAlias('@statics') . '/web/catalog/export/admin-' . $name); // Записываем в файл

        return Yii::getAlias('@statics') . '/web/catalog/export/admin-products-' . $name;

    }

}
