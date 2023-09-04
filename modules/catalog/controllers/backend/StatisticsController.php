<?php

namespace common\modules\catalog\controllers\backend;

use Yii;
use backend\components\Controller;
use common\modules\catalog\components\statistics\Filled;

class StatisticsController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => [
                    'filled',
                ],
                'roles' => ['@']
            ],
            [
                'allow' => true,
                'actions' => [
                    'filled-cron',
                ],
                'roles' => ['?','@']
            ]
        ];
        return $behaviors;
    }

    public function actionFilled()
    {

        $date_from = Yii::$app->request->get('Filled')['date_from'];
        $date_to = Yii::$app->request->get('Filled')['date_to'];

        $points = [];
        if (Yii::$app->request->isGet)
            $points = Filled::get($date_from,$date_to);

        return $this->render('filled', [
            'model' => new \common\modules\catalog\models\backend\Filled(),
            'points' => $points,
        ]);
    }

    public function actionFilledCron()
    {
        Filled::add();
    }

}

