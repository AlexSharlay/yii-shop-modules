<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use common\components\imperavi\Widget as Imperavi;
use yii\jui\Selectable;
use common\modules\mods\mods_reviews\models\Review;

/* @var $this yii\web\View */
/* @var $model common\modules\mods\mods_reviews\models\Review */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="review-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'text')->widget(Imperavi::className()); ?>

    <?= $form->field($model, 'advantage')->widget(Imperavi::className()); ?>

    <?= $form->field($model, 'disadvantages')->widget(Imperavi::className()); ?>

<!--    <?/*= $form->field($model, 'vote_up')->textInput() */?>

    --><?/*= $form->field($model, 'vote_down')->textInput() */?>

    <?= $form->field($model, 'rating')->textInput() ?>

    <?= $form->field($model, 'published')->dropDownList(Review::published()) ?>

    <?= $form->field($model, 'created_at')->widget(DatePicker::className(),[
        'model'=>$model,
        'attribute'=>'created_at',
        'dateFormat'=>'yyyy-MM-dd',
        'options'=>[
            'class'=>'form-control'
        ]
    ]) ?>

    <?= $form->field($model, 'catalog_element_id')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
