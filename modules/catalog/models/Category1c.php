<?PHP

namespace common\modules\catalog\models;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use common\modules\catalog\traits\ModuleTrait;
use common\components\fileapi\behaviors\UploadBehavior;

/**
 * This is the model class for table "{{%catalog_category1c}}".
 *
 * @property integer $id
 * @property string $id_code_1c
 * @property integer $id_parent
 * @property string $title
 */
class Category1c extends ActiveRecord
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
        return '{{%catalog_category1c}}';
    }

    public function behaviors()
    {
        return [
//            'uploadBehavior' => [
//                'class' => UploadBehavior::className(),
//                'attributes' => [
//                    'ico' => [
//                        'path' => $this->module->categoryPath,
//                        'tempPath' => $this->module->categoryTempPath,
//                        'url' => $this->module->categoryUrl
//                    ],
//                ]
//            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_code_1c', 'id_parent', 'title'], 'required'],
            [['id_parent','id'], 'integer'],
            [['id_code_1c'], 'string', 'max' => 10],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_code_1c' => 'Код категории в 1С',
            'id_parent' => 'Родительская категория',
            'title' => 'Заголовок',
        ];
    }

//    public function getCategories()
//    {
//        return $this->hasOne(Category1c::className(), ['id' => 'id_parent']);
//    }
//
//    public static function getCategoriesList()
//    {
//        $models = Category1c::find()->asArray()->all();
//        return ArrayHelper::map($models, 'id', 'title');
//    }

}
