<?php

namespace common\modules\mods\mods_reviews\models;

use common\modules\catalog\models\backend\Element;
use common\modules\mods\mods_reviews\traits\ModuleTrait;
use common\modules\users\models\backend\User;
use common\modules\users\models\Profile;
use Yii;

/**
 * This is the model class for table "{{%review}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property string $advantage
 * @property string $disadvantages
 * @property integer $vote_up
 * @property integer $vote_down
 * @property integer $rating
 * @property integer $published
 * @property string $created_at
 * @property integer $catalog_element_id
 * @property integer $user_id
 *
 * @property Element $element
 * @property User $user
 */
class Review extends \yii\db\ActiveRecord
{

    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mods_reviews}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'text', 'advantage', 'disadvantages', 'created_at', 'catalog_element_id', 'user_id'], 'required'],
            [['text', 'advantage', 'disadvantages'], 'string'],
            [['vote_up', 'vote_down', 'rating', 'published', 'catalog_element_id', 'user_id'], 'integer'],
            [['created_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['catalog_element_id'], 'exist', 'skipOnError' => true, 'targetClass' => Element::className(), 'targetAttribute' => ['catalog_element_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\modules\users\models\backend\User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'text' => 'Текст отзыва',
            'advantage' => 'Достоинства',
            'disadvantages' => 'Недостатки',
            'vote_up' => '+',
            'vote_down' => '-',
            'rating' => 'Оценка',
            'published' => 'Опубликован',
            'created_at' => 'Дата отзыва',
            'catalog_element_id' => 'Товар',
            'user_id' => 'Пользователь',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElement()
    {
        return $this->hasOne(Element::className(), ['id' => 'catalog_element_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'user_id']);
    }

    public function getFullName(){

        return $this->profile->name." ".$this->profile->patronymic." ".$this->profile->surname;

    }

    /**
     * @inheritdoc
     * @return ReviewQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ReviewQuery(get_called_class());
    }

    public static function published() {
        return [
            '1' => 'Опубликовано',
            '0' => 'Не опубликовано',
        ];
    }
}
