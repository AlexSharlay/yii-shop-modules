<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\mods\mods_news\models\backend\NewsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="news-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'col') ?>

    <?= $form->field($model, 'row') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'ico_title') ?>

    <?php // echo $form->field($model, 'ico_color') ?>

    <?php // echo $form->field($model, 'image') ?>

    <?php // echo $form->field($model, 'url') ?>

    <?php // echo $form->field($model, 'url_target') ?>

    <?php // echo $form->field($model, 'published') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
