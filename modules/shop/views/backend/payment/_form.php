<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\imperavi\Widget as Imperavi;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\modules\shop\models\backend\Payment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?=
    $form->field($model, 'desc')->widget(
        Imperavi::className(),
        [
            'settings' => [
                'imageGetJson' => Url::to(['/catalog/category/imperavi-get']),
                'imageUpload' => Url::to(['/catalog/category/imperavi-image-upload']),
                'fileUpload' => Url::to(['/catalog/category/imperavi-file-upload']),
            ]
        ]
    )
    ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
