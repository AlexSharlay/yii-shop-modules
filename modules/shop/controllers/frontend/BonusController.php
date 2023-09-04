<?php

namespace common\modules\shop\controllers\frontend;

use Yii;
use backend\components\Controller;
use common\modules\shop\models\frontend\Cart;

class BonusController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => [
                'bonus'
            ],
            'roles' => ['?', '@']
        ];

        return $behaviors;
    }

    public function actionBonus()
    {

        return $this->render('bonus');
    }


}
