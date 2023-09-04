<?php

namespace common\modules\catalog\controllers\frontend;

use common\modules\catalog\models\frontend\Element;
use common\modules\catalog\models\frontend\Manufacturer;
use common\modules\catalog\models\frontend\Country;
use Yii;
use frontend\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;


class CountryController extends Controller
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
                'countries',
            ],
            'roles' => ['?','@']
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'countries' => ['get'],
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

    public function actionCountries()
    {
        return $this->render('countries', [
            'categories' => Manufacturer::getCategories(),
            'countries' => Country::getCountriesPage(),
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
