<?php
$bundle = \backend\themes\shop\pageAssets\catalog\element\index::register($this);

use backend\widgets\Box;
use backend\widgets\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use \common\modules\catalog\components\Helper;


$this->title = 'Товары';
$this->params['subtitle'] = 'Товары';
$this->params['breadcrumbs'] = [
    $this->title
];
$gridId = 'element-grid';
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
        'code_1c',
        'alias',
        'title',
        'title_model',
        [
            'attribute' => 'id_category',
            'value' => function ($model) {
                return ($model->categories->title == '') ? '' : $model->categories->title;
            },
            'filter' => Html::activeDropDownList(
                $searchModel,
                'id_category',
                //\common\modules\catalog\models\backend\Element::getChildCategoriesList(),
                \common\modules\catalog\models\backend\Element::getChildCategoriesListComplect(),
                [
                    'class' => 'form-control',
                    'prompt' => '-Выбрать-'
                ]
            )
        ],
        [
            'attribute' => 'id_manufacturer',
            'value' => function ($model) {
                return ($model->manufacturers->title == '') ? '' : $model->manufacturers->title;
            },
            'filter' => Html::activeDropDownList(
                $searchModel,
                'id_manufacturer',
                \common\modules\catalog\models\backend\Element::getManufacturersList(),
                [
                    'class' => 'form-control',
                    'prompt' => '-Выбрать-'
                ]
            )
        ],
        [
            'attribute' => 'price_1c',
            'value' => function ($model) {
                return ($model->price_1c) ? Helper::formatPrice($model->price_1c) : '';
            },
        ],
//        [
//            'attribute' => 'price',
//            'value' => function ($model) {
//                return ($model->price) ? Helper::formatPrice($model->price) : '';
//            },
//        ],
//        [
//            'attribute' => 'sort',
//            'value' => function ($model) {
//                return $model->sort;
//            },
//        ],
        [
            'attribute' => 'is_model',
            'format' => 'html',
            'value' => function ($model) {
                return ($model->is_model === 1) ? '<i class="icon icon-checkmark3 text-success"></i>' :  '';

            },
            'filter' => Html::activeDropDownList(
                $searchModel,
                'is_model',
                [
                    1 => 'Да',
                    0 => 'Нет'
                ],
                [
                    'class' => 'form-control',
                    'prompt' => '-Выбрать-'
                ]
            ),
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
                [
                    'class' => 'form-control',
                    'prompt' => '-Выбрать-'
                ]
            )
        ],
        [
            'format'=>'raw',
            'value'=>function ($model){
                return '<a href="/backend/catalog/element/update/?id='.$model->id.'" title="Редактировать" data-pjax="false" target="_blank" ><span class="glyphicon glyphicon-pencil"></span></a>';
            }
        ],
    ]
];

$boxButtons = $actions = [];
$showActions = false;

if (Yii::$app->user->can('BCreateCatalogElement')) {
    $boxButtons[] = '{create}';
}

if (Yii::$app->user->can('BUpdateCatalogElement')) {
    $actions[] = '{update}';
    $showActions = $showActions || true;
}

if (Yii::$app->user->can('BDeleteCatalogElement')) {
    $actions[] = '{delete}';
    $showActions = $showActions || true;
}

if ($showActions === true) {
    $gridConfig['columns'][] = [
        'class' => ActionColumn::className(),
        'template' => '{view} '.implode(' ', $actions),
        'headerOptions' => [
            'style' => [
                'width' => '60px'
            ]
        ],
    ];
}
$boxButtons = !empty($boxButtons) ? implode(' ', $boxButtons) : null; ?>

<div id="message" class="mt-20">
    <div></div>
</div>

<div class="row">
    <div class="col-xs-12">
        <?
        echo Html::input('text','alias','',['placeholder'=>'url на товар онлайнера', 'class'=>'form-control', 'style'=>'width:300px;float:left;']);
        echo Html::dropDownList('category','',ArrayHelper::map(\common\modules\catalog\models\backend\Category::find()->orderBy('title ASC')->all(),'id','title'), ['prompt'=>'Категория...','class'=>'form-control', 'style'=>'width:200px;float:left;']);
        echo Html::dropDownList('measurement','3',ArrayHelper::map(\common\modules\catalog\models\backend\Measurement::find()->orderBy('title ASC')->all(),'id','title'), ['prompt'=>'Измерения...','class'=>'form-control', 'style'=>'width:200px;float:left;', '3'=>['selected'=>true]]);
        echo Html::button('Парсить', ['class' => 'btn btn-primary btn-sm', 'style'=>'float:left;', 'id'=>'actionParse']);
        ?>
    </div>
</div>

<br/>
<input type="button" class="btn btn-default" id="fill" value="Показывать только товары без единого заполненного поля"/>


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