<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\fileapi\Widget as FileAPI;
use common\modules\mods\mods_news\models\backend\News;

/* @var $this yii\web\View */
/* @var $model common\modules\mods\mods_news\models\backend\News */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="news-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'col')->textInput() ?>

    <?= $form->field($model, 'row')->textInput() ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ico_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ico_color')->radioList(
        News::colors(),
        [
            'item' => function ($index, $label, $name, $checked, $value) {
                return
                    '<div class="radio"><label>' . Html::radio($name, $checked, ['value' => $value]) . '<span class="label label-' . $label . '">' . $label . '</span></label></div>';
            },
        ]
    );
    ?>

    <?=
    $form->field($model, 'image')->widget(
        FileAPI::className(),
        [
            'settings' => [
                'url' => ['/mods_news/news/fileapi-upload']
            ]
        ]
    )
    ?>

    <?= $form->field($model, 'url_target')->dropDownList(News::target()); ?>

    <?= $form->field($model, 'published')->dropDownList(News::published()); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
