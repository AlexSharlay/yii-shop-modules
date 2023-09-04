<?php

namespace common\modules\catalog\models\backend;

use Yii;
use yii\db\ActiveRecord;
use common\modules\catalog\traits\ModuleTrait;

class Filled extends ActiveRecord
{

    use ModuleTrait;

    public $date_from;
    public $date_to;

    public function rules()
    {
        return [
           // [['date_from', 'date_to'], 'integer', 'integerOnly' => false]
        ];
    }

}
