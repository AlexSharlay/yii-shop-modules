<?php
namespace common\modules\catalog\components\export\excell;

use common\modules\catalog\components\Helper;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Worksheet_MemoryDrawing;
use common\modules\catalog\models\frontend\Category;
use \yii\db\Query;
use Yii;
use common\modules\catalog\components\File as sendFile;
//use \common\modules\shop\models\UserDiscount;
use \common\modules\shop\models\UserDiscount1c;

class ExportExcell
{

    private static $ids = [];

    private static $_titles = [];

    public function run()
    {
        // Получить товары
        $query = new Query;
        $products = $query
            ->select('e.title, e.price, e.in_stock, m.title as manufacturer, c.id as categoryId, e.id_category_1c')
            ->from('tbl_catalog_element e')
            ->leftJoin('tbl_catalog_category c', 'e.id_category = c.id')
            ->leftJoin('tbl_catalog_manufacturer m', 'e.id_manufacturer = m.id')
            ->where('e.in_stock > 0 AND e.published = 1')
            ->orderBy('e.id_category ASC')
            ->all();

        // Получить категории
        $categories = Category::find()->select('id, id_parent as parent, title')->where('')->asArray()->all(); //->where('published = 1')

        // Добавить товарам путь категорий
        $products = self::addCategoryPath($products, $categories);

        // Покупатель
        if (!Yii::$app->user->isGuest) {

            // Скидки есть?
//            $discounts = UserDiscount::discounts(Yii::$app->user->id)['discounts'];
            $discounts = UserDiscount1c::getUserDiscount1c(Yii::$app->user->id);

            // Очистить от скидок = 0%
//            foreach($discounts as $key=>$discount) if ($discount['discount'] == 0) unset($discounts[$key]);

            // Да, со скидочками
//            if (count($discounts)) {
            foreach ($products as $key => $product) {
                $arr_id_category_1c = json_decode($product['id_category_1c']);
                if (is_array($arr_id_category_1c)) {
                    foreach ($arr_id_category_1c as $id_category_1c) {

                        if (array_key_exists($id_category_1c, $discounts)) {
                            if ($products[$key]['price']) {
//                            foreach ($discounts as $discount) {
//                                if ($product['categoryId'] == $discount['id']) {
                                $products[$key]['price'] = round($products[$key]['price'] / 100 * (100 - $discounts[$id_category_1c]), 0);
                            }
                        }
                    }
                }
//                }
            }

        }

        // В файл
        $file = self::generateExcell($products);

        // Вернуть документ
        sendFile::file_force_download($file);

    }

    private function addCategoryPath(array $products, array $categories)
    {
        foreach ($products as $key => $product) {
            if (!in_array($product['categoryId'], self::$ids)) {
                self::getThreePath($product['categoryId'], $categories);
                self::$_titles = array_reverse(self::$_titles);
                $title = implode(' - ', self::$_titles);
                self::$_titles = [];
                self::$ids[$title] = $product['categoryId'];

            } else {
                $products[$key]['category'] = array_search($product['categoryId'], self::$ids);
            }
        }
        return $products;
    }

    private function getThreePath($id, $categories)
    {
        if ($id == 0) {
            return true;
        } else {
            $c = false;
            $parent = 0;
            foreach ($categories as $category) {
                if ($id == $category['id']) {
                    if (!in_array($category['title'], self::$_titles)) {
                        self::$_titles[] = $category['title'];
                    }
                    $parent = $category['parent'];
                    $c = true;
                }
            }
            if ($c) {
                self::getThreePath($parent, $categories);
            } else {
                return true;
            }
        }
    }

    private function generateExcell($products)
    {
        $objPHPExcel = new PHPExcel();
        $page = $objPHPExcel->setActiveSheetIndex(0); // Делаем активной первую страницу и получаем её

        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(67);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(71);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(11);

        $objPHPExcel->getActiveSheet()->getStyle("3:5")->getFont()->setBold(true);


        //$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(40);
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(80);
        $gdImage = imagecreatefromjpeg(Yii::getAlias('@statics') . '/web/catalog/export/info.jpg');
        $objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
        $objDrawing->setName('info');
        $objDrawing->setDescription('info');
        $objDrawing->setImageResource($gdImage);
        $objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
        $objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
        //$objDrawing->setHeight(150);
        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

        $row = 3;
        $page->setCellValueByColumnAndRow(0, $row, 'Цены указаны на ' . date('d.m.Y H:i'));

        $row = 5;
        $page->setCellValueByColumnAndRow(0, $row, 'Категория');
        $page->setCellValueByColumnAndRow(1, $row, 'Бренд');
        $page->setCellValueByColumnAndRow(2, $row, 'Наименование товара');
        $page->setCellValueByColumnAndRow(3, $row, 'Цена c НДС');

        $row = 6;
        foreach ($products as $product) {
            $page->setCellValueByColumnAndRow(0, $row, $product['category']);
            $page->setCellValueByColumnAndRow(1, $row, $product['manufacturer']);
            $page->setCellValueByColumnAndRow(2, $row, $product['title']);
            $page->setCellValueByColumnAndRow(3, $row, ($product['price'] == 0) ? '' : Helper::formatPrice($product['price']));
            $row++;
        }

        $page->setTitle("Прайс"); // Ставим заголовок "Test" на странице
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); // Начинаем готовиться к записи информации в xlsx-файл
        $name = 'Прайс «Сантехпром» ' . date('d.m.Y H-i-s') . '.xlsx';
        $name = iconv('utf-8', 'windows-1251', $name);
        $objWriter->save(Yii::getAlias('@statics') . '/web/catalog/export/' . $name); // Записываем в файл

        return Yii::getAlias('@statics') . '/web/catalog/export/' . $name;
    }

    private function buildTree(array $categories)
    {
        $tree = [];
        $sub = [0 => &$tree];
        foreach ($categories as $item) {
            $id = $item['id'];
            $parent = $item['parent'];
            $title = $item['title'];

            $branch = &$sub[$parent];
            $branch[$id]['title'] = $title;
            $sub[$id] = &$branch[$id];
        }
        return $tree;
    }

}