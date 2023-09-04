<?php

namespace common\modules\catalog\controllers\backend;

use common\modules\catalog\components\export\excell\ExportExcellAdmin;
use common\modules\catalog\components\import\excell\ImportExcell;
use common\modules\catalog\components\import\excell\ImportExcellCard;
use common\modules\catalog\components\import\excell\ImportFotoKranik;
use common\modules\catalog\components\import\excell\Log;
use common\modules\catalog\components\import\marketplace\Import;
use common\modules\catalog\models\backend\Element;
use common\modules\catalog\models\backend\Manufacturer;
use Yii;
use backend\components\Controller;
use common\modules\catalog\models\backend\UploadXlsx;
use yii\db\Query;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use \common\modules\catalog\components\export\excell;
use common\modules\catalog\components\import\excell\ImportExcellNews;

ini_set("max_execution_time", "3600");

class ImportController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => [
                    'import-from-excell',
                    'import-from-excell-news',
                    'import-from-excell-card',
                    'import-to-excell',
                    'import-foto-kranik',
                    '1c',
                    'export-to-onliner',
                    'import-desc-onliner',
                    'import-desc-yandex',
                    'import-desc-onek',
                    'model-photo',
                    'model-photo-main',
                    'import-photo',
                ],
                'roles' => ['@']
            ],
        ];
        return $behaviors;
    }

    public function actionImportFromExcell()
    {
        $model = new UploadXlsx();
        $results = [];

        if (Yii::$app->request->isPost) {
            $model->xlsxFile = UploadedFile::getInstance($model, 'xlsxFile');
            if ($model->upload()) {
                $obj = new ImportExcell;
                $results = $obj->run($model->file, Yii::$app->request->post('UploadXlsx')['import_type']);
            }
        }

        return $this->render('excell', ['model' => $model, 'results' => $results]);
    }

    public function actionImportToExcell()
    {
        $model = new UploadXlsx();
        $results = [];


        $table_schema = Element::getTableSchema();
        $columns_name = [];
        foreach ($table_schema->columns as $column) {
            $columns_name[] = $column->name;
        }

        $brand_name = Manufacturer::find()->select('id, title, perekup')->asArray()->orderBy(['title' => SORT_ASC])->all();


        if ($model->load(Yii::$app->request->post())) {
            if (is_array(Yii::$app->request->post()['UploadXlsx']['brand_name'])) {
                $brand_id = Yii::$app->request->post()['UploadXlsx']['brand_name'];
//                $brand_id = [2,14];
            } else {
                $brand_id = '';
            }
            ExportExcellAdmin::run($brand_id);
        }

        return $this->render('export-to-excel', compact('columns_name', 'brand_name', 'model'));

        // Только товар и модели, без сборок и прочего
        //ExportExcellAdmin::run();
    }

    public function actionImportFotoKranik()
    {
        $obj = new ImportFotoKranik;
        return $this->render('photo-kranik', ['count' => $obj->run()]);
    }

    public function actionImportPhoto()
    {
//        $productsSql = "
//        SELECT * FROM
//        (
//        SELECT ce.id , ce.title
//        FROM `tbl_catalog_element` `ce`
//        ) t1 LEFT OUTER JOIN
//
//        `tbl_catalog_photo` `cp`
//on cp.id_element = t1.id
//
//        ";
//        $parents = Yii::$app->db->createCommand($productsSql, [])->queryAll();

        $photosSql = (new Query())
            ->select('cp.id_element, cp.name')
            ->from('{{%catalog_photo}} cp')
            ->leftJoin('{{%catalog_element}} ce', 'ce.id = cp.id_element')
            ->where('ce.published = 1')
            ->andWhere('ce.price_1c > 0')
//            ->andWhere('ce.id_category = 93')//////////////
            ->all();

        $photos = [];
        foreach ($photosSql as $photo) {
            $photos[$photo['id_element']][] = $photo['name'];
        }
        $countPhotos = count($photos);

        unset($photosSql);

        $products = (new Query())
            ->select(['ce.id', 'ce.alias', 'ce.title', 'ce.title_before', 'ce.title_model', 'ce.desc_mini', 'ce.desc_full', 'ce.code_1c', 'ce.guarantee', 'ce.vendor_code',
                'cm.alias AS brand_alias', 'cm.title AS brand', 'cc1.title AS category', 'cc1.alias AS category_alias',
                "CONCAT_WS('/', '',cc4.alias,cc3.alias, cc2.alias, cc1.alias, ce.alias, '') AS url",])
            ->from('{{%catalog_element}} ce')
            ->leftJoin('{{%catalog_category}} cc1', 'cc1.id = ce.id_category')
            ->leftJoin('{{%catalog_category}} cc2', 'cc2.id = cc1.id_parent')
            ->leftJoin('{{%catalog_category}} cc3', 'cc3.id = cc2.id_parent')
            ->leftJoin('{{%catalog_category}} cc4', 'cc4.id = cc3.id_parent')
//            ->leftJoin('{{%catalog_photo}} cp', 'cp.id_element = ce.id')
            ->leftJoin('{{%catalog_manufacturer}} cm', 'cm.id = ce.id_manufacturer')
            ->where('ce.published = 1')
            ->andWhere('ce.price_1c > 0')
//            ->andWhere('ce.id_category = 93')//////////////
            ->orderBy('brand_alias')
            ->all();
        $countProducts = count($products);

//        setlocale(LC_ALL, 'ru_RU.UTF-8');
//        setlocale(LC_NUMERIC, 'POSIX');
//
//        function get_str_cp($str){
//            $arrCP = array('utf-8', 'ISO-8859-5', 'windows-1251'); // предполагаемые кодировки по приоритету
//            foreach ($arrCP as $key=>$cp){
//                if (md5($str) === md5(iconv($cp, $cp, $str))){
//                    return $cp;
//                }
//            }
//            return null;
//        }



//        excell\ExportExcellAdminProducts::run($products,$photos);

        $pathPhotoSite = '../../statics/web/catalog/photo/images/';
//        $pathPhotoSite = '/statics/web/catalog/photo/images/';
//        $pathPhotoLocal = 'd:\\images_kranik\\привет\\';
        $pathPhotoLocal = 'd:\\images_kranik\\';


        $i = 0;
        $j = 0;
//        if (!mkdir($pathPhotoLocal,0755)) {
//            echo 'error';
//        }
//
//        FileHelper::createDirectory($pathPhotoLocal);
//        foreach ($products as $product) {
//            mkdir($pathPhotoLocal . $product['brand_alias']);
//            mkdir($pathPhotoLocal . $product['brand_alias'] . '/' . $product['category_alias']);
//            $pathPhotoLocalProduct = $pathPhotoLocal . $product['brand_alias'] . '/' . $product['category_alias'] . '/' . $product['code_1c'] . '/';
//            mkdir($pathPhotoLocalProduct);
//            foreach ($photos[$product['id']] as $item) {
//
//                if (!copy($pathPhotoSite . $item, $pathPhotoLocalProduct . $item)) {
//                    echo 'Не удалось скопировать: ' .
//                        ' brand => ' . $product['brand'] .
//                        ' category => ' . $product['category'] .
//                        ' article => ' . $product['code_1c'] .
//                        ' title => ' . $product['title'] .
//                        ' photo => ' . $item . '<br>';
//                    $j++;
//                } else {
//                    $i++;
//                }
//            }
//        }

        echo 'Скопировано: ' . $i . ' фото.' . '<br>';
        echo 'Не найдено: ' . $j . ' фото.' . '<br>';


        return $this->render('photo', compact('products', 'photos', 'countProducts', 'countPhotos'));
    }

    public function action1c()
    {
        return $this->render('1c_index');
    }

    public function actionExportToOnliner()
    {
        return $this->render('export-to-onliner', '');
    }

    public function actionImportDescOnliner()
    {
        return $this->render('marketplace', ['i' => Import::onliner()]);
    }

    public function actionImportDescYandex()
    {
        return $this->render('marketplace', ['i' => Import::yandex()]);
    }

    public function actionImportDescOnek()
    {
        return $this->render('marketplace', ['i' => Import::onek()]);
    }

    // У главного товара есть фото, а у моделей нет. Добавить всем моделям родительское фото.
    public function actionModelPhoto()
    {
        Import::modelPhoto();
        return $this->render('model-photo');
    }

    // У моделей фото есть, а у родителя нет, добавить ему любое фото из моделей.
    public function actionModelPhotoMain()
    {
        Import::modelPhotoMain();
        return $this->render('model-photo-main');
    }


    public function actionImportFromExcellNews()
    {
        $model = new UploadXlsx();
        $results = [];

        if (Yii::$app->request->isPost) {
            $model->xlsxFile = UploadedFile::getInstance($model, 'xlsxFile');
            if ($model->upload()) {
                $obj = new ImportExcellNews;
                $results = $obj->run($model->file, Yii::$app->request->post('UploadXlsx')['import_type']);
            }
        }

        return $this->render('import-from-excell-news', ['model' => $model, 'results' => $results]);
    }

    public function actionImportFromExcellCard()
    {

        $model = new UploadXlsx();
        $results = [];

        if (Yii::$app->request->isPost) {
            $model->xlsxFile = UploadedFile::getInstance($model, 'xlsxFile');
            if ($model->upload()) {
                $obj = new ImportExcellCard();
                $results = $obj->run($model->file, Yii::$app->request->post('UploadXlsx')['import_type']);
            }
        }

        return $this->render('import-from-excell-card', ['model' => $model, 'results' => $results]);
    }




}
