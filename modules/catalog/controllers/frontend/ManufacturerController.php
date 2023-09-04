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


class ManufacturerController extends Controller
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
                'manufacturer',
                'manufacturers',
            ],
            'roles' => ['?','@']
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'manufacturer' => ['get'],
                'manufacturers' => ['get'],
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

    public function actionManufacturer()
    {
        $manufacturer = Yii::$app->request->get('manufacturer');
        Manufacturer::issetManufacturer($manufacturer);
        return $this->render('manufacturer', [
            'page' =>  Manufacturer::getManufacturersPage($manufacturer),
            'categories' => Manufacturer::getCategories($manufacturer),
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
