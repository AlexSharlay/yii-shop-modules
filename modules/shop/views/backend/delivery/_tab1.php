<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\imperavi\Widget as Imperavi;
use yii\helpers\Url;
?>

<div class="delivery-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?=
    $form->field($model, 'desc')->widget(
        Imperavi::className(),
        [
            'settings' => [
                'imageGetJson' => Url::to(['/shop/delivery/imperavi-get']),
                'imageUpload' => Url::to(['/shop/delivery/imperavi-image-upload']),
                'fileUpload' => Url::to(['/shop/delivery/imperavi-file-upload']),
            ]
        ]
    )
    ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'price_from')->textInput() ?>

    <?= $form->field($model, 'price_to')->textInput() ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>