<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\catalog\models\ElementSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="element-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'alias') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'title_model_dop') ?>

    <?= $form->field($model, 'title_for_onliner') ?>

    <?php // echo $form->field($model, 'title_for_1k') ?>

    <?php // echo $form->field($model, 'desc_mini') ?>

    <?php // echo $form->field($model, 'desc_full') ?>

    <?php // echo $form->field($model, 'desc_yml') ?>

    <?php // echo $form->field($model, 'img_cover') ?>

    <?php // echo $form->field($model, 'imgs') ?>

    <?php // echo $form->field($model, 'id_category') ?>

    <?php // echo $form->field($model, 'id_manufacturer') ?>

    <?php // echo $form->field($model, 'id_measurement') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'article') ?>

    <?php // echo $form->field($model, 'price_1c') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'price_old') ?>

    <?php // echo $form->field($model, 'in_stock') ?>

    <?php // echo $form->field($model, 'is_defect') ?>

    <?php // echo $form->field($model, 'is_main') ?>

    <?php // echo $form->field($model, 'is_model') ?>

    <?php // echo $form->field($model, 'is_custom') ?>

    <?php // echo $form->field($model, 'hit') ?>

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
