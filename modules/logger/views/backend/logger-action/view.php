<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\modules\logger\models\backend\LoggerAction */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Logger Actions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="logger-action-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'create',
            'module',
            'controller',
            'action',
            'ip',
            'id_user',
        ],
    ]) ?>

    <?

    echo '<hr/>';

    echo '<b>headers:</b><br/>';
    echo '<pre>';
    print_r(json_decode($model->headers));
    echo '</pre>';

    echo '<hr/>';

    echo '<b>get:</b><br/>';
    echo '<pre>';
    print_r(json_decode($model->get));
    echo '</pre>';

    echo '<hr/>';

    echo '<b>post:</b><br/>';
    echo '<pre>';
    print_r(json_decode($model->post));
    echo '</pre>';

    ?>



</div>
