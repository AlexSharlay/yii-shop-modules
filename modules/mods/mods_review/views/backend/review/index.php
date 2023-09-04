<?php

/* @var $this yii\web\View */
/* @var $searchModel common\modules\mods\mods_review\models\backend\ReviewSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use backend\widgets\Box;
use backend\widgets\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use \common\modules\mods\mods_review\models\backend\Review;
use yii\jui\DatePicker;


$this->title = 'Вакансии';
$this->params['subtitle'] = 'Вакансии';
$this->params['breadcrumbs'][] = $this->title;

$gridId = 'mods_review-grid';
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
        'mark',
        'name',
        'city',
        //'desc',
        [
            'attribute' => 'date',
            'format' => 'date',
            'filter' => DatePicker::widget(
                [
                    'model' => $searchModel,
                    'attribute' => 'date',
                    'options' => [
                        'class' => 'form-control'
                    ],
                    'clientOptions' => [
                        'dateFormat' => 'ddmmyy',
                    ]
                ]
            )
        ],
        [
            'attribute' => 'published',
            'format' => 'html',
            'value' => function ($model) {
                $class = '';
                if ($model->published === 0) {
                    $class = ' icon-question3 text-warning';
                } else if ($model->published === 1) {
                    $class = 'icon-checkmark3 text-success';
                } else if ($model->published === 2) {
                    $class = 'icon-cross2 text-danger';
                }
                return '<i class="icon ' . $class . '"></i>';
            },
            'filter' => Html::activeDropDownList(
                $searchModel,
                'published',
                Review::published(),
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

if (Yii::$app->user->can('BCreateModsReview')) {
    $boxButtons[] = '{create}';
}

if (Yii::$app->user->can('BUpdateModsReview')) {
    $actions[] = '{update}';
    $showActions = $showActions || true;
}

if (Yii::$app->user->can('BDeleteModsReview')) {
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

