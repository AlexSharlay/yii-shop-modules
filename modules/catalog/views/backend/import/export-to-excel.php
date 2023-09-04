<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;


?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

<div class="row">
    <?= $form->field($model, 'columns_name')
        ->label('Выберите нужные столбцы:')
        ->checkboxList(ArrayHelper::toArray($columns_name), [
        'item' => function ($index, $label, $name, $value) {
            return '<div class="col-md-2">' . Html::checkbox($name, true, ['label' => $label, 'value' => $label]) . '</div>';
        }
    ]); ?>
</div>
<div class="clearfix"></div>
<br/>

<hr/>

<div class="row">
    <?= $form->field($model, 'brand_name')
        ->label('Выберите производителей:')
        ->checkboxList(ArrayHelper::map($brand_name,'id','title'), [
            'item' => function ($index, $label, $name, $checked, $value) use ($brand_name){
                $checked = $brand_name[$index]['perekup'];
                return '<div class="col-md-2">' . Html::checkbox($name, $checked, ['label' => $label, 'value' => $value]) . '</div>';
            }
        ]); ?>
</div>
<div class="clearfix"></div>




<!--<div class="row">-->
<!--    --><?//=
//    $form->field($model, 'columns_name')
//        ->label('Выберите нужные столбцы:')
//        ->checkboxList(ArrayHelper::toArray($columns_name));
//    ?>
<!--</div>-->
<br/>
<?= Html::submitButton('<i class="icon-file-upload position-left"></i>Экспорт', ['class' => 'btn btn-default']) ?>
<?php ActiveForm::end() ?>
