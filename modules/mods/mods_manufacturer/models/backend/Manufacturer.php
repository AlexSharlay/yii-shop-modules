<?php

namespace common\modules\mods\mods_manufacturer\models\backend;

use Yii;
use common\components\fileapi\behaviors\UploadBehavior;

class Manufacturer extends \common\modules\mods\mods_manufacturer\models\Manufacturer
{

    public function behaviors()
    {
        return [
            'uploadBehavior' => [
                'class' => UploadBehavior::className(),
                'attributes' => [
                    'ico' => [
                        'path' => $this->module->manufacturerPath,
                        'tempPath' => $this->module->manufacturerTempPath,
                        'url' => $this->module->manufacturerUrl,
                    ],
                ]
            ],
        ];
    }

}
