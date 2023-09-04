<?php

namespace common\modules\mods\mods_review\models\backend;

use Yii;

class Review extends \common\modules\mods\mods_review\models\Review
{

    public static function published() {
        return [
            '0' => 'Не проверено',
            '1' => 'Опубликовано',
            '2' => 'Отказано',
        ];
    }

}
