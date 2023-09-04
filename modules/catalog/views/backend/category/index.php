<?php

use backend\widgets\Box;
use backend\widgets\GridView;
use common\modules\catalog\Module;
use yii\grid\ActionColumn;
use yii\helpers\Html;


$this->title = 'Секции';
$this->params['subtitle'] = 'Категории';
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

        [
            'attribute' => 'id',
            'headerOptions' => [
                'style' => [
                    'width' => '20px'
                ]
            ],
        ],
        [
            'attribute' => 'id_parent',
            //'value' => 'categories.title',
            'value' => function ($model) {
                return ($model->categories->title == '') ? '' : $model->categories->title;
            },
            'filter' => Html::activeDropDownList(
                $searchModel,
                'id_parent',
                //\common\modules\catalog\models\backend\Category::getCategoriesList(),
                \common\modules\catalog\models\backend\Element::getChildCategoriesListComplect(),
                [
                    'class' => 'form-control',
                    'prompt' => '-Выбрать-'
                ]
            )
        ],
        'title',
        //'title_yml',
        //'desc:ntext',
        'alias',
        //'ico',
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
                $publishedArray,
                $categoryFilterArray,
                [
                    'class' => 'form-control',
                    'prompt' => '-Выбрать-'
                ]
            ),
            'headerOptions' => [
                'style' => [
                    'width' => '60px'
                ]
            ],
        ],
        [
            'attribute' => 'show_in_menu',
            'format' => 'html',
            'value' => function ($model) {
                $class = ($model->show_in_menu === 1) ? 'icon-list text-success' : 'icon-list text-danger';
                return '<i class="icon ' . $class . '"></i>';
            },
            'filter' => Html::activeDropDownList(
                $searchModel,
                'show_in_menu',
                $showInMenuArray,
                $parentFilterArray,
                [
                    'class' => 'form-control',
                    'prompt' => '-Выбрать-'
                ]
            ),
            'headerOptions' => [
                'style' => [
                    'width' => '60px'
                ]
            ],
        ],
        /*
        [
            'attribute' => 'use_model',
            'format' => 'html',
            'value' => function ($model) {
                $class = ($model->use_model === 1) ? 'icon-checkmark3 text-success' : 'icon-cross2 text-danger';
                return '<i class="icon ' . $class . '"></i>';
            },
            'filter' => Html::activeDropDownList(
                $searchModel,
                'use_model',
                $publishedArray,
                [
                    'class' => 'form-control',
                    'prompt' => '-Выбрать-'
                ]
            ),
            'headerOptions' => [
                'style' => [
                    'width' => '60px'
                ]
            ],
        ],
        */
        //'hide_filter_after',
        // 'seo_title',
        // 'seo_keyword',
        // 'seo_desc',
    ]
];

$boxButtons = $actions = [];
$showActions = false;

/*
if (Yii::$app->user->can('BViewCatalogCategory')) {
    $boxButtons[] = '{view}';
}
*/

if (Yii::$app->user->can('BCreateCatalogCategory')) {
    $boxButtons[] = '{create}';
}

if (Yii::$app->user->can('BUpdateCatalogCategory')) {
    $actions[] = '{update}';
    $showActions = $showActions || true;
}

if (Yii::$app->user->can('BDeleteCatalogCategory')) {
    $actions[] = '{delete}';
    $showActions = $showActions || true;
}

if ($showActions === true) {
    $gridConfig['columns'][] = [
        'class' => ActionColumn::className(),
        'template' => '{view} '.implode(' ', $actions),
        'headerOptions' => [
            'style' => [
                'width' => '100px'
            ]
        ],
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

