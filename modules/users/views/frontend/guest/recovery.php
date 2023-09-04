<?php

/**
 * Recovery password page view.
 *
 * @var \yii\web\View $this View
 * @var \yii\widgets\ActiveForm $form Form
 * @var \common\modules\users\models\frontend\RecoveryForm $model Model
 */

use common\modules\users\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Восстановить пароль';
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
            'Восстановить пароль',
            [
                'class' => 'btn btn-success pull-right'
            ]
        ) ?>
    </fieldset>
<?php ActiveForm::end(); ?>