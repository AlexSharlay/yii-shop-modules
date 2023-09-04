<?php

use backend\widgets\Box;
use backend\widgets\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\SerialColumn;

$this->title = 'Доставки';
$this->params['subtitle'] = 'Доставки';
$this->params['breadcrumbs'] = [
    $this->title
];
$gridId = 'delivery-grid';
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
        [
            'attribute' => 'title',
            'headerOptions' => [
                'style' => [
                    'width' => '200px'
                ]
            ],
        ],
        [
            'attribute' => 'desc',
            'format' => 'html',
        ],
        [
            'attribute'=>'deliveryPayments.payment',
            'format' => 'html',
            'label' => 'Оплаты',
            'value' => function ($model) {
                $arr = [];
                foreach($model->deliveryPayments as $item) {
                    $arr[] = $item->payment->id.' - '.$item->payment->title;
                }
                return implode('<br/>',$arr);
            },
        ],
        [
            'attribute' => 'price',
            'headerOptions' => [
                'style' => [
                    'width' => '80px'
                ]
            ],
        ],
        [
            'attribute' => 'price_from',
            'headerOptions' => [
                'style' => [
                    'width' => '80px'
                ]
            ],
        ],
        [
            'attribute' => 'price_to',
            'headerOptions' => [
                'style' => [
                    'width' => '80px'
                ]
            ],
        ],
        [
            'attribute' => 'sort',
            'headerOptions' => [
                'style' => [
                    'width' => '40px'
                ]
            ],
        ],
    ]
];

$boxButtons = $actions = [];
$showActions = false;

if (Yii::$app->user->can('BCreateShopDelivery')) {
    $boxButtons[] = '{create}';
}

if (Yii::$app->user->can('BUpdateShopDelivery')) {
    $actions[] = '{update}';
    $showActions = $showActions || true;
}

if (Yii::$app->user->can('BDeleteShopDelivery')) {
    $actions[] = '{delete}';
    $showActions = $showActions || true;
}

if ($showActions === true) {
    $gridConfig['columns'][] = [
        'class' => ActionColumn::className(),
        'template' => '{view} ' . implode(' ', $actions)
    ];
}
$boxButtons = !empty($boxButtons) ? implode(' ', $boxButtons) : null; ?>

<div class="row">
    <div class="col-xs-12">
        <?php Box::begin(
            [
                'title' => $this->params['subtitle'],
                'bodyOptions' => [
                    'class' => ''
                ],
                'buttonsTemplate' => $boxButtons,
                'grid' => $gridId
            ]
        ); ?>
        <?= GridView::widget($gridConfig); ?>
        <?php Box::end(); ?>
    </div>
</div>