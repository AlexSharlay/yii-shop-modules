<?php

namespace common\modules\catalog\controllers\frontend;

use common\modules\catalog\models\frontend\Country;
use common\modules\catalog\models\frontend\Element;
use common\modules\catalog\models\frontend\Manufacturer;
use Yii;
use frontend\components\Controller;
use yii\db\Query;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use common\modules\catalog\models\frontend\Category;


class BrandController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        if (!isset($behaviors['access']['class'])) {
            $behaviors['access']['class'] = AccessControl::className();
        }

        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => [
                'brand',
                'brands',
            ],
            'roles' => ['?','@']
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'brand' => ['get'],
                'brands' => ['get'],
            ]
        ];

        return $behaviors;
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionManufacturers()
    {
        return $this->render('manufacturers', [
            'categories' => Manufacturer::getCategories(),
            'manufacturers' => Manufacturer::getManufacturers(),
        ]);
    }

    public function actionBrand()
    {
        $manufacturer = Yii::$app->request->get('manufacturer');
        Manufacturer::issetManufacturer($manufacturer);
        $categoriesMenu = Category::getCategoriesForMenu('bathtub');

        $manufacturers = (new Query())
            ->select('cm.title, cm.alias')
            ->from('{{%catalog_manufacturer}} cm')
            ->leftJoin('{{%catalog_element}} ce', 'ce.id_manufacturer = cm.id')
            ->where('ce.published = 1')
            ->distinct()
            ->groupBy('cm.title')
            //->createCommand()->sql;
            ->all();

        $categories = Manufacturer::getCategories($manufacturer);

        return $this->render('brand', [
            'page' =>  Manufacturer::getManufacturersPage($manufacturer),
//            'categories' => Manufacturer::getCategories($manufacturer),
            'categories' => $categories,
//            'categoriesMenu' => $categoriesMenu,
            'manufacturers' => $manufacturers,
        ]);
    }


    public function actionBrands()
    {
        return $this->render('brands', [
            'manufacturers' => Manufacturer::getManufacturers(),
        ]);
    }




    protected function findModel($id)
    {
        if (($model = Element::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
