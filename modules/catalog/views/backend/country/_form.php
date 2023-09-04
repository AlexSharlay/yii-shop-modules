<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\components\fileapi\Widget as FileAPI;

/* @var $this yii\web\View */
/* @var $model common\modules\catalog\models\, 'use_model', 'hide_filter_after' */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?=
    $form->field($model, 'ico')->widget(
        FileAPI::className(),
        [
            'settings' => [
                'url' => ['/catalog/country/fileapi-upload']
            ]
        ]
    )
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
