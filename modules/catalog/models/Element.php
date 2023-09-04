<?php

namespace common\modules\catalog\models;

use Yii;
use yii\db\ActiveRecord;
use common\modules\catalog\models\Category;
use common\modules\catalog\models\Manufacturer;
use common\modules\catalog\models\backend\Measurement;
use yii\helpers\ArrayHelper;
use common\components\fileapi\behaviors\UploadBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%catalog_element}}".
 *
 * @property integer $id
 * @property string $alias
 * @property string $title
 * @property string $title_before
 * @property string $title_model
 * @property string $desc_mini
 * @property string $desc_full
 * @property string $desc_yml
 * @property integer $id_category
 * @property string $id_category_1c
 * @property integer $id_manufacturer
 * @property integer $id_measurement
 * @property integer $published
 * @property string $created_at
 * @property string $updated_at
 * @property string $article
 * @property integer $price_1c
 * @property string $code_1c
 * @property string $vendor_code
 * @property integer $price
 * @property integer $price_control
 * @property integer $price_old
 * @property double $in_stock
 * @property integer $is_defect
 * @property integer $is_main
 * @property integer $is_model
 * @property integer $is_custom
 * @property integer $hit
 * @property integer $in_status
 * @property integer $in_action
 * @property integer $in_new
 * @property integer $halva
 * @property string $info_manufacturer
 * @property integer $info_importer
 * @property integer $info_service
 * @property integer $tip_1c
 * @property integer $sort
 *
 * @property string $tp_onliner_by_alias
 * @property string $tp_onliner_by_title
 * @property string $tp_onliner_by_url
 * @property string $tp_1k_by_title
 * @property string $tp_1k_by_alias
 * @property string $tp_1k_by_url
 * @property string $tp_market_yandex_by_title
 * @property string $tp_market_yandex_by_alias
 * @property string $tp_market_yandex_by_url
 * @property string $tp_shop_by_title
 * @property string $tp_shop_by_alias
 * @property string $tp_shop_by_url
 * @property string $tp_unishop_by_title
 * @property string $tp_unishop_by_alias
 * @property string $tp_unishop_by_url
 *
 * @property string $note
 * @property string $seo_title
 * @property string $seo_keyword
 * @property string $seo_desc
 * @property string $guarantee
 * @property string $life_time
 */
class Element extends ActiveRecord
{

    /** Unpublished status **/
    const STATUS_UNPUBLISHED = 0;

    /** Published status **/
    const STATUS_PUBLISHED = 1;

