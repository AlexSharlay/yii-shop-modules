<?php
$bundle = \frontend\themes\shop\pageAssets\user\update::register($this);

/**
 * Update profile page view.
 *
 * @var \yii\web\View $this View
 * @var \yii\widgets\ActiveForm $form Form
 * @var \common\modules\users\models\frontend\User $model Model
 */

//use common\components\fileapi\Widget;
//use common\modules\users\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

//use kartik\alert\Alert;
//use yii\helpers\ArrayHelper;
use common\modules\shop\models\UserCity;
use common\modules\users\models\frontend\User;

$this->title = 'Редактирование профиля';
$this->params['breadcrumbs'] = [
    'Редактирование профиля',
    $this->title
];
$this->params['contentId'] = 'error'; ?>
<?php $form = ActiveForm::begin(['options' => ['class' => 'center']]); ?>

    <fieldset class="registration-form">

        <?php
        if (empty($model->surname) || empty($model->patronymic) || empty($model->name) || empty($model->phone_director) || empty($model->phone_company) ||
            empty($model->firmName) || empty($model->legal_address) || empty($model->settlement_account)) {
            ?>
            <div id="w2-warning" class="alert-warning alert fade in">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                Для корректного выставления счетов просим Вас заполнить и проверить следующие данные. После заполнения данных, как только Вас проверит модератор, Вы сможете оформлять заказы.
            </div>
            <?
        } else {
            if ($status == 0) {
                ?>
                <div id="w2-warning" class="alert-warning alert fade in">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    Оформление заказов будет доступно сразу после проверки Вашего аккаунта модератором.
                </div>
                <?
            }
        }
        ?>

        <?
        $attr = [
            'readOnly'=>true,
            'disabled'=>'disabled',
            'style' => 'background-color: #f3f3f3;'
        ];
        ?>
        <div class="row">
            <div class="row col-lg-10 col-md-10 col-sm-9">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <?= $form->field($model, 'surname')->textInput()->label(null, ['style'=>' font-weight:600;']) ?>
                    <?= $form->field($model, 'name')->textInput()->label(null, ['style'=>' font-weight:600;']) ?>
                    <?= $form->field($model, 'patronymic')->textInput()->label(null, ['style'=>' font-weight:600;']) ?>
                    <?= $form->field($model, 'phone_director')->textInput(['id' => 'phone1'])->label(null, ['style'=>' font-weight:600;']) ?>
                    <?= $form->field($model, 'phone_company')->textInput(['id' => 'phone2'])->label(null, ['style'=>' font-weight:600;']) ?>
                    <?= $form->field($model, 'id_city', ['inputOptions' => ['class' => 'selectpicker ']])
                        ->dropDownList(UserCity::getCities(), ['prompt' => '- Город доставки -', 'class'=>'form-control required'])->label(null, ['style'=>' font-weight:600;']); ?>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">

                    <?= $form->field($model, 'ynp')                                 ->textInput($attr)               ->label(null, ['style'=>' font-weight:600;']) ?>
                    <?= $form->field($model, 'firmName')                            ->textInput($attr)          ->label(null, ['style'=>' font-weight:600;']) ?>
                    <?= $form->field(User::findIdentity($model->user_id), 'email')  ->textInput($attr)             ->label(null, ['style'=>' font-weight:600;']); ?>
                    <?= $form->field($model, 'legal_address')                       ->textInput($attr)     ->label(null, ['style'=>' font-weight:600;']) ?>
                    <?= $form->field($model, 'settlement_account')                  ->textInput($attr)->label(null, ['style'=>' font-weight:600;']) ?>


                    <label>Изменение данных полей произойдёт после проверки администратором</label>
                    <?= Html::submitButton('Открыть поля для редактирования', [
                        'class' => 'btn btn-default',
                        'style' => 'width:100%;margin-top: 0px;',
                        'id' => 'updateProtectFields'
                    ]) ?>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="form-group">
                        <label class="control-label" for="">&nbsp;</label><br/>
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-default btn-upd', 'style' => 'width:100%;font-size:15px;font-weight:600;']) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-3">
               <?= $this->render('_menu') ?>
            </div>
        </div>
    </fieldset>

<?php ActiveForm::end(); ?>

<style>
    .has-success .control-label {
        color: #333333;
    }
    .form-control {
        border: 2px solid #ddd;
    }

    .has-success .form-control {
        border-color: #5dd662;
    }
</style>

<? /*
<fieldset class="registration-form">
        <?= $form->field($model, 'name')->textInput(['placeholder' => $model->getAttributeLabel('name')])->label(
    false
) ?>
<?= $form->field($model, 'surname')->textInput(['placeholder' => $model->getAttributeLabel('surname')])->label(
    false
) ?>
<?=
$form->field($model, 'avatar_url')->widget(
    Widget::className(),
    [
        'settings' => [
            'url' => ['fileapi-upload']
        ],
        'crop' => true,
        'cropResizeWidth' => 100,
        'cropResizeHeight' => 100
    ]
)->label(false) ?>
<?= Html::submitButton(
    'Обновить',
    [
        'class' => 'btn btn-primary pull-right'
    ]
) ?>
</fieldset>
*/ ?>