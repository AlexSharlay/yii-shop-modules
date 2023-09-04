<?php

use backend\widgets\Box;
use backend\widgets\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\SerialColumn;

$this->title = 'Доставки';
$this->params['subtitle'] = 'Клиент - Менеджер';
$this->params['breadcrumbs'] = [
    $this->title
];
$gridId = 'delivery-grid';
$gridConfig = [
    'id' => $gridId,
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        //['class' => CheckboxColumn::classname()],
        //'id',
        [
            'attribute' => 'id_manager',
            'value' => function($model) {
                return $model->profileManager->fullName;
            },
            'filter' => Html::activeDropDownList(
                $searchModel,
                'id_manager',
                \common\modules\users\models\backend\User::getMangersList(),
                [
                    'class' => 'form-control',
                    'prompt' => '-Выбрать-'
                ]
            )
        ],
        [
            'attribute' => 'id_client',
            'value' => function($model) {
                return $model->profileClient->firmName.' - '.$model->profileClient->fullName;
            },/*
            'filter' => Html::activeDropDownList(
                $searchModel,
                'id_client',
                \common\modules\users\models\backend\User::getClientsList(),
                [
                    'class' => 'form-control',
                    'prompt' => '-Выбрать-'
                ]
            )*/
        ],
        [
            'attribute'=>'active',
            'value' => function ($model) {
                return ($model->active) ? 'Да' : 'Нет';
            },
            'filter' => Html::activeDropDownList(
                $searchModel,
                'active',
                [
                    '0' => 'Нет',
                    '1' => 'Да',
                ],
                [
                    'class' => 'form-control',
                    'prompt' => '-Выбрать-'
                ]
            )
        ],
    ]
];

$boxButtons = $actions = [];
$showActions = false;

if (Yii::$app->user->can('BCreateClientManager')) {
    $boxButtons[] = '{create}';
}

if (Yii::$app->user->can('BUpdateClientManager')) {
    $actions[] = '{update}';
    $showActions = $showActions || true;
}

if (Yii::$app->user->can('BDeleteClientManager')) {
    $actions[] = '{delete}';
    $showActions = $showActions || true;
}

if ($showActions === true) {
    $gridConfig['columns'][] = [
        'class' => ActionColumn::className(),
        'template' => '' . implode(' ', $actions)
    ];
}
$boxButtons = !empty($boxButtons) ? implode(' ', $boxButtons) : null;
?>

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


