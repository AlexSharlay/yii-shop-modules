<?php

namespace common\modules\mods\mods_job\models;

use Yii;
use yii\db\ActiveRecord;
use common\modules\mods\mods_manufacturer\traits\ModuleTrait;


/**
 * This is the model class for table "{{%mods_job}}".
 *
 * @property integer $id
 * @property string $department
 * @property string $vacancy
 * @property string $salary
 * @property string $content
 * @property integer $sort
 */
class Job extends ActiveRecord
{

    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mods_job}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['department', 'vacancy', 'content', 'sort'], 'required'],
            [['content'], 'string'],
            [['sort'], 'integer'],
            [['department', 'vacancy', 'salary'], 'string', 'max' => 255],
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
            'vacancy' => 'Вакансия',
            'salary' => 'Зарплата',
            'content' => 'Описание вакансии',
            'sort' => 'Порядок',
        ];
    }
}
