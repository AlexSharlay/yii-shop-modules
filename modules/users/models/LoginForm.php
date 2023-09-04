<?php

namespace common\modules\users\models;

use
    common\modules\users\Module;
use common\modules\users\traits\ModuleTrait;
use Yii;
use yii\base\Model;

/**
 * Class LoginForm
 * @package module\users\models
 * LoginForm is the model behind the login form.
 *
 * @property string $username Username
 * @property string $password Password
 * @property boolean $rememberMe Remember me
 */
class LoginForm extends Model
{
    use ModuleTrait;

    /**
     * @var string $username Username
     */
    public $username;

    /**
     * @var string $password Password
     */
    public $password;

    /**
     * @var boolean rememberMe Remember me
     */
    public $rememberMe = true;

    /**
     * @var User|boolean User instance
     */
    private $_user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // Required
            [['username', 'password'], 'required'],
            // Password
            ['password', 'validatePassword'],
            // Remember Me
            ['rememberMe', 'boolean']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить меня'
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->$attribute)) {
                $this->addError($attribute, 'Неверный УНП или пароль');
            }
        }
    }

    /**
     * Finds user by username.
     *
     * @return User|boolean User instance
     */
    protected function getUser()
    {
        if ($this->_user === false) {
            $user = User::findByUsername($this->username, 'active');

            // Или по емэилу
            if ($user === null) {
                $user = User::findByEmail($this->username, 'active');
            }

            if ($user !== null) {
                if ($this->module->isBackend) {
                    if (Yii::$app->authManager->checkAccess($user->id, 'accessBackend')) {
                        $this->_user = $user;
                    }
                } else {
                    $this->_user = $user;
                }
            }
        }
        return $this->_user;
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
    }
}
