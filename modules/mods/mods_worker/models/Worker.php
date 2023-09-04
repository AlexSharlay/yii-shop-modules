<?php

namespace common\modules\mods\mods_worker\models;

use Yii;
use yii\db\ActiveRecord;
use common\modules\mods\mods_worker\traits\ModuleTrait;

/**
 * This is the model class for table "{{%mods_worker}}".
 *
 * @property integer $id
 * @property integer $department
 * @property string $fio
 * @property string $photo
 * @property string $flag1
 * @property string $flag2
 * @property string $flag3
 * @property string $flag4
 * @property string $flag5
 * @property string $position
 * @property string $phone
 * @property string $phone_mobile
 * @property string $email
 * @property integer $sort
 */
class Worker extends ActiveRecord
{

    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mods_worker}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['department', 'fio', 'photo', 'position', 'sort'], 'required'],
            [['department', 'sort'], 'integer'],
            [['photo', 'fio', 'flag1', 'flag2', 'flag3', 'flag4', 'flag5', 'position', 'phone', 'phone_mobile', 'email'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'department' => 'Отдел',
            'fio' => 'ФИО',
            'photo' => 'Фото',
            'flag1' => 'Флаг языка 1',
            'flag2' => 'Флаг языка 2',
            'flag3' => 'Флаг языка 3',
            'flag4' => 'Флаг языка 4',
            'flag5' => 'Флаг языка 5',
            'position' => 'Должность',
            'phone' => 'Телефон',
            'phone_mobile' => 'Телефон мобильный',
            'email' => 'E-mail',
            'sort' => 'Сортировка',
        ];
    }
}
