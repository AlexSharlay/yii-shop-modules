<?php

namespace common\modules\users\models;

use common\components\fileapi\behaviors\UploadBehavior;
use common\modules\users\traits\ModuleTrait;
use yii\db\ActiveRecord;
use Yii;

/**
 * Class Profile
 * @package common\modules\users\models
 * User profile model.
 *
 * @property integer $user_id User ID
 * @property string $name Name
 * @property string $patronymic Patronymic
 * @property string $surname Surname
 * @property string $firmName
 * @property string $legal_address
 * @property string $delivery_address
 * @property string $settlement_account
 * @property string $phone_company
 * @property string $phone_director
 * @property integer id_city
 *
 * @property User $user User
 */



class Profile extends ActiveRecord
{
    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%profiles}}';
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
                    'avatar_url' => [
                        'path' => $this->module->avatarPath,
                        'tempPath' => $this->module->avatarsTempPath,
                        'url' => $this->module->avatarUrl
                    ]
                ],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findByUserId($id)
    {
        return static::findOne(['user_id' => $id]);
    }

    /**
     * @return string User full name
     */
    public function getFullName()
    {
        return $this->surname . ' ' . $this->name . ' ' . $this->patronymic;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','surname','patronymic','firmName','ynp','legal_address','delivery_address','settlement_account'], 'trim'],
            [['ynp'], 'unique', 'on' => 'signup'],
            ['id_city', 'integer'],
//            ['settlement_account', 'integer', 'message' => 'Расчетный счёт может содержать только цифры'],
            ['settlement_account', 'string', 'length' => 28, 'message' => 'Расчетный счёт должен содержать 28 символов'],
            ['name', 'match', 'pattern' => '/^[a-zа-яё]+$/iu', 'message' => 'В поле Имя могут быть только буквы'],
            ['patronymic', 'match', 'pattern' => '/^[a-zа-яё]+$/iu', 'message' => 'В поле Отчество могут быть только буквы'],
            ['surname', 'match', 'pattern' => '/^[a-zа-яё]+(-[a-zа-яё]+)?$/iu', 'message' => 'В поле Фамилия могут быть только буквы'],
//            ['ynp', 'integer', 'min' => 100000000, 'max' => 999999999, 'on' => 'user'],
            ['ynp', 'integer', 'min' => 100000000, 'max' => 999999999],


            // Регистрация пользователя фронт
            [['firmName','ynp'], 'required', 'on' => 'signup'],

            // Изменение профиля фронт
            [['ynp', 'name','surname','patronymic','firmName','phone_company','phone_director','id_city'], 'required', 'on' => 'update'],


            // Регистрация админом
            [['name','surname','patronymic'], 'required', 'on' => 'signup_manager'],


        ];
    }

    public function scenarios()
    {
        return [
            'signup' => ['firmName','ynp'],
            'update' => ['email', 'ynp', 'name','surname','patronymic','firmName','legal_address','settlement_account','phone_company','phone_director','id_city'],
            'signup_manager' => ['name','surname','patronymic'],
            'update_manager' => ['email', 'ynp', 'name','surname','patronymic','legal_address','settlement_account','avatar_url'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'patronymic' => 'Отчество',
            'firmName' => 'Наименование компании',
            'ynp' => 'УНП',
            'legal_address' => 'Юридический адрес',
            'delivery_address' => 'Адреса доставок',
            'settlement_account' => 'Расчётный счёт',
            'phone_company' => 'Телефон компании',
            'phone_director' => 'Телефон директора',
            'id_city' => 'Город',
        ];
    }

    /**
     * @return Profile|null Profile user
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->inverseOf('profile');
    }
}
