<?php

use backend\widgets\Box;
use backend\widgets\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Коллекции';
$this->params['subtitle'] = 'Коллекция';
$this->params['breadcrumbs'] = [
    $this->title
];
$gridId = 'section-grid';
$gridConfig = [
    'id' => $gridId,
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        //['class' => CheckboxColumn::classname()],
        'id',
        [
            'attribute' => 'alias',
            'label'=>'Alias',
            'format' => 'raw',
            'value' => function ($model) {
                return  Html::a($model->alias,Url::toRoute(['view-elements', 'id' => $model->id]));
            },
        ],
    ]
];

$boxButtons = $actions = [];
$showActions = false;

/*
if (Yii::$app->user->can('BViewCatalogCollection')) {
    $boxButtons[] = '{view}';
}
*/

if (Yii::$app->user->can('BCreateCatalogCollection')) {
    $boxButtons[] = '{create}';
}

if (Yii::$app->user->can('BUpdateCatalogCollection')) {
    $actions[] = '{update}';
    $showActions = $showActions || true;
}

if (Yii::$app->user->can('BDeleteCatalogCollection')) {
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