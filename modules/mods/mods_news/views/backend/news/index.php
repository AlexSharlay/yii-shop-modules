<?php

/* @var $this yii\web\View */
/* @var $searchModel common\modules\mods\mods_news\models\backend\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use backend\widgets\Box;
use backend\widgets\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use \common\modules\mods\mods_news\models\backend\News;


$this->title = 'Новости на главной';
$this->params['subtitle'] = 'Новости на главной';
$this->params['breadcrumbs'][] = $this->title;

$gridId = 'mods_news-grid';
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
            'attribute' => 'col',
            'headerOptions' => [
                'style' => [
                    'width' => '20px'
                ]
            ],
        ],
        [
            'attribute' => 'row',
            'headerOptions' => [
                'style' => [
                    'width' => '20px'
                ]
            ],
        ],
        [
            'attribute' => 'title',
            'format' => 'html',
            'value' => function ($model) {
                return ($model->url) ? '<a href="'.$model->url.'">'.$model->title.'</a>' : $model->title;
            },
        ],
        [
            'attribute' => 'ico_title',
            'format' => 'html',
            'value' => function ($model) {
                $colors = News::colors();
                return '<span class="label label-'.$colors[$model->ico_color].'">'.$model->ico_title.'</span>';
            },
        ],
        // 'image',
        [
            'attribute' => 'url_target',
            'format' => 'html',
            'value' => function ($model) {
                return ($model->url_target === 1) ? 'В новом' : 'В том же';
            },
            'filter' => Html::activeDropDownList(
                $searchModel,
                'published',
                News::target(),
                [
                    'class' => 'form-control',
                    'prompt' => '-Выбрать-'
                ])
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
                News::published(),
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

if (Yii::$app->user->can('BCreateModsNews')) {
    $boxButtons[] = '{create}';
}

if (Yii::$app->user->can('BUpdateModsNews')) {
    $actions[] = '{update}';
    $showActions = $showActions || true;
}

if (Yii::$app->user->can('BDeleteModsNews')) {
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


