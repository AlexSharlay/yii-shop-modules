<?php

use yii\helpers\Html;
use yii\grid\ActionColumn;
use backend\widgets\GridView;
use backend\widgets\Box;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\blogs\models\backend\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="category-index">

    <?// $this->render('_search', ['model' => $searchModel]); ?>

    <?
    /*
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'id',
                'attribute' => 'id',
                'headerOptions' => [
                    'width' => '80'
                ],
            ],
            'title',
            'alias',
            //'content:ntext',
            //'image_url:url',
            'sort',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    */

    $this->title = 'Категории';
    $this->params['subtitle'] = 'Категории';
    $this->params['breadcrumbs'] = [
        $this->title
    ];
    $gridId = 'blogs-category-grid';
    $gridConfig = [
        'id' => $gridId,
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => CheckboxColumn::classname()],
            [
                'label' => 'id',
                'attribute' => 'id',
                'headerOptions' => [
                    'width' => '80'
                ],
            ],
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
            'alias',
            //'content:ntext',
            //'image_url:url',
            'sort',
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
                        'prompt' => 'Статус'
                    ]
                )
            ],
        ]
    ];

    $boxButtons = $actions = [];
    $showActions = false;

    if (Yii::$app->user->can('BCreateBlogsCategory')) {
        $boxButtons[] = '{create}';
    }
    if (Yii::$app->user->can('BUpdateBlogsCategory')) {
        $actions[] = '{update}';
        $showActions = $showActions || true;
    }
    if (Yii::$app->user->can('BDeleteBlogsCategory')) {
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

</div>
