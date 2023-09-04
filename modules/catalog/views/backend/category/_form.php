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

    <? // $form->field($model, 'id_parent')->dropDownList($parentArray, ['prompt' => '']) ?>

    <div>
        <? // $form->field($model, 'id_parent') ?>
        <?= $form->field($model, 'id_parent')->widget(Select::className(), [
            'options' => [
                'prompt' => '',
            ],
            'settings' => [
                'width' => '100%',
            ],
            'items' => $parentArray
        ]) ?>
    </div>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title_yml')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'parent_filter')->dropDownList($parentFilterArray) ?>

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

    <?=
    $form->field($model, 'desc_top')->widget(
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

    <?=
    $form->field($model, 'desc_filter')->widget(
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

    <?=
    $form->field($model, 'desc_bottom')->widget(
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

    <?=
    $form->field($model, 'desc_filter_bottom')->widget(
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

    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

    <?=
    $form->field($model, 'ico')->widget(
        FileAPI::className(),
        [
            'settings' => [
                'url' => ['/catalog/category/fileapi-upload']
            ]
        ]
    )
    ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <?= $form->field($model, 'use_model')->checkbox() ?>

    <?= $form->field($model, 'hide_filter_after')->textInput() ?>

    <?= $form->field($model, 'published')->dropDownList($publishedArray) ?>

    <?= $form->field($model, 'show_in_menu')->dropDownList($showInMenuArray) ?>

    <?= $form->field($model, 'seo_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'seo_keyword')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'seo_desc')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
