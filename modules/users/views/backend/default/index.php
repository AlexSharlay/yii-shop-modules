<?php

/**
 * Users list view.
 *
 * @var \yii\base\View $this View
 * @var \yii\data\ActiveDataProvider $dataProvider Data provider
 * @var \common\modules\users\models\backend\UserSearch $searchModel Search model
 * @var array $roleArray Roles array
 * @var array $statusArray Statuses array
 */

use backend\widgets\Box;
use backend\widgets\GridView;
use common\modules\users\Module;
use yii\grid\ActionColumn;
use yii\grid\CheckboxColumn;
use yii\helpers\Html;
use yii\jui\DatePicker;

$this->title = Module::t('users', 'BACKEND_INDEX_TITLE');
$this->params['subtitle'] = Module::t('users', 'BACKEND_INDEX_SUBTITLE');
$this->params['breadcrumbs'] = [
    $this->title
];
$gridId = 'users-grid';
$gridConfig = [
    'id' => $gridId,
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        /*
        [
            'class' => CheckboxColumn::classname()
        ],
        */
        'id',
        [
            'attribute' => 'ynp',
            'format' => 'html',
            'value' => function ($model) {
                return ($model->profile->ynp > 0) ? $model->profile->ynp : '<span class="label label-info">' . $model->username . '</span>';
            },
        ],
        [
            'attribute' => 'firmName',
            'format' => 'html',
            'value' => function ($model){
                return ($model->profile->firmName)
                    ? $model->profile->firmName
                    : '<span class="label label-info">' . $model->profile->surname . ' ' . $model->profile->name . ' ' . $model->profile->patronymic . '</span>';
            }
        ],
        [
            'attribute' => 'status_id',
            'format' => 'html',
            'value' => function ($model) {
                if ($model->status_id === $model::STATUS_ACTIVE) {
                    $class = 'label-success';
                } elseif ($model->status_id === $model::STATUS_INACTIVE) {
                    $class = 'label-warning';
                } else {
                    $class = 'label-danger';
                }

                return '<span class="label ' . $class . '">' . $model->status . '</span>';
            },
            'filter' => Html::activeDropDownList(
                $searchModel,
                'status_id',
                $statusArray,
                ['class' => 'form-control', 'prompt' => Module::t('users', 'BACKEND_PROMPT_STATUS')]
            )
        ],
        [
            'attribute' => 'role',
            'filter' => Html::activeDropDownList(
                $searchModel,
                'role',
                $roleArray,
                ['class' => 'form-control', 'prompt' => Module::t('users', 'BACKEND_PROMPT_ROLE')]
            )
        ],


//Скидки отключены
//        [
//            'label' => 'Скидки',
//            'format' => 'html',
//            'value' => function ($model) {
//                return ($model->profile->ynp > 0) ? Html::a('<span class="icon-percent"></span>', ['update-discount', 'id' => $model['id']], ['data-pjax' => 0]) : '';
//            }
//        ],

//        [
//            'attribute' => 'username',
//            'format' => 'html',
//            'value' => function ($model) {
//                return Html::a($model['username'], ['update', 'id' => $model['id']], ['data-pjax' => 0]);
//            }
//        ],
//        [
//            'attribute' => 'name',
//            'value' => 'profile.name'
//        ],
//        [
//            'attribute' => 'surname',
//            'value' => 'profile.surname'
//        ],
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
//        [
//            'attribute' => 'updated_at',
//            'format' => 'date',
//            'filter' => DatePicker::widget(
//                [
//                    'model' => $searchModel,
//                    'attribute' => 'updated_at',
//                    'options' => [
//                        'class' => 'form-control'
//                    ],
//                    'clientOptions' => [
//                        'dateFormat' => 'dd.mm.yy',
//                    ]
//                ]
//            )
//        ]

    ]
];

$boxButtons = $actions = [];
$showActions = false;

if (Yii::$app->user->can('BCreateUsers')) {
    $boxButtons[] = '{create}';
}
if (Yii::$app->user->can('BUpdateUsers')) {
    $actions[] = '{update}';
    $showActions = $showActions || true;
}
if (Yii::$app->user->can('BDeleteUsers')) {
    //$boxButtons[] = '{batch-delete}';
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