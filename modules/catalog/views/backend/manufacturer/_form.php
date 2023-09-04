<?php
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model common\modules\shop\models\backend\Delivery */
/* @var $form yii\widgets\ActiveForm */

echo Tabs::widget([
    'items' => [
        [
            'label' => 'Производитель',
            'options' => ['id' => 'tab1'],
            'content' => $this->render('_tab1', [
                'model' => $model,
            ]),
        ],
        [
            'label' => 'Страны',
            'visible' => ($model->isNewRecord) ? false : true,
            'options' => ['id' => 'tab2'],
            'content' => $this->render('_tab2', [
                'countryArray' => $countryArray,
                'id_manufacturer' => $id_manufacturer,
            ]),
        ],
    ],
]);