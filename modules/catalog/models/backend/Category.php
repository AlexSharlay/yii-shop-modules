<?php

namespace common\modules\catalog\models\backend;

use Yii;

/**
 * This is the model class for table "{{%catalog_category}}".
 *
 * @property integer $id
 * @property integer $id_parent
 * @property integer $parent_filter
 * @property string $title
 * @property string $title_yml
 * @property string $desc_top
 * @property string $desc_filter
 * @property string $desc_bottom
 * @property string $desc_filter_bottom
 * @property string $desc
 * @property string $alias
 * @property string $menu_img
 * @property string $ico
 * @property integer $sort
 * @property integer $use_model
 * @property integer $hide_filter_after
 * @property integer $show_in_menu
 * @property integer $published
 * @property string $seo_title
 * @property string $seo_keyword
 * @property string $seo_desc
 * @property string $facets
 */
class Category extends \common\modules\catalog\models\Category
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%catalog_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'title_yml', 'alias', 'sort', 'published'], 'required'],
            [['id_parent', 'parent_filter', 'sort', 'published', 'use_model', 'hide_filter_after', 'show_in_menu'], 'integer'],
//            [['alias'], 'unique'],
            [['desc', 'desc_top', 'desc_filter', 'desc_bottom', 'desc_filter_bottom'], 'string'],
            [['title', 'title_yml', 'alias', 'menu_img', 'ico', 'seo_title', 'seo_keyword', 'seo_desc'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_parent' => 'Родительская категория',
            'parent_filter' => 'При выборе в меню родительской категории',
            'title' => 'Заголовок',
            'title_yml' => 'Заголовок для yml',
            'desc' => 'Описание',
            'desc_top' => 'Описание категории сверху страницы',
            'desc_filter' => 'Описание статических фильтров',
            'desc_bottom' => 'Описание категории снизу страницы',
            'desc_filter_bottom' => 'Описание фильтров снизу страницы',
            'alias' => 'Alias',
            'menu_img ' => 'Картинка в меню',
            'ico' => 'Ico',
            'sort' => 'Sort',
            'use_model' => 'С моделями',
            'hide_filter_after' => 'Показывать полей фильтра',
            'show_in_menu' => 'Видимость в меню',
            'published' => 'Публикация',
            'seo_title' => 'Seo Title',
            'seo_keyword' => 'Seo Keyword',
            'seo_desc' => 'Seo Desc',
        ];
    }

    public static function getPublishedArray()
    {
        return [
            self::STATUS_PUBLISHED => 'Опубликован',
            self::STATUS_UNPUBLISHED => 'Не опубликован'
        ];
    }

    public static function getShowInMenuArray()
    {
        return [
            self::STATUS_UNPUBLISHED => 'Скрыт в меню',
            self::STATUS_PUBLISHED => 'Виден в меню'
        ];
    }

    public static function getParentFilterArray()
    {
        return [
            self::STATUS_UNPARENTFILTER => 'Не выводить товары категории в родительской категории',
            self::STATUS_PARENTFILTERALL => 'Показать товары категории, применить фильтр родительской категории',
            self::STATUS_PARENTFILTERONE => 'Показать товары категории, применить фильтр данной категории'
        ];
    }
}
