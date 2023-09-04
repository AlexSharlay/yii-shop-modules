<?php

use backend\widgets\Box;
use backend\widgets\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;


$this->title = 'Оплаты';
$this->params['subtitle'] = 'Оплаты';
$this->params['breadcrumbs'] = [
    $this->title
];
$gridId = 'payment-grid';
$gridConfig = [
    'id' => $gridId,
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        //['class' => CheckboxColumn::classname()],
        [
            'attribute' => 'id',
            'headerOptions' => [
                'style' => [
                    'width' => '20px'
                ]
            ],
        ],
        'title',
        [
            'attribute' => 'desc',
            'format' => 'html',
        ],
        'sort',
    ]
];

$boxButtons = $actions = [];
$showActions = false;

if (Yii::$app->user->can('BCreateShopPayment')) {
    $boxButtons[] = '{create}';
}

if (Yii::$app->user->can('BUpdateShopPayment')) {
    $actions[] = '{update}';
    $showActions = $showActions || true;
}

if (Yii::$app->user->can('BDeleteShopPayment')) {
    $actions[] = '{delete}';
    $showActions = $showActions || true;
}

if ($showActions === true) {
    $gridConfig['columns'][] = [
        'class' => ActionColumn::className(),
        'template' => '{view} '.implode(' ', $actions)
    ];
}
$boxButtons = !empty($boxButtons) ? implode(' ', $boxButtons) : null; ?>

<div class="row">
    <div class="col-xs-12">
        <?php Box::begin(
            [
                'title' => $this->params['subtitle'],
                'buttonsTemplate' => $boxButtons,
                'grid' => $gridId
            ]
        ); ?>
        <?= GridView::widget($gridConfig); ?>
        <?php Box::end(); ?>
    </div>
</div>