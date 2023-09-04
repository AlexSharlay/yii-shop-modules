<?php

/**
 * Recovery confirmation page view.
 *
 * @var \yii\web\View $this View
 * @var \yii\widgets\ActiveForm $form Form
 * @var \common\modules\users\models\frontend\RecoveryConfirmationForm $model Model
 */

use common\modules\users\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Восстановление пароля';
$this->params['breadcrumbs'] = [
    $this->title
];
$this->params['contentId'] = 'error'; ?>
<?php $form = ActiveForm::begin(
    [
        'options' => [
            'class' => 'center'
        ]
    ]
); ?>
    <fieldset class="registration-form">
        <?= $form->field($model, 'password')->passwordInput(['placeholder' => $model->getAttributeLabel('password')])->label(false) ?>
        <?= $form->field($model, 'repassword')->passwordInput(['placeholder' => $model->getAttributeLabel('repassword')])->label(false) ?>
        <?= $form->field($model, 'token', ['template' => "{input}\n{error}"])->hiddenInput() ?>
        <?= Html::submitButton(
            'Изменить пароль',
            [
                'class' => 'btn btn-success pull-right'
            ]
        ) ?>
    </fieldset>
<?php ActiveForm::end(); ?>