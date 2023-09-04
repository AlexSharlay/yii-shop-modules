<?php

namespace common\modules\users\components\validators;

use yii\validators\Validator;

class PasswordValidator extends Validator {

    public function init() {
        parent::init();
    }

    public function validateAttribute($model, $attribute)
    {

    }

    public function clientValidateAttribute($model, $attribute, $view) {
        return <<<JS
            var password = $('#user-password').val();
            var rePassword = $('#user-repassword').val();
            if (password.toLowerCase() == password) {
                messages.push('Пароль должен содержать минимум один заглавный символ.');
            }
            if (password != rePassword) {
                messages.push('Пароли не совпадают.');
            }

JS;
    }
}