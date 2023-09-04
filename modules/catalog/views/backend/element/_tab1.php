<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\bootstrap\Tabs;

use common\components\imperavi\Widget as Imperavi;
use common\components\select2\Widget as Select;

use kartik\file\FileInput;

$bundle = \backend\themes\shop\pageAssets\catalog\element\form::register($this);

?>

<div class="element-form">

    <?php if(!empty($parent)): ?>
        <p>Id родительской модели: <?= $parent ?></p>
    <?php endif; ?>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?=
    $form->field($model, 'desc_mini')->widget(
        Imperavi::className(),
        [
            'settings' => [
                'imageGetJson' => Url::to(['/catalog/element/imperavi-get']),
                'imageUpload' => Url::to(['/catalog/element/imperavi-image-upload']),
                'fileUpload' => Url::to(['/catalog/element/imperavi-file-upload']),
            ]
        ]
    )
    ?>

    <?=
    $form->field($model, 'desc_full')->widget(
        Imperavi::className(),
        [
            'settings' => [
                'imageGetJson' => Url::to(['/catalog/element/imperavi-get']),
                'imageUpload' => Url::to(['/catalog/element/imperavi-image-upload']),
                'fileUpload' => Url::to(['/catalog/element/imperavi-file-upload']),
            ]
        ]
    )
    ?>

    <?= $form->field($model, 'title_model')->textInput(['maxlength' => true]) ?>

    <?=
    $form->field($model, 'desc_yml')->widget(
        Imperavi::className(),
        [
            'settings' => [
                'imageGetJson' => Url::to(['/catalog/element/imperavi-get']),
                'imageUpload' => Url::to(['/catalog/element/imperavi-image-upload']),
                'fileUpload' => Url::to(['/catalog/element/imperavi-file-upload']),
            ]
        ]
    )
    ?>

    <? if (count($photos)>0) : ?>
        <div class="upfile-preview-thumbnails">
            <? foreach ($photos as $photo) : ?>
                <div class="upfile-preview-frame" idphoto="<?=$photo->id;?>">
                    <img src="/statics/catalog/photo/images/<?=$photo->name;?>" class="upfile-preview-image" style="width:auto;height:160px;">
                    <div class="upfile-thumbnail-footer">
                        <div class="upfile-actions">
                            <div class="upfile-footer-buttons">
                                <button type="button" class="photo-cover btn btn-xs btn-default" title="Сделать обложкой"><i class="glyphicon <? if ($photo->is_cover) echo 'glyphicon-star text-success'; else echo 'glyphicon-star-empty text-warning'?>"></i></button>
                                <button type="button" class="photo-remove btn btn-xs btn-default" title="Удалить файл"><i class="glyphicon glyphicon-trash text-danger"></i></button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            <? endforeach; ?>
        </div>
    <? endif; ?>

    <?=
    FileInput::widget([
        'model' => $modelPhoto,
        'attribute' => 'file[]',
        'options' => [
            'multiple'=>true,
            'accept' => 'image/*',
        ],
        'pluginOptions' => [
            'showRemove' => true,
            'showUpload' => false,
            'allowedFileExtensions' => ['jpg','jpeg','png'],
            'overwriteInitial' => false,
            'removeClass' => 'btn btn-danger',
            'removeIcon' => '<i class="glyphicon glyphicon-trash"></i> ',
            'previewFileType' => 'any',
            //'uploadUrl' => '#'
        ],

    ]);
    ?>


    <?= $form->field($model, 'id_category')->dropDownList(
        \common\modules\catalog\models\backend\Element::getChildCategoriesListComplect(), array('prompt'=>'- Выбрать -')) ?>

    <?= $form->field($model, 'id_manufacturer')->dropDownList($manufacturerArray, array('prompt'=>'- Выбрать -')) ?>

    <?= $form->field($model, 'info_manufacturer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_measurement')->dropDownList($measurementArray) ?>

    <?= $form->field($model, 'published')->dropDownList($publishedArray) ?>

    <?// $form->field($model, 'created_at')->textInput(['maxlength' => true]) ?>

    <?// $form->field($model, 'updated_at')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'article')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'vendor_code')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'code_1c')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'guarantee')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price_1c')->textInput() ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'price_old')->textInput() ?>

    <?= $form->field($model, 'in_stock')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_defect')->checkbox() ?>

    <?= $form->field($model, 'is_main')->checkbox() ?>

    <?= $form->field($model, 'is_model')->checkbox() ?>

    <?= $form->field($model, 'is_custom')->checkbox() ?>

    <?= $form->field($model, 'in_action')->checkbox() ?>
    <?= $form->field($model, 'in_new')->checkbox() ?>


    <?= $form->field($model, 'sort')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'seo_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'seo_keyword')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'seo_desc')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'note')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>