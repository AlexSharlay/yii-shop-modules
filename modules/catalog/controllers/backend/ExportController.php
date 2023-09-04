<?php

namespace common\modules\catalog\controllers\backend;

use Yii;
use backend\components\Controller;
use common\modules\catalog\components\export\marketplace\Marketplace;

class ExportController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => [
                    'marketplace',
                ],
                'roles' => ['?','@']
            ],
        ];

        return $behaviors;
    }

    public function actionMarketplace()
    {
        (new Marketplace())->generateXml();
    }

}

