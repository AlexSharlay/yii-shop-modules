<?php

/**
 * Blogs list view.
 *
 * @var \yii\base\View $this View
 * @var \yii\data\ActiveDataProvider $dataProvider Data provider
 * @var \common\modules\blogs\models\backend\BlogSearch $searchModel Search model
 * @var array $statusArray Statuses array
 */

use backend\widgets\GridView;
use common\modules\blogs\Module;
use yii\grid\ActionColumn;
use yii\grid\CheckboxColumn;
use yii\helpers\Html;
use yii\jui\DatePicker;
use backend\widgets\Box;

$this->title = Module::t('blogs', 'BACKEND_INDEX_TITLE');
$this->params['subtitle'] = Module::t('blogs', 'BACKEND_INDEX_SUBTITLE');
$this->params['breadcrumbs'] = [
    $this->title
];
$gridId = 'blogs-grid';
$gridConfig = [
    'id' => $gridId,
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        // ['class' => CheckboxColumn::classname()],
        'id',
        [
            'attribute' => 'category_id',
            'value' => 'categories.title',
            'filter' => Html::activeDropDownList(
                $searchModel,
                'category_id',
                \common\modules\blogs\models\backend\Blog::getCategoriesList(),
                [
                    'class' => 'form-control',
                    'prompt' => '-Выбрать-'
                ]
            )
        ],
        'alias',
        [
            'attribute' => 'title',
            'format' => 'html',
            'value' => function ($model) {
                return Html::a(
                    $model['title'],
                    ['update', 'id' => $model['id']]
                );
            }
        ],
        'views',
        [
            'attribute' => 'status_id',
            'format' => 'html',
            'value' => function ($model) {
                $class = ($model->status_id === $model::STATUS_PUBLISHED) ? 'label-success' : 'label-danger';

                return '<span class="label ' . $class . '">' . $model->status . '</span>';
            },
            'filter' => Html::activeDropDownList(
                $searchModel,
                'status_id',
                $statusArray,
                [
                    'class' => 'form-control',
                    'prompt' => Module::t('blogs', 'BACKEND_PROMPT_STATUS')
                ]
            )
        ],
        [
            'attribute' => 'created_at',
            'format' => 'date',
            'filter' => DatePicker::widget(
                [
                    'model' => $searchModel,
                    'attribute' => 'created_at',
                    'options' => [
                        'class' => 'form-control'
                    ],
                    'clientOptions' => [
                        'dateFormat' => 'dd.mm.yy',
                    ]
                ]
            )
        ],
        [
            'attribute' => 'updated_at',
            'format' => 'date',
            'filter' => DatePicker::widget(
                [
                    'model' => $searchModel,
                    'attribute' => 'updated_at',
                    'options' => [
                        'class' => 'form-control'
                    ],
                    'clientOptions' => [
                        'dateFormat' => 'dd.mm.yy',
                    ]
                ]
            )
        ]
    ]
];

$boxButtons = $actions = [];
$showActions = false;

if (Yii::$app->user->can('BCreateBlogs')) {
    $boxButtons[] = '{create}';
}
if (Yii::$app->user->can('BUpdateBlogs')) {
    $actions[] = '{update}';
    $showActions = $showActions || true;
}
if (Yii::$app->user->can('BDeleteBlogs')) {
    $boxButtons[] = '{batch-delete}';
    $actions[] = '{delete}';
    $showActions = $showActions || true;
}

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
