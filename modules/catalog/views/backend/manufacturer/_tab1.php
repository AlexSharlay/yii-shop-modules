<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\components\fileapi\Widget as FileAPI;
use common\components\imperavi\Widget as Imperavi;
use common\components\select2\Widget as Select;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\modules\catalog\models\, 'use_model', 'hide_filter_after' */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?=
    $form->field($model, 'desc')->widget(
        Imperavi::className(),
        [
            'settings' => [
                'imageGetJson' => Url::to(['/catalog/manufacturer/imperavi-get']),
                'imageUpload' => Url::to(['/catalog/manufacturer/imperavi-image-upload']),
                'fileUpload' => Url::to(['/catalog/manufacturer/imperavi-file-upload']),
            ]
        ]
    )
    ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'perekup')->checkbox() ?>

    <?=
    $form->field($model, 'ico')->widget(
        FileAPI::className(),
        [
            'settings' => [
                'url' => ['/catalog/manufacturer/fileapi-upload']
            ]
        ]
    )
    ?>

    <?= $form->field($model, 'seo_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'seo_keyword')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'seo_desc')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
