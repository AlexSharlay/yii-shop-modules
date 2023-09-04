<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\fileapi\Widget as FileAPI;
use common\modules\mods\mods_slides\models\backend\Slides;

/* @var $this yii\web\View */
/* @var $model common\modules\mods\mods_slides\models\backend\Slides */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="slides-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<!--    --><?//= $form->field($model, 'img')->textInput(['maxlength' => true]) ?>
    <?=
    $form->field($model, 'img')->widget(
        FileAPI::className(),
        [
            'settings' => [
                'url' => ['/mods_slides/slides/fileapi-upload']
            ]
        ]
    )
    ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <?= $form->field($model, 'published')->dropDownList(Slides::published()); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
