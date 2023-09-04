<?php

/* @var $this yii\web\View */
/* @var $searchModel common\modules\mods\mods_job\models\backend\JobSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use backend\widgets\Box;
use backend\widgets\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;


$this->title = 'Вакансии';
$this->params['subtitle'] = 'Вакансии';
$this->params['breadcrumbs'][] = $this->title;

$gridId = 'mods_job-grid';
$gridConfig = [
    'id' => $gridId,
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'attribute' => 'id',
            'headerOptions' => [
                'style' => [
                    'width' => '20px'
                ]
            ],
        ],
        'department',
        'vacancy',
        'salary',
        [
            'attribute' => 'sort',
            'headerOptions' => [
                'style' => [
                    'width' => '30px'
                ]
            ],
        ],
    ],


];

$boxButtons = $actions = [];
$showActions = false;

if (Yii::$app->user->can('BCreateModsJob')) {
    $boxButtons[] = '{create}';
}

if (Yii::$app->user->can('BUpdateModsJob')) {
    $actions[] = '{update}';
    $showActions = $showActions || true;
}

if (Yii::$app->user->can('BDeleteModsJob')) {
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

