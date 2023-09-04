<?php

/* @var $this yii\web\View */
/* @var $searchModel common\modules\logger\models\backend\LoggerActionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use backend\widgets\Box;
use backend\widgets\GridView;
use yii\grid\ActionColumn;

$this->title = 'Логи';
$this->params['subtitle'] = 'Логи';
$this->params['breadcrumbs'] = [
    $this->title
];
$gridId = 'country-grid';
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
            'attribute' => 'create',
            //'format' => 'datetime',
            'headerOptions' => [
                'style' => [
                    'width' => '120px'
                ]
            ],
        ],
        'module',
        'controller',
        'action',
        [
            'attribute' => 'ip',
            'headerOptions' => [
                'style' => [
                    'width' => '80px'
                ]
            ],
        ],
        [
            'attribute' => 'id_user',
            'headerOptions' => [
                'style' => [
                    'width' => '80px'
                ]
            ],
        ],
        // 'headers:ntext',
        // 'get:ntext',
        // 'post:ntext',
    ]
];

$boxButtons = $actions = [];
$showActions = false;

if (Yii::$app->user->can('BDeleteLoggerAction')) {
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