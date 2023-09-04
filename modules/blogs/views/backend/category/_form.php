<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\fileapi\Widget as FileAPI;
use common\components\imperavi\Widget as Imperavi;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\modules\blogs\models\backend\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

    <?// $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <?=
    $form->field($model, 'content')->widget(
        Imperavi::className(),
        [
            'settings' => [
                'minHeight' => 200,
                'imageGetJson' => Url::to(['/blogs/category/imperavi-get']),
                'imageUpload' => Url::to(['/blogs/category/imperavi-image-upload']),
                'fileUpload' => Url::to(['/blogs/category/imperavi-file-upload'])
            ]
        ]
    )
    ?>

    <?//= $form->field($model, 'image_url')->textInput(['maxlength' => true]) ?>
    <?=
    $form->field($model, 'image_url')->widget(
        FileAPI::className(),
        [
            'settings' => [
                'url' => ['/blogs/category/fileapi-upload']
            ]
        ]
    )
    ?>

    <?= $form->field($model, 'status_id')->dropDownList($statusArray) ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <?= $form->field($model, 'seo_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'seo_keyword')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'seo_desc')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
