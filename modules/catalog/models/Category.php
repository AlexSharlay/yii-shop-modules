<?PHP

namespace common\modules\catalog\models;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use common\modules\catalog\traits\ModuleTrait;
use common\components\fileapi\behaviors\UploadBehavior;

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
class Category extends ActiveRecord
{
    use ModuleTrait;

    /** Unpublished status **/
    const STATUS_UNPUBLISHED = 0;
    /** Published status **/
    const STATUS_PUBLISHED = 1;

    const STATUS_UNPARENTFILTER = 0;
    const STATUS_PARENTFILTERALL = 1;
    const STATUS_PARENTFILTERONE = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%catalog_category}}';
    }

    public function behaviors()
    {
        return [
            'uploadBehavior' => [
                'class' => UploadBehavior::className(),
                'attributes' => [
                    'ico' => [
                        'path' => $this->module->categoryPath,
                        'tempPath' => $this->module->categoryTempPath,
                        'url' => $this->module->categoryUrl
                    ],
                ]
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_parent', 'title', 'title_yml', 'desc_top', 'desc_filter', 'desc_bottom', 'desc_filter_bottom', 'desc', 'alias', 'menu_img', 'ico', 'sort', 'published', 'seo_title', 'seo_keyword', 'seo_desc'], 'required'],
            [['id_parent', 'parent_filter', 'sort', 'published', 'use_model', 'hide_filter_after', 'show_in_menu'], 'integer'],
            [['desc', 'desc_top', 'desc_filter', 'desc_bottom', 'desc_filter_bottom'], 'string'],
            [['title', 'alias', 'menu_img', 'ico', 'seo_title', 'seo_keyword', 'seo_desc'], 'string', 'max' => 255]
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
            'menu_img' => 'Картинка в меню',
            'ico' => 'Ico',
            'sort' => 'Sort',
            'use_model' => 'С моделями',
            'hide_filter_after' => 'Показывать полей фильтра',
            'show_in_menu' => 'Видимость в меню',
            'published' => 'Published',
            'seo_title' => 'Seo Title',
            'seo_keyword' => 'Seo Keyword',
            'seo_desc' => 'Seo Desc',
        ];
    }

    public function getCategories()
    {
        return $this->hasOne(Category::className(), ['id' => 'id_parent']);
    }

    public static function getCategoriesList()
    {
        $models = Category::find()->asArray()->all();
        return ArrayHelper::map($models, 'id', 'title');
    }

    public static function getCategoryFilterArray()
    {
        $models = Category::find()->asArray()->all();
        return ArrayHelper::map($models, 'id', 'parent_filter');
    }
}
