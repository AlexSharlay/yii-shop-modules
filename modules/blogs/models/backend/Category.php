<?php

namespace common\modules\blogs\models\backend;

use Yii;
use common\modules\blogs\traits\ModuleTrait;
use common\components\fileapi\behaviors\UploadBehavior;
use common\modules\base\behaviors\PurifierBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%blogs_category}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $alias
 * @property string $content
 * @property string $image_url
 * @property integer $sort
 * @property integer $status_id
 * @property string $seo_title
 * @property string $seo_keyword
 * @property string $seo_desc
 */
class Category extends ActiveRecord
{
    use ModuleTrait;

    /** Unpublished status **/
    const STATUS_UNPUBLISHED = 0;
    /** Published status **/
    const STATUS_PUBLISHED = 1;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%blogs_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'alias',  'sort'], 'required'],
            [['alias'], 'unique'],
            [['content'], 'string'],
            [['sort', 'status_id'], 'integer'],
            [['title', 'alias'], 'string', 'max' => 100],
            [['seo_title', 'seo_keyword', 'seo_desc'], 'string', 'max' => 255],
            [['image_url'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'uploadBehavior' => [
                'class' => UploadBehavior::className(),
                'attributes' => [
                    'image_url' => [
                        'path' => $this->module->imagePathCategory,
                        'tempPath' => $this->module->imagesTempPathCategory,
                        'url' => $this->module->imageUrlCategory
                    ]
                ]
            ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'alias' => 'Alias',
            'content' => 'Описание',
            'image_url' => 'Image Url',
            'sort' => 'Сортировка',
            'status_id' => 'Статус',
            'seo_title' => 'Seo title',
            'seo_keyword' => 'Seo keyword',
            'seo_desc' => 'Seo desc',
        ];
    }

    /**
     * @return string Readable blog status
     */
    public function getStatus()
    {
        $statuses = self::getStatusArray();

        return $statuses[$this->status_id];
    }

    /**
     * @return array Status array.
     */
    public static function getStatusArray()
    {
        return [
            self::STATUS_UNPUBLISHED => 'Не опубликован',
            self::STATUS_PUBLISHED => 'Опубликован'
        ];
    }

}

