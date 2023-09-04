<?php

/* @var $this yii\web\View */
/* @var $searchModel common\modules\mods\mods_seo\models\backend\SeoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use backend\widgets\Box;
use backend\widgets\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;


$this->title = 'SEO';
$this->params['subtitle'] = 'SEO';
$this->params['breadcrumbs'][] = $this->title;

$gridId = 'mods_seo-grid';
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
        [
            'attribute' => 'url',
            'format' => 'raw',
            'value' => function($model) {
                return Html::a($model->url,"http:\\\\".Yii::$app->request->serverName.$model->url,['target'=>'_blank']);
            },
        ],
        'note',
        'seo_title',
        //'seo_keyword',
        // 'seo_desc',
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

