<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\catalog\models\SectionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_parent') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'title_yml') ?>

    <?= $form->field($model, 'desc') ?>

    <?= $form->field($model, 'alias') ?>

    <?php // echo $form->field($model, 'ico') ?>

    <?php // echo $form->field($model, 'sort') ?>

    <?php // echo $form->field($model, 'use_model') ?>

    <?php // echo $form->field($model, 'hide_filter_after') ?>

    <?php // echo $form->field($model, 'published') ?>

    <?php // echo $form->field($model, 'seo_title') ?>

    <?php // echo $form->field($model, 'seo_keyword') ?>

    <?php // echo $form->field($model, 'seo_desc') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
