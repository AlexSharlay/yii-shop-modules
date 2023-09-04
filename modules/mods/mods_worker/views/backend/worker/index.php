<?php

/* @var $this yii\web\View */
/* @var $searchModel common\modules\mods\mods_worker\models\backend\WorkerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use backend\widgets\Box;
use backend\widgets\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;


$this->title = 'Сотрудники';
$this->params['subtitle'] = 'Сотрудники';
$this->params['breadcrumbs'][] = $this->title;

$gridId = 'mods_worker-grid';
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
        'fio',
        'phone',
        'phone_mobile',
        'email:email',
        //'photo',
        [
            'attribute' => 'sort',
            'headerOptions' => [
                'style' => [
                    'width' => '20px'
                ]
            ],
        ],
        // 'flag1',
        // 'flag2',
        // 'flag3',
        // 'flag4',
        // 'flag5',
        // 'position',
        // 'phone',
        // 'phone_mobile',
        // 'email:email',
    ],


];

$boxButtons = $actions = [];
$showActions = false;

if (Yii::$app->user->can('BCreateModsWorker')) {
    $boxButtons[] = '{create}';
}

if (Yii::$app->user->can('BUpdateModsWorker')) {
    $actions[] = '{update}';
    $showActions = $showActions || true;
}

if (Yii::$app->user->can('BDeleteModsWorker')) {
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

