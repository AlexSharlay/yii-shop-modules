<?php

/* @var $this yii\web\View */
/* @var $searchModel common\modules\mods\mods_slides\models\backend\SlidesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use backend\widgets\Box;
use backend\widgets\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use \common\modules\mods\mods_slides\models\backend\Slides;


$this->title = 'Слайдер на главной';
$this->params['subtitle'] = 'Слайдер на главной';
$this->params['breadcrumbs'][] = $this->title;

$gridId = 'mods_slides-grid';
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
            'attribute' => 'img',
            'format' => 'html',
            'value' => function ($model) {
                return ($model->img) ? '<a href="'.$model->img.'">'.$model->img.'</a>' : $model->img;
            },
        ],
        [
            'attribute' => 'url',
            'format' => 'html',
            'value' => function ($model) {
                return ($model->url) ? '<a href="'.$model->url.'">'.$model->url.'</a>' : $model->url;
            },
        ],
        [
            'attribute' => 'sort',
            'headerOptions' => [
                'style' => [
                    'width' => '20px'
                ]
            ],
        ],
        [
            'attribute' => 'published',
            'format' => 'html',
            'value' => function ($model) {
                $class = ($model->published === 1) ? 'icon-checkmark3 text-success' : 'icon-cross2 text-danger';
                return '<i class="icon ' . $class . '"></i>';
            },
            'filter' => Html::activeDropDownList(
                $searchModel,
                'published',
                Slides::published(),
                [
                    'class' => 'form-control',
                    'prompt' => '-Выбрать-'
                ]
            )
        ],
    ],


];

$boxButtons = $actions = [];
$showActions = false;

if (Yii::$app->user->can('BCreateModsSlides')) {
    $boxButtons[] = '{create}';
}

if (Yii::$app->user->can('BUpdateModsSlides')) {
    $actions[] = '{update}';
    $showActions = $showActions || true;
}

if (Yii::$app->user->can('BDeleteModsSlides')) {
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


