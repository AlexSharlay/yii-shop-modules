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

    <?= $form->field($model, 'region')->dropDownList([ 'Брестская область' => 'Брестская область', 'Витебская область' => 'Витебская область', 'Гомельская область' => 'Гомельская область', 'Гродненская область' => 'Гродненская область', 'Минская область' => 'Минская область', 'Могилевская область' => 'Могилевская область', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'day')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
