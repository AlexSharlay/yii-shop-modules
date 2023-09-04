<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use \common\modules\mods\mods_review\models\backend\Review;

/* @var $this yii\web\View */
/* @var $model common\modules\mods\mods_review\models\backend\Review */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="review-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'mark')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'desc')->textarea(['maxlength' => false]) ?>

    <?= $form->field($model, 'date')->widget(
        DatePicker::className(),
        [
            'options' => [
                'class' => 'form-control'
            ],
            'clientOptions' => [
                //'dateFormat' => 'dd-MM-yyyy',
                'changeMonth' => true,
                'changeYear' => true
            ]
        ]
    ); ?>

    <?= $form->field($model, 'published')->dropDownList(Review::published()); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
