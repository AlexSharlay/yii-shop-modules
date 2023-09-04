<?php

use backend\widgets\Box;
use backend\widgets\GridView;
use common\modules\catalog\Module;
use yii\grid\ActionColumn;
use yii\helpers\Html;


$this->title = 'Секции';
$this->params['subtitle'] = 'Производители';
$this->params['breadcrumbs'] = [
    $this->title
];
$gridId = 'manufacturer-grid';
$gridConfig = [
    'id' => $gridId,
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        //['class' => CheckboxColumn::classname()],
        [
            'attribute' => 'id',
        ],
        'title',
        [
            'attribute'=>'manufacturerCountries.country',
            'format' => 'html',
            'label' => 'Страны',
            'value' => function ($model) {
                $arr = [];
                foreach($model->manufacturerCountries as $item) {
                    $arr[] = $item->country->id.' - '.$item->country->title;
                }
                return implode('<br/>',$arr);
            },
        ],
        //'desc:ntext',
        'alias',
        'ico',

//        'published',
//        [
//            'attribute' => 'published',
//            'format' => 'html',
//            'value' => function ($model) {
//                $class = ($model->published === 1) ? 'icon-checkmark3 text-success' : 'icon-cross2 text-danger';
//                return '<i class="icon ' . $class . '"></i>';
//            },
//            'filter' => Html::activeDropDownList(
//                $searchModel,
//                'published',
//                $publishedArray,
//                [
//                    'class' => 'form-control',
//                    'prompt' => '-Выбрать-'
//                ]
//            )
//        ],


//        'perekup',
        [
            'attribute' => 'perekup',
            'format' => 'html',
            'value' => function ($model) {
                $class = ($model->perekup === 1) ? 'icon-checkmark3 text-success' : 'icon-cross2 text-danger';
                return '<i class="icon ' . $class . '"></i>';
            },
            'filter' => Html::activeDropDownList(
                $searchModel,
                'perekup',
                $perekupArray,
                [
                    'class' => 'form-control',
                    'prompt' => '-Выбрать-'
                ]
            )
        ],



        // 'seo_title',
        // 'seo_keyword',
        // 'seo_desc',
    ]
];

$boxButtons = $actions = [];
$showActions = false;

if (Yii::$app->user->can('BCreateCatalogManufacturer')) {
    $boxButtons[] = '{create}';
}

if (Yii::$app->user->can('BUpdateCatalogManufacturer')) {
    $actions[] = '{update}';
    $showActions = $showActions || true;
}

if (Yii::$app->user->can('BDeleteCatalogManufacturer')) {
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
                'bodyOptions' => [
                    'class' => ''
                ],
                'buttonsTemplate' => $boxButtons,
                'grid' => $gridId
            ]
        ); ?>
        <?= GridView::widget($gridConfig); ?>
        <?php Box::end(); ?>
    </div>
</div>