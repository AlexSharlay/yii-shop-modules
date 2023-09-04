<?php

/* @var $this yii\web\View */
/* @var $searchModel common\modules\mods\mods_manufacturer\models\backend\ManufacturerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use backend\widgets\Box;
use backend\widgets\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;


$this->title = 'Бренды на главной';
$this->params['subtitle'] = 'Бренды на главной';
$this->params['breadcrumbs'][] = $this->title;

$gridId = 'mods_manufacturer-grid';
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
        'title',
        'url:url',
        'ico',
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

if (Yii::$app->user->can('BCreateModsManufacturer')) {
    $boxButtons[] = '{create}';
}

if (Yii::$app->user->can('BUpdateModsManufacturer')) {
    $actions[] = '{update}';
    $showActions = $showActions || true;
}

if (Yii::$app->user->can('BDeleteModsManufacturer')) {
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


