<?php

namespace common\modules\users\models\frontend;

use common\modules\users\helpers\Security;
use common\modules\users\models\User;
use common\modules\users\Module;
use common\modules\users\traits\ModuleTrait;
use yii\base\Model;
use Yii;

/**
 * Class RecoveryConfirmationForm
 * @package common\modules\users\models
 * RecoveryConfirmationForm is the model behind the recovery confirmation form.
 *
 * @property string $password Password
 * @property string $repassword Repeat password
 * @property string $token Secure token
 */
class RecoveryConfirmationForm extends Model
{
    use ModuleTrait;

    /**
     * @var string Password
     */
    public $password;

    /**
     * @var string Repeat password
     */
    public $repassword;

    /**
     * @var string Confirmation token
     */
    public $token;

    /**
     * @var \common\modules\users\models\frontend\User User instance
     */
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // Required
            [['password', 'repassword', 'token'], 'required'],
            // Trim
            [['password', 'repassword', 'token'], 'trim'],
            // String
            [['password', 'repassword'], 'string', 'min' => 6, 'max' => 30],
            ['token', 'string', 'max' => 53],
            // Repassword
            ['repassword', 'compare', 'compareAttribute' => 'password'],
            // Secure token
            [
                'token',
                'exist',
                'targetClass' => User::className(),
                'filter' => function ($query) {
                        $query->active();
                    }
            ],
            ['password', 'filter','filter' => function ($str) {
                $chars = str_split($str);
                foreach($chars as $char) {
                    if (ctype_upper($char)) return $str;
                }
                $this->addError('password', 'Пароль должен содержать минимум один заглавный символ.');
            }],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password' => 'Пароль',
            'repassword' => 'Подтвердите пароль'
        ];
    }

    /**
     * Check if token is valid.
     *
     * @return boolean true if token is valid
     */
    public function isValidToken()
    {
        /*
        if (Security::isValidToken($this->token, $this->module->recoveryWithin) === true) {
            return ($this->_user = User::findByToken($this->token, 'active')) !== null;
        }
        return false;
        */

        if (($this->_user = User::findByToken($this->token, 'active')) !== null) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Recover password.
     *
     * @return boolean true if password was successfully recovered
     */
    public function recovery()
    {
        $model = $this->_user;
        if ($model !== null) {
            return $model->recovery($this->password);
        }
        return false;
    }
}
