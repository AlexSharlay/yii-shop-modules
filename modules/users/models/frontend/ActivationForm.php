<?php

namespace common\modules\users\models\frontend;

use common\modules\users\models\User;
use common\modules\users\Module;
use common\modules\users\traits\ModuleTrait;
use yii\base\Model;
use Yii;

/**
 * Class ActivationForm
 * @package common\modules\users\models
 * ResendForm is the model behind the activation form.
 *
 * @property string $secure_key Activation key
 */
class ActivationForm extends Model
{
    use ModuleTrait;

    /**
     * @var string $token Token
     */
    public $token;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // Secure key
            ['token', 'required'],
            ['token', 'trim'],
            ['token', 'string', 'max' => 53],
            [
                'token',
                'exist',
                'targetClass' => User::className(),
                'filter' => function ($query) {
                        $query->inactive();
                    }
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'token' => 'ATTR_TOKEN'
        ];
    }

    /**
     * Activates user account.
     *
     * @return boolean true if account was successfully activated
     */
    public function activation()
    {
        $model = User::findByToken($this->token, 'inactive');
        if ($model !== null) {
            return $model->activation();
        }
        return false;
    }
}
