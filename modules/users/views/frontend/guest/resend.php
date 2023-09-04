<?php

/**
 * Resend page view.
 *
 * @var \yii\web\View $this View
 * @var \yii\widgets\ActiveForm $form Form
 * @var \common\modules\users\models\ResendForm $model Model
 */

use common\modules\users\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Повторная отправка e-mail подтверждения';
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
        <?= $form->field($model, 'email')->textInput(['placeholder' => $model->getAttributeLabel('email')])->label(false) ?>
        <?= Html::submitButton(
            Module::t('users', 'FRONTEND_RESEND_SUBMIT'),
            [
                'class' => 'btn btn-success pull-right'
            ]
        ) ?>
    </fieldset>
<?php ActiveForm::end(); ?>