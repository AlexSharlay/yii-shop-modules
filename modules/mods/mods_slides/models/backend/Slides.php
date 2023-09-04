<?php

namespace common\modules\mods\mods_slides\models\backend;

use Yii;
use common\components\fileapi\behaviors\UploadBehavior;

class Slides extends \common\modules\mods\mods_slides\models\Slides
{

    public function behaviors()
    {
        return [
            'uploadBehavior' => [
                'class' => UploadBehavior::className(),
                'attributes' => [
                    'img' => [
                        'path' => $this->module->slidesPath,
                        'tempPath' => $this->module->slidesTempPath,
                        'url' => $this->module->slidesUrl,
                    ],
                ]
            ],
        ];
    }



    public static function published() {
        return [
            '1' => 'Опубликовано',
            '0' => 'Не опубликовано',
        ];
    }

}