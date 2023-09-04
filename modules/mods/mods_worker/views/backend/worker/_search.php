<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\mods\mods_worker\models\backend\WorkerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="worker-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'department') ?>

    <?= $form->field($model, 'photo') ?>

    <?= $form->field($model, 'flag1') ?>

    <?= $form->field($model, 'flag2') ?>

    <?php // echo $form->field($model, 'flag3') ?>

    <?php // echo $form->field($model, 'flag4') ?>

    <?php // echo $form->field($model, 'flag5') ?>

    <?php // echo $form->field($model, 'position') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'phone_mobile') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'sort') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
