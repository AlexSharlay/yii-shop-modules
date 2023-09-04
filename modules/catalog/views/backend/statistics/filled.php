<?
$bundle = \backend\themes\shop\pageAssets\catalog\statistics\filled::register($this);

use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<div class="row">
    <div class="col-lg-12">

        <?php $form = ActiveForm::begin(['method' => 'get']) ?>
        <?= $form->field($model, 'date_from')->textInput(['value' => '2016-03-23']); ?>
        <?= $form->field($model, 'date_to')->textInput(['value' => date('Y-m-d')]); ?>
        <?= Html::submitButton('Просмотр', ['class' => 'btn btn-default']) ?>
        <?php ActiveForm::end() ?>

    </div>
</div>

<? if ($points) { ?>

<script>
    var google_area = [
        ['Дата', 'Всего', 'С фото', 'Заполнено частично', 'Заполнено полностью'],
        <?=$points;?>
    ];
</script>

<div class="panel-body">
    <div class="chart-container">

        <div class="panel panel-flat">
            <div class="panel-body">
                <div class="chart-container">
                    <div class="chart" id="google-area"></div>
                </div>
            </div>
        </div>

    </div>
</div>

<? } ?>