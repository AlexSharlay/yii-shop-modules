<?/*<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title">Мои заказы</h5>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>№</th>
                    <th>Дата заказа</th>
                    <th>Статус</th>
                    <th>До конца брони</th>
                    <th>Счёт на оплату</th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
*/?>

<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\modules\shop\components\Helper;

$bundle = \frontend\themes\shop\pageAssets\order\index::register($this);

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Мои заказы';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-8 col-lg-9">
        <? if (count($manager) > 1) { ?>
        <div class="panel panel-flat">
            <div class="panel-body row">
                <div style="float:left;margin-right: 20px;">
                    <img style="width: 126px;border-radius: 100px;border: 1px solid #ccc;box-shadow: 0 0 2px #ccc;" src="/statics/users/avatars/<?=$manager['avatar_url']?>">
                </div>
                <div style="float:left;">
                    <h6 class="panel-title">Ваш менеджер:</h6>
                    <br/>
                    <p>
                        <?= $manager['surname'] . ' ' . $manager['name'] . ' ' . $manager['patronymic'] ?><br/>

                        <? if ($manager['email']) { ?>
                            <i class="icon-mail5"></i> <?=$manager['email']?><br/>
                        <? } ?>

                          <? if ($manager['phone_company']) { ?>
                            <i class="icon-phone"></i> <?=$manager['phone_company']?><br/>
                        <? } ?>

                        <? if ($manager['phone_director']) { ?>
                            <i class="icon-mobile"></i> <?=$manager['phone_director']?><br/>
                        <? } ?>

                    </p>
                </div>
            </div>
        </div>
        <? } ?>
    </div>
    <div class="col-md-4 col-lg-3">
        <?= $this->render('_menu') ?>
    </div>
</div>

<div class="shop-order-index">

    <h1>Мои заказы</h1>


    <div class="row">
        <div class="row col-lg-12 clo-md-12 col-sm-12">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    //['class' => 'yii\grid\SerialColumn'],
                    /*[
                        'attribute' => 'id',
                        'headerOptions' => ['width' => '40'],
                    ],*/
                    [
                        'headerOptions' => ['width' => '100'],
                        'attribute' => 'status',
                        'format' => 'html',
                        'value' => function ($model) {
                            $status = \common\modules\shop\models\Order::status($model->status);
                            return '<span class="label ' . $status['class'] . '">' . $status['title'] . '</span>';
                        },
                    ],
                    [
                        'headerOptions' => ['width' => '180'],
                        'attribute' => 'created',
                        'value' => function($model) {
                            $date = new DateTime();
                            $date->setTimestamp($model->created);
                            return $date->format("d.m.Y H:i:s");
                        },
                    ],
                    [
                        'attribute' => 'cost',
                        'value' => function($model) {
                            return \common\modules\catalog\components\Helper::formatPrice($model->cost);
                        },
                    ],
                    [
                        'label' => 'Оплатите до',
                        'headerOptions' => ['width' => '180'],
                        'value' => function($model) {
                            if ($model->status === 0 || 6 || 7 || 8 || 9 || 10) {
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
                        'headerOptions' => ['width' => '180'],
                        'value' => function($model) {
                            if ($model->status === 0 || 6 || 7 || 8 || 9 || 10) {
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
                                    return Html::a('<span class="icon icon-file-pdf"></span>', '/statics/shop/invoices/pdf/'.$model->invoice_pdf, [
                                        'title' => 'PDF',
                                    ]);
                                } else if (!in_array($model->status,[0,1,2,3,7])) {
                                    return '';
                                } else {
                                    return '<span class="invoice_pdf" data-id="'.$model->id.'">Формируется, пожалуйста подождите пару минут. Ссылка на скачку появится автоматически без перезагрузки страницы.</span>';
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
                                if (in_array($model->status, [0,4,5,6,7,8,9,10])) {
                                    return Html::a('<span class="glyphicon glyphicon-remove"></span>', '/my/orders/delete/'.$model->id.'/', [
                                        'title' => 'Удалить',
                                        'data' => [
                                            'confirm' => 'Вы уверены что хотите удалить счёт?',
                                        ],
                                    ]);
                                }
                            },
                            'view' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', '/my/orders/view/'.$model->id.'/', [
                                    'title' => 'Подробнее',
                                ]);
                            },
                        ],
                        'headerOptions' => ['width' => '80'],
                    ],
                    //'id_user',
                    //'data:ntext',
                    //'one_data:ntext',
                ],
            ]); ?>
        </div>
    </div>

</div>