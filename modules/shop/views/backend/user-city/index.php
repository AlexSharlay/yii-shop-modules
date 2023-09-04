<?php

use backend\widgets\Box;
use backend\widgets\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;


$this->title = 'Города доступные для доставки';
$this->params['subtitle'] = 'Города доступные для доставки';
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
        'region',
        'day',
        'city',
    ]
];

$boxButtons = $actions = [];
$showActions = false;

$boxButtons[] = '{create}';

$actions[] = '{update}';
$showActions = $showActions || true;

$actions[] = '{delete}';
$showActions = $showActions || true;


if ($showActions === true) {
    $gridConfig['columns'][] = [
        'class' => ActionColumn::className(),
        'template' => implode(' ', $actions)
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