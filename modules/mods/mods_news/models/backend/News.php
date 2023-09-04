<?php

namespace common\modules\mods\mods_news\models\backend;

use Yii;
use common\components\fileapi\behaviors\UploadBehavior;

class News extends \common\modules\mods\mods_news\models\News
{

    public function behaviors()
    {
        return [
            'uploadBehavior' => [
                'class' => UploadBehavior::className(),
                'attributes' => [
                    'image' => [
                        'path' => $this->module->newsPath,
                        'tempPath' => $this->module->newsTempPath,
                        'url' => $this->module->newsUrl,
                    ],
                ]
            ],
        ];
    }

    public static function colors() {
        return [
            '1' => 'default',
            '2' => 'primary',
            '3' => 'danger',
            '4' => 'success',
            '5' => 'warning',
            '6' => 'info',
        ];
    }

    public static function target() {
        return [
            '1' => 'В новом',
            '0' => 'В том же',
        ];
    }

    public static function published() {
        return [
            '1' => 'Опубликовано',
            '0' => 'Не опубликовано',
        ];
    }

}