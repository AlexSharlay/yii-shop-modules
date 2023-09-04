<?php

namespace common\modules\users\models\frontend;

use common\modules\users\components\validators\PasswordValidator;
use common\modules\users\Module;
use Yii;

/**
 * Class User
 * @package common\modules\users\models\frontend
 * User is the model behind the signup form.
 *
 * @property string $username Username
 * @property string $email E-mail
 * @property string $password Password
 * @property string $repassword Repeat password
 *
 * @property Profile $profile Profile
 */
class User extends \common\modules\users\models\User
{
    /**
     * @var string $password Password
     */
    public $password;

    /**
     * @var string $repassword Repeat password
     */
    public $repassword;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            // Required
            [['email', 'password', 'repassword'], 'required'],

            // Trim
            [['email', 'password', 'repassword'], 'trim'],

            // String
            [['password', 'repassword'], 'string', 'min' => 6, 'max' => 30],

            // Unique
            [['email'], 'unique', 'on'=>'signup'],

            // Username
            //['username', 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/'],
            //['username', 'string', 'min' => 3, 'max' => 30],

            // E-mail
            ['email', 'string', 'max' => 100],
            ['email', 'email'],

            // Repassword
            ['repassword', 'compare', 'compareAttribute' => 'password'],

            ['password', PasswordValidator::className()],

        ];
    }


    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            'signup' => ['username', 'email', 'password', 'repassword'],
            'signup_manager' => ['username', 'password', 'repassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();

        return array_merge($labels, [
            'password' => 'Пароль',
            'repassword' => 'Пароль (повтор)',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->setPassword($this->password);
            }
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            if ($this->profile !== null) {
                $this->profile->save(false);
            }

            $auth = Yii::$app->authManager;
            $role = $auth->getRole(self::ROLE_DEFAULT);
            $auth->assign($role, $this->id);

            if ($this->module->requireEmailConfirmation === true) {
                $this->send();
            }
        }
    }

    /**
     * Send an email confirmation token.
     *
     * @return boolean true if email was sent successfully
     */
    public function send()
    {
        return $this->module->mail
                    ->compose('signup', ['model' => $this])
                    ->setTo($this->email)
                    ->setSubject('Код подтверждения новой учётной записи.' . ' ' . Yii::$app->name)
                    ->send();
    }
}
