<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\components\fileapi\Widget as FileAPI;

/* @var $this yii\web\View */
/* @var $model common\modules\mods\mods_manufacturer\models\backend\Manufacturer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mods_manufacturer-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?=
    $form->field($model, 'ico')->widget(
        FileAPI::className(),
        [
            'settings' => [
                'url' => ['/mods_manufacturer/manufacturer/fileapi-upload']
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
