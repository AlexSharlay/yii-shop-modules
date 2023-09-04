<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\imperavi\Widget as Imperavi;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\modules\mods\mods_job\models\backend\Job */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="job-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'department')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vacancy')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'salary')->textInput(['maxlength' => true]) ?>

    <?=
    $form->field($model, 'content')->widget(
        Imperavi::className(),
        [
            'settings' => [
                'imageGetJson' => Url::to(['/mods_job/job/imperavi-get']),
                'imageUpload' => Url::to(['/mods_job/job/imperavi-image-upload']),
                'fileUpload' => Url::to(['/mods_job/job/imperavi-file-upload']),
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
