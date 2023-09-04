<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\components\fileapi\Widget as FileAPI;

/* @var $this yii\web\View */
/* @var $model common\modules\mods\mods_worker\models\backend\Worker */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="worker-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'department')->dropDownList([
        '1' => 'Отдел продаж',
        '2' => 'Отдел закупок',
    ]); ?>

    <?= $form->field($model, 'fio')->textInput(['maxlength' => true]) ?>

    <?=
    $form->field($model, 'photo')->widget(
        FileAPI::className(),
        [
            'settings' => [
                'url' => ['/mods_worker/worker/fileapi-upload']
            ]
        ]
    )
    ?>

    <?= $form->field($model, 'position')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone_mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <?= $form->field($model, 'flag1')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'flag2')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'flag3')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'flag4')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'flag5')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
