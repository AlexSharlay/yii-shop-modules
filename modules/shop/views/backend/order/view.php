<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\modules\shop\components\Helper;

/* @var $this yii\web\View */
/* @var $model common\modules\shop\models\frontend\Order */

$this->title = 'Заказ #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Shop Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="shop-order-view">

        <p>
            <?= Html::a('Вернуться к списку заказов', '/backend/shop/order/', ['class' => 'btn btn-default']) ?>
        </p>

        <table class="table table-striped table-bordered detail-view">
            <tbody>
            <tr>
                <th>Статус</th>
                <td><?= status($model); ?></td>
            </tr>
            <tr>
                <th>Дата заказа</th>
                <td><?= created($model); ?></td>
            </tr>
            <? if ($model->status == 0 || 6 || 7 || 8 || 9 || 10) { ?>
                <tr>
                    <th>Оплатите до</th>
                    <td><?= payUp($model); ?></td>
                </tr>
                <tr>
                    <th>У вас осталось</th>
                    <td><?= (timeLeft($model) > 0) ? timeLeft($model) : '' ?></td>
                </tr>
            <? } ?>

            <tr>
                <th>Заказ</th>
                <td class="info-order"> <?= $model->data; ?></td>
            </tr>
            </tbody>
        </table>
    </div>

    </div>


    <style>
        .info-order table td, .info-order table th {
            padding: 10px;
        }

        .info-order table {
            width: 100%;
        }

        .info-order {
            background-color: #fff;
        }
    </style>

<?
function payUp($model)
{
    if ($model->status == 0) {
        $dateEnd = new DateTime();
        $dateEnd->setTimestamp($model->created)->modify('+3 day');
        return $dateEnd->format("d.m.Y H:i:s");
    } else {
        return '';
    }
}

function status($model)
{
    $status = \common\modules\shop\models\Order::status($model->status);
    return '<span class="label ' . $status['class'] . '">' . $status['title'] . '</span>';
}

function timeLeft($model)
{
    if ($model->status == 0) {
        $dateEnd = new DateTime();
        $dateEnd->setTimestamp($model->created)->modify('+3 day');
        $dateNow = new DateTime('NOW');
        $dateLeft = strtotime($dateEnd->format("d.m.Y H:i:s")) - strtotime($dateNow->format("d.m.Y H:i:s"));
        return Helper::time_autoformat($dateLeft, 1);
    } else {
        return '';
    }
}

function created($model)
{
    $date = new DateTime();
    $date->setTimestamp($model->created);
    return $date->format("d.m.Y H:i:s");
}

?>