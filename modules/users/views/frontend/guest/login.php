<!-- Simple login form -->
<?php

/**
 * Sign In page view.
 *
 * @var \yii\web\View $this View
 * @var \yii\widgets\ActiveForm $form Form
 * @var \common\modules\users\models\LoginForm $model Model
 */

use common\modules\users\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Авторизация';
$this->params['breadcrumbs'] = [
    $this->title
]; ?>
<?php $form = ActiveForm::begin(
    [
        'options' => [
            'class' => 'center'
        ]
    ]
); ?>

<form action="index.html">
    <div class="panel panel-body login-form">
        <div class="text-center">
            <div class="icon-object border-slate-300 text-slate-300"><i class="icon-reading"></i></div>
            <h5 class="content-group">Войти в свой аккаунт <small class="display-block">Введите Ваш логин и пароль</small></h5>
        </div>

        <div class="form-group has-feedback has-feedback-left">
            <?= $form->field($model, 'username')->textInput(['class' => 'form-control', 'placeholder' => 'УНП'])->label(false) ?>
            <div class="form-control-feedback">
                <i class="icon-key text-muted"></i>
            </div>
        </div>

        <div class="form-group has-feedback has-feedback-left">
            <?= $form->field($model, 'password')->passwordInput(['class' => 'form-control', 'placeholder' => 'Пароль'])->label(false) ?>
            <div class="form-control-feedback">
                <i class="icon-lock2 text-muted"></i>
            </div>
        </div>

        <div class="form-group has-feedback has-feedback-left">
            <?= $form->field($model, 'rememberMe')->checkbox() ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Войти <i class="icon-circle-right2 position-right"></i>', ['class' => 'btn btn-primary btn-block']) ?>
        </div>

        <div class="text-center">
            <?= Html::a('Забыли пароль?', '/recovery/') ?>
        </div>
    </div>
</form>

<?php ActiveForm::end(); ?>

<!-- /simple login form -->