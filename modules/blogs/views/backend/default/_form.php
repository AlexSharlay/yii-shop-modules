<?php

/**
 * Blog form view.
 *
 * @var \yii\base\View $this View
 * @var \yii\widgets\ActiveForm $form Form
 * @var \common\modules\blogs\models\backend\Blog $model Model
 * @var \backend\widgets\Box $box Box widget instance
 * @var array $statusArray Statuses array
 */

use common\modules\blogs\Module;
use common\components\fileapi\Widget as FileAPI;
use common\components\imperavi\Widget as Imperavi;
use common\modules\blogs\models\backend\Category;
use yii\helpers\ArrayHelper;
use common\components\select2\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin(); ?>
<?php $box->beginBody(); ?>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'title') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'category_id')->dropDownList($model->getCategoriesList()) ?>
            <div class="help-block"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'alias') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'status_id')->dropDownList($statusArray) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'seo_title')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'seo_keyword')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'seo_desc')->textInput(['maxlength' => true]) ?>
        </div>
    </div>


    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'createdAtJui')->widget(
                DatePicker::className(),
                [
                    'options' => [
                        'class' => 'form-control'
                    ],
                    'clientOptions' => [
                        'dateFormat' => 'dd.mm.yy',
                        'changeMonth' => true,
                        'changeYear' => true
                    ]
                ]
            ); ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'updatedAtJui')->widget(
                DatePicker::className(),
                [
                    'options' => [
                        'class' => 'form-control'
                    ],
                    'clientOptions' => [
                        'dateFormat' => 'dd.mm.yy',
                        'changeMonth' => true,
                        'changeYear' => true
                    ]
                ]
            ); ?>

        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'preview_url')->widget(
                FileAPI::className(),
                [
                    'settings' => [
                        'url' => ['/blogs/default/fileapi-upload']
                    ]
                ]
            ) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'image_url')->widget(
                FileAPI::className(),
                [
                    'settings' => [
                        'url' => ['/blogs/default/fileapi-upload']
                    ]
                ]
            ) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'snippet')->widget(
                Imperavi::className(),
                [
                    'settings' => [
                        'minHeight' => 200,
                        'imageGetJson' => Url::to(['/blogs/default/imperavi-get']),
                        'imageUpload' => Url::to(['/blogs/default/imperavi-image-upload']),
                        'fileUpload' => Url::to(['/blogs/default/imperavi-file-upload'])
                    ]
                ]
            ) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?// $form->field($model, 'content')->textarea(); ?>

            <?= $form->field($model, 'content')->widget(
                Imperavi::className(),
                [
                    'settings' => [
                        'replaceDivs' => false,
                        'minHeight' => 300,
                        'imageGetJson' => Url::to(['/blogs/default/imperavi-get']),
                        'imageUpload' => Url::to(['/blogs/default/imperavi-image-upload']),
                        'fileUpload' => Url::to(['/blogs/default/imperavi-file-upload'])
                    ]
                ]
            ) ?>


<!--                <label>Описание товара</label>-->
<!--                <textarea name="body" class='ckeditor form-control' >dddd</textarea>-->

<!--           --><?// $form->field($model, 'text')->widget(CKEditor::className(), [
//            'options' => ['rows' => 6],
//            'preset' => 'basic'
//            ]); ?>


        </div>
    </div>
<?php $box->endBody(); ?><br/>
<?php $box->beginFooter(); ?>
<?= Html::submitButton(
    $model->isNewRecord ? Module::t('blogs', 'BACKEND_CREATE_SUBMIT') : Module::t(
        'blogs',
        'BACKEND_UPDATE_SUBMIT'
    ),
    [
        'class' => $model->isNewRecord ? 'btn btn-primary btn-large' : 'btn btn-success btn-large'
    ]
) ?>
<?php $box->endFooter(); ?>
<?php ActiveForm::end(); ?>