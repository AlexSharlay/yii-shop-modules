<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\modules\mods\mods_manufacturer\models\Manufacturer */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Бренды на главной', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mods_manufacturer-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('К списку', ['/mods_manufacturer/manufacturer/index'], ['class' => 'btn btn-default']) ?>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
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
            'title',
            'url:url',
            'ico',
            'sort',
        ],
    ]) ?>

</div>