    //public $imageFiles = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%catalog_element}}';
    }

    public function behaviors()
    {
        return [
            'timestampBehavior' => [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['imageFiles'], 'file'],
            [['title', 'id_category', 'id_manufacturer' , 'id_measurement'], 'required'],
            [['alias', 'code_1c', 'alias', 'vendor_code'], 'unique'],
            [['desc_mini', 'desc_full',  'desc_yml', 'info_manufacturer', 'info_importer', 'info_service', 'tip_1c', 'id_category_1c'], 'string'],
            //[['created_at', 'updated_at'], 'date'],
            [['id_category', 'id_manufacturer', 'id_measurement', 'is_defect', 'is_main', 'is_model', 'is_custom', 'hit', 'published', 'sort',
                'in_status', 'in_action', 'in_new', 'halva'], 'integer'],
            [['price_1c', 'price', 'price_control', 'price_old', 'in_stock'], 'double'],
            [['alias', 'title', 'title_model', 'article', 'code_1c', 'guarantee', 'note', 'seo_title', 'seo_keyword', 'seo_desc','vendor_code'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'alias' => 'Alias',
            'title' => 'Title',
            'title_model' => 'Title Model',
            'desc_mini' => 'Desc Mini',
            'desc_full' => 'Desc Full',
            'desc_yml' => 'Desc Yml',
            'id_category' => 'Категория',
            'id_category_1c' => 'Категория 1С',
            'id_manufacturer' => 'Производитель',
            'id_measurement' => 'Измерение',
            'published' => 'Публикация',
            'created_at' => 'Date Create',
            'updated_at' => 'Date update',
            'article' => 'Артикул',
            'guarantee' => 'Гарантия',
            'code_1c' => 'Код 1c',
            'price_1c' => 'Цена 1c',
            'vendor_code' => 'Заводской артикул',
            'price' => 'Цена',
            'price_control' => 'Контрольная цена',
            'price_old' => 'Цена старая',
            'in_stock' => 'На складе (n)',
            'is_defect' => 'Брак',
            'is_main' => 'Главный',
            'is_model' => 'Модель',
            'is_custom' => 'На заказ',
            'hit' => 'Просмотров',
            'in_status' => 'Статус склада, (3 - экспозиция)',
            'in_action' => 'Акция',
            'in_new' => 'Новый товар',
            'halva' => 'Рассрочка',
            'info_manufacturer' => 'Страна производства',
            'info_importer' => 'info_importer',
            'info_service' => 'info_service',
            'tip_1c' => 'tip_1c',
            'sort' => 'Сортировка',

            'tp_onliner_by_alias',
            'tp_onliner_by_title',
            'tp_onliner_by_url',

            'tp_1k_by_title',
            'tp_1k_by_alias',
            'tp_1k_by_url',

            'tp_market_yandex_by_title',
            'tp_market_yandex_by_alias',
            'tp_market_yandex_by_url',

            'tp_shop_by_title',
            'tp_shop_by_alias',
            'tp_shop_by_url',

            'tp_unishop_by_title',
            'tp_unishop_by_alias',
            'tp_unishop_by_url',

            'note' => 'Примечание',

            'seo_title' => 'Seo Title',
            'seo_keyword' => 'Seo Keyword',
            'seo_desc' => 'Seo Desc',
        ];
    }

    public static function getPublishedArray()
    {
        return [
            self::STATUS_PUBLISHED => 'Опубликован',
            self::STATUS_UNPUBLISHED => 'Не опубликован',
        ];
    }

    public function getCategories()
    {
        return $this->hasOne(Category::className(), ['id' => 'id_category']);
    }

    public static function getCategoriesList()
    {
        $models = Category::find()->asArray()->all();
        return ArrayHelper::map($models, 'id', 'title');
    }

    public static function getChildCategoriesList()
    {
        $models = Category::find()
            ->select('c1.id as id, c1.title as title')
            ->from('{{%catalog_category}} c1')
            ->leftJoin('{{%catalog_category}} c2', 'c1.id = c2.id_parent')
            ->where('c2.id IS NULL')
            ->orderBy('c1.title ASC')
            ->asArray()->all();
        return ArrayHelper::map($models, 'id', 'title');
    }

    public static function getChildCategoriesListComplect()
    {
        $categories = Category::find()
            ->select('c1.id as id, c1.title as title, c3.title as parent')
            ->from('{{%catalog_category}} c1')
//            ->leftJoin('{{%catalog_category}} c2', 'c1.id = c2.id_parent')////////////////
            ->leftJoin('{{%catalog_category}} c3', 'c1.id_parent = c3.id')
//            ->where('c2.id IS NULL')///////////////////
//            ->where('c1.published=1 AND c1.show_in_menu=1')///////////////////
            ->where('c1.show_in_menu=1')///////////////////
            ->orderBy('c1.title ASC')
            ->asArray()->all();


        $result = [];
        foreach($categories as $category) {
            if ($category['title'] == 'Комплектующие') {
                $result[] = [
                    'id' => $category['id'],
                    'title' => $category['parent'].' - '.$category['title'],
                ];
            } else {
                $result[] = [
                    'id' => $category['id'],
                    'title' => $category['title'],
                ];
            }
        }
        array_multisort(array_column($result, 'title'), SORT_ASC, $result);

        return ArrayHelper::map($result, 'id', 'title');
    }

    public function getManufacturers()
    {
        return $this->hasOne(Manufacturer::className(), ['id' => 'id_manufacturer']);
    }

    public static function getManufacturersList()
    {
        $models = Manufacturer::find()->orderBy('title asc')->asArray()->all();
        return ArrayHelper::map($models, 'id', 'title');
    }

    public function getMeasurements()
    {
        return $this->hasOne(Measurement::className(), ['id' => 'id_measurement']);
    }

    public static function getMeasurementsList()
    {
        $models = Measurement::find()->asArray()->all();
        return ArrayHelper::map($models, 'id', 'title');
    }

}
