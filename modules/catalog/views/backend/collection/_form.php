<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\components\imperavi\Widget as Imperavi;
use common\components\select2\Widget as Select;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\modules\catalog\models\backend\Collection */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="collection-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
