<?php

namespace common\modules\catalog\models;

use Yii;
use yii\db\ActiveRecord;
use common\modules\catalog\traits\ModuleTrait;

/**
 * This is the model class for table "{{%catalog_statistic_filled}}".
 *
 * @property integer $id
 * @property integer $all
 * @property integer $with_photo
 * @property integer $fill_mini
 * @property integer $fill_full
 * @property string $date
 */
class StatisticFilled extends ActiveRecord
{

    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%catalog_statistic_filled}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['all', 'with_photo', 'fill_mini', 'fill_full'], 'integer'],
            [['date'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'all' => 'All',
            'with_photo' => 'With Photo',
            'fill_mini' => 'Fill Mini',
            'fill_full' => 'Fill Full',
            'date' => 'Date',
        ];
    }
}
