<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\modules\shop\components\Helper;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\shop\models\backend\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'id',
                'headerOptions' => ['width' => '40'],
            ],
            [
                'label' => 'УНП',
                'attribute' => 'ynp',
                'value' => 'profile.ynp',
            ],
            [
                'label' => 'Наименование компании',
                'attribute' => 'firmName',
                'value' => 'profile.firmName',
            ],

            /*
            [
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'attribute_name',
                    ArrayHelper::map(User::find()->asArray()->all(), 'id', 'Name'),
                    ['class'=>'form-control','prompt' => '- Статус -']
                ),
            ],
            */
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function ($model) {
                    $status = \common\modules\shop\models\Order::status($model->status);
                    return '<span class="label ' . $status['class'] . '">' . $status['title'] . '</span>';
                },
                'filter' => \common\modules\shop\models\Order::statusFilter()
            ],
            [
                'attribute' => 'cost',
                'value' => function($model) {
                    return \common\modules\catalog\components\Helper::formatPrice($model->cost);
                },
            ],
            [
                'attribute' => 'created',
                //'format' =>  ['date', 'dd.MM.Y H:i:s'],
                'value' => function($model) {
                    $dateEnd = new DateTime();
                    $dateEnd->setTimestamp($model->created);
                    return $dateEnd->format("d.m.Y H:i:s");
                },
            ],
            [
                'label' => 'Оплатите до',
                'value' => function($model) {
                    if ($model->status == 0 || 6 || 7 || 8 || 9 || 10) {
                        $dateEnd = new DateTime();
                        $dateEnd->setTimestamp($model->created)->modify('+3 day');
                        return $dateEnd->format("d.m.Y H:i:s");
                    } else {
                        return '';
                    }
                },
            ],
            [
                'label' => 'У вас осталось',
                'value' => function($model) {
                    if ($model->status == 0 || 6 || 7 || 8 || 9 || 10) {
                        $dateEnd = new DateTime();
                        $dateEnd->setTimestamp($model->created)->modify('+3 day');
                        $dateNow = new DateTime('NOW');
                        $dateLeft = strtotime($dateEnd->format("d.m.Y H:i:s")) - strtotime($dateNow->format("d.m.Y H:i:s"));
                        $result =  Helper::time_autoformat($dateLeft,1);
                        return ($result > 0) ?  $result : '';
                    } else {
                        return '';
                    }
                },
            ],
            [
                'header' => 'Счёт',
                'class' => 'yii\grid\ActionColumn',
                'template' => '{pdf} {docx}',
                'buttons' => [
                    'pdf' => function ($url, $model) {
                        if ($model->invoice_pdf) {
                            return Html::a('<span class="icon icon-file-excel"></span>', '/statics/shop/invoices/pdf/'.$model->invoice_pdf, [
                                'title' => 'PDF',
                            ]);
                        } else if (!in_array($model->status,[0,1,2,3,7])) {
                            return '';
                        } else {
                            return '<span id="invoice_pdf">Формируется. F5.</span>';
                        }
                    },
                ],
                'headerOptions' => ['width' => '250'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {delete}',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        if ($model->status == 0) {
                            return Html::a('<span class="glyphicon glyphicon-remove"></span>', '/backend/shop/order/delete/?id='.$model->id, [
                                'title' => 'Удалить',
                            ]);
                        } else {
                            return '';
                        }
                    },
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', '/backend/shop/order/view/?id='.$model->id, [
                            'title' => 'Подробнее',
                        ]);
                    },
                ],
                'headerOptions' => ['width' => '80'],
            ],
            //'data:ntext',
            //'oneC:ntext',
        ],
    ]); ?>
</div>
