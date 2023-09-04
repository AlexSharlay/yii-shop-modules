<?php
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
?>
<style>
    .log span {
        margin-bottom: 1px;
        text-align: left;
    }
</style>

<div class="row">
    <div class="col-lg-12 log">

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

        <?= $form->field($model, 'xlsxFile')->fileInput(['class'=>'form-control'])->label('Выберите .xlsx файл для импорта/обновления товаров.'); ?>
        <?= Html::submitButton('<i class="icon-file-download position-left"></i>Импортировать', ['class' => 'btn btn-default']) ?>
        <?php ActiveForm::end() ?>
    </div>
</div>

<hr/>

<div class="row">
    <div class="col-lg-3">
        <p>Порядок полей в файле импорта статей:</p>
        <p>'A' - id</p>
        <p>'B' - title</p>
        <p>'C' - mini_desc</p>
        <p>'D' - content</p>
        <p>'E' - img</p>
        <p>'F' - is_active</p>
        <p>'G' - alias</p>
    </div>
</div>
