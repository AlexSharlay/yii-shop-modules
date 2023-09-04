<?php
namespace common\modules\catalog\components\export\excell;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Worksheet_MemoryDrawing;
use Yii;
use \yii\db\Query;
use common\modules\catalog\components\File as sendFile;

class ExportExcellAdmin
{

    public static function run($brand_id)
    {

        /*
            Id категории
            Бренд
            Префикс
            Имя
            Имя модели
            Сортировка
            Артикул
            Код 1с
            На складе
            Публикация
            Цена
            Гарантия, мес
            Срок годности, мес
            Производитель
            Импортер
            Сервисный центр
            onliner.by
            1k.by
            shop.by
            kypi.tut.by - yandex
            unishop.by
        */

        $parents = (new Query())
            ->select('e.id, e.id_category, e.sort, m.title as manufacturer, e.title_before, e.title, e.title_model, e.code_1c, e.in_stock, e.published, e.price,
                    e.guarantee, e.life_time, e.info_manufacturer, e.info_importer, e.info_service, 
                    e.tp_onliner_by_url, e.tp_1k_by_url, e.tp_shop_by_url, e.tp_market_yandex_by_url, e.tp_unishop_by_url')
            ->from('{{%catalog_element}} e')
            ->leftJoin('{{%catalog_manufacturer}} m', 'm.id = e.id_manufacturer')
            ->where('e.is_model = 0')
            ->andWhere(['m.id' => $brand_id])
            ->orderBy('m.title ASC')
            ->all();

        $childrens = (new Query())
            ->select('e.id, mr.id_element_parent as id_parent, e.id_category, e.sort, m.title as manufacturer, e.title_before, e.title, e.title_model, 
                    e.code_1c, e.in_stock, e.published, e.price, e.guarantee, e.life_time, e.info_manufacturer, e.info_importer, e.info_service, 
                    e.tp_onliner_by_url, e.tp_1k_by_url, e.tp_shop_by_url, e.tp_market_yandex_by_url, e.tp_unishop_by_url')
            ->from('{{%catalog_element}} e')
            ->leftJoin('{{%catalog_manufacturer}} m', 'm.id = e.id_manufacturer')
            ->leftJoin('{{%catalog_model_rel}} mr', 'mr.id_element_children = e.id')
            ->where('mr.id_element_parent IS NOT NULL')
            ->andWhere(['m.id' => $brand_id])
            ->orderBy('m.title ASC')
            ->all();

        $idParents = array_column($childrens, 'id_parent');
        $idParents = array_unique($idParents);

        foreach ($parents as $key => $parent) {
            if (in_array($parent['id'], $idParents)) {
                foreach ($childrens as $children) {
                    if ($parent['id'] == $children['id_parent']) {
                        $parents[$key]['models'][] = $children;
                    }
                }
            }
        }

        // В файл
        $file = self::generateExcell($parents);

        // Вернуть документ
        sendFile::file_force_download($file);

    }

    private function generateExcell($products)
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

        $page->setCellValueByColumnAndRow($col++, $row, '');
        $page->setCellValueByColumnAndRow($col++, $row, 'Id категории');
        $page->setCellValueByColumnAndRow($col++, $row, 'Бренд');
        $page->setCellValueByColumnAndRow($col++, $row, 'Префикс');
        $page->setCellValueByColumnAndRow($col++, $row, 'Имя');
        $page->setCellValueByColumnAndRow($col++, $row, 'Имя модели');
        $page->setCellValueByColumnAndRow($col++, $row, 'Сортировка');
        $page->setCellValueByColumnAndRow($col++, $row, 'Артикул');
        $page->setCellValueByColumnAndRow($col++, $row, 'Код 1с');
        $page->setCellValueByColumnAndRow($col++, $row, 'На складе');
        $page->setCellValueByColumnAndRow($col++, $row, 'Публикация');
        $page->setCellValueByColumnAndRow($col++, $row, 'Цена');
        $page->setCellValueByColumnAndRow($col++, $row, 'Гарантия, мес');
        $page->setCellValueByColumnAndRow($col++, $row, 'Срок годности, мес');
        $page->setCellValueByColumnAndRow($col++, $row, 'Производитель');
        $page->setCellValueByColumnAndRow($col++, $row, 'Импортер');
        $page->setCellValueByColumnAndRow($col++, $row, 'Сервисный центр');
        $page->setCellValueByColumnAndRow($col++, $row, 'onliner.by');
        $page->setCellValueByColumnAndRow($col++, $row, '1k.by');
        $page->setCellValueByColumnAndRow($col++, $row, 'shop.by');
        $page->setCellValueByColumnAndRow($col++, $row, 'market.yandex.by');
        $page->setCellValueByColumnAndRow($col, $row, 'unishop.by');

        $row = 2;
        foreach ($products as $product) {
            $col = 0;
            $page->setCellValueByColumnAndRow($col++, $row, '');
            $page->setCellValueByColumnAndRow($col++, $row, $product['id_category']);
            $page->setCellValueByColumnAndRow($col++, $row, $product['manufacturer']);
            $page->setCellValueByColumnAndRow($col++, $row, $product['title_before']);
            $page->setCellValueByColumnAndRow($col++, $row, $product['title']);
            $page->setCellValueByColumnAndRow($col++, $row, $product['title_model']);
            $page->setCellValueByColumnAndRow($col++, $row, $product['sort']);
            $page->setCellValueByColumnAndRow($col++, $row, $product['code_1c']);
            $page->setCellValueByColumnAndRow($col++, $row, $product['code_1c']);
            $page->setCellValueByColumnAndRow($col++, $row, $product['in_stock']);
            $page->setCellValueByColumnAndRow($col++, $row, $product['published']);
            $page->setCellValueByColumnAndRow($col++, $row, (count($product['models'])) ? '' : $product['price'] / 100);
            $page->setCellValueByColumnAndRow($col++, $row, $product['guarantee']);
            $page->setCellValueByColumnAndRow($col++, $row, $product['life_time']);
            $page->setCellValueByColumnAndRow($col++, $row, $product['info_manufacturer']);
            $page->setCellValueByColumnAndRow($col++, $row, $product['info_importer']);
            $page->setCellValueByColumnAndRow($col++, $row, $product['info_service']);
            $page->setCellValueByColumnAndRow($col++, $row, $product['tp_onliner_by_url']);
            $page->setCellValueByColumnAndRow($col++, $row, $product['tp_1k_by_url']);
            $page->setCellValueByColumnAndRow($col++, $row, $product['tp_shop_by_url']);
            $page->setCellValueByColumnAndRow($col++, $row, $product['tp_market_yandex_by_url']);
            $page->setCellValueByColumnAndRow($col, $row, $product['tp_unishop_by_url']);
            $row++;

            if (count($product['models'])) {
                $i = 1;
                foreach ($product['models'] as $model) {
                    $col = 0;
                    $page->setCellValueByColumnAndRow($col++, $row, 'Модель ' . $i);
                    $page->setCellValueByColumnAndRow($col++, $row, $model['id_category']);
                    $page->setCellValueByColumnAndRow($col++, $row, $model['manufacturer']);
                    $page->setCellValueByColumnAndRow($col++, $row, $model['title_before']);
                    $page->setCellValueByColumnAndRow($col++, $row, $model['title']);
                    $page->setCellValueByColumnAndRow($col++, $row, $model['title_model']);
                    $page->setCellValueByColumnAndRow($col++, $row, $model['sort']);
                    $page->setCellValueByColumnAndRow($col++, $row, $model['code_1c']);
                    $page->setCellValueByColumnAndRow($col++, $row, $model['code_1c']);
                    $page->setCellValueByColumnAndRow($col++, $row, $model['in_stock']);
                    $page->setCellValueByColumnAndRow($col++, $row, $model['published']);
                    $page->setCellValueByColumnAndRow($col++, $row, $model['price'] / 100);
                    $page->setCellValueByColumnAndRow($col++, $row, $model['guarantee']);
                    $page->setCellValueByColumnAndRow($col++, $row, $model['life_time']);
                    $page->setCellValueByColumnAndRow($col++, $row, $model['info_manufacturer']);
                    $page->setCellValueByColumnAndRow($col++, $row, $model['info_importer']);
                    $page->setCellValueByColumnAndRow($col++, $row, $model['info_service']);
                    $page->setCellValueByColumnAndRow($col++, $row, $model['tp_onliner_by_url']);
                    $page->setCellValueByColumnAndRow($col++, $row, $model['tp_1k_by_url']);
                    $page->setCellValueByColumnAndRow($col++, $row, $model['tp_shop_by_url']);
                    $page->setCellValueByColumnAndRow($col++, $row, $model['tp_market_yandex_by_url']);
                    $page->setCellValueByColumnAndRow($col, $row, $model['tp_unishop_by_url']);
                    $i++;
                    $row++;
                }
            }

        }

        $page->setTitle("Экспорт");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); // Начинаем готовиться к записи информации в xlsx-файл
        $name = 'export_Kranik_' . date('d.m.Y_H-i-s') . '.xlsx';
        $name = iconv('utf-8', 'windows-1251', $name);
        $objWriter->save(Yii::getAlias('@statics') . '/web/catalog/export/admin-' . $name); // Записываем в файл

        return Yii::getAlias('@statics') . '/web/catalog/export/admin-' . $name;

    }

}
