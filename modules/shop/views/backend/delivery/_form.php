<?php
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model common\modules\shop\models\backend\Delivery */
/* @var $form yii\widgets\ActiveForm */

echo Tabs::widget([
    'items' => [
        [
            'label' => 'Доставка',
            'options' => ['id' => 'tab1'],
            'content' => $this->render('_tab1', [
                'model' => $model,
            ]),
        ],
        [
            'label' => 'Оплаты',
            'visible' => ($model->isNewRecord) ? false : true,
            'options' => ['id' => 'tab2'],
            'content' => $this->render('_tab2', [
                'paymentArray' => $paymentArray,
                'id_delivery' => $id_delivery,
            ]),
        ],
    ],
]);