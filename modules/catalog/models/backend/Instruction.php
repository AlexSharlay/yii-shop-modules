<?php

namespace common\modules\catalog\models\backend;
use yii\db\ActiveRecord;

use Yii;

/**
 * This is the model class for table "{{%catalog_instruction}}".
 *
 * @property integer $id
 * @property integer $id_element
 * @property string $name
 * @property integer $sort
 * @property integer $published
 */
class Instruction extends \common\modules\catalog\models\Instruction
{

    public $file = [];
    public $id_element;
    public $name;
    public $sort;
    public $published;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%catalog_instruction}}';
    }
}
