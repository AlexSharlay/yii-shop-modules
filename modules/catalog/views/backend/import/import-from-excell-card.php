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



        <?
        if (count($results) > 0) {
            foreach ($results as $result) {
                $type = $result['type'];
                $msg = $result['msg'];
                if ($type == 'error') {
                    foreach ($msg['errors'] as $e) {
                        foreach ($e as $m) {
                            echo '<span class="label label-flat label-block border-danger text-danger-600">Ошибка: ' . $m . '</span>';
                        }
                    }
                    /*
                    echo '<pre>';
                    print_r($msg);
                    echo '</pre>';
                    */
                } else {
                    echo '<span class="label label-flat label-block border-' . $type . ' text-' . $type . '-600">' . $msg . '</span>';
                }
            }
            echo '<br/>';
        }
        ?>




        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

        <?= $form->field($model, 'xlsxFile')->fileInput(['class'=>'form-control'])->label('Выберите .xlsx файл для импорта номеров бонусных карт.'); ?>
        <?= Html::submitButton('<i class="icon-file-download position-left"></i>Импортировать', ['class' => 'btn btn-default']) ?>
        <?php ActiveForm::end() ?>
    </div>
</div>

<hr/>

<div class="row">
    <div class="col-lg-3">
        <p>Порядок полей в файле импорта номеров бонусных карт:</p>
        <p>'A' -> 13-значный номер карты</p>
    </div>
</div>
