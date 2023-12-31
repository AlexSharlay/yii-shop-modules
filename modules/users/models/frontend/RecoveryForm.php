<?php

namespace common\modules\users\models\frontend;

use common\modules\users\models\User;
use common\modules\users\Module;
use common\modules\users\traits\ModuleTrait;
use yii\base\Model;
use Yii;

/**
 * Class RecoveryForm
 * @package common\modules\users\models
 * RecoveryForm is the model behind the recovery form.
 *
 * @property string $email E-mail
 */
class RecoveryForm extends Model
{
    use ModuleTrait;

    /**
     * @var string $email E-mail
     */
    public $email;

    /**
     * @var \common\modules\users\models\frontend\User User instance
     */
    private $_model;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // E-mail
            ['email', 'required'],
            ['email', 'trim'],
            ['email', 'string', 'max' => 100],
            [
                'email',
                'exist',
                'targetClass' => User::className(),
                'filter' => function ($query) {
                        $query->active();
                    }
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => 'E-mail'
        ];
    }

    /**
     * Send a recovery password token.
     *
     * @return boolean true if recovery token was successfully sent
     */
    public function recovery()
    {
        $this->_model = User::findByEmail($this->email, 'active');
        if ($this->_model !== null) {
            return $this->send();
        }
        return false;
    }

    /**
     * Send an email confirmation token.
     *
     * @return boolean true if email confirmation token was successfully sent
     */
    public function send()
    {
        return $this->module->mail
            ->compose('recovery', ['model' => $this->_model])
            ->setTo($this->email)
            ->setSubject('Код восстановления пароля.' . ' ' . Yii::$app->name)
            ->send();
    }
}
