<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\modules\users\models\backend\User;

/* @var $this yii\web\View */
/* @var $model common\modules\shop\models\backend\ClientManager */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="client-manager-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_manager')->dropDownList(User::getMangersList()); ?>

    <?= $form->field($model, 'id_client')->dropDownList(User::getClientsList()) ?>

    <?= $form->field($model, 'active')->dropDownList([
        '1' => 'Да',
        '0' => 'Нет',
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
