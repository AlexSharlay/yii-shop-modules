<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\modules\mods\mods_reviews\models\Review */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Reviews', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="review-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
            [
                'attribute'=>'text',
                'format'=>'html'
            ],
            [
                'attribute'=>'advantage',
                'format'=>'html'
            ],
            [
                'attribute'=>'disadvantages',
                'format'=>'html'
            ],
            //'vote_up',
            //'vote_down',
            'rating',
            [
                'attribute'=>'published',
                'format'=>'html',
                'value'=>$model->published===1? '<span class="label label-success">Опубликован</span>':'<span class="label label-warning">Не опубликован</span>'
            ],
            [
                'attribute'=>'created_at',
                'format'=>['date','dd.MM.yyyy']
            ],
            [
                'attribute'=>'catalog_element_id',
                'format'=>'html',
                'value'=>'<a href="/backend/catalog/element/view/?id='.$model->element->id.'">'.$model->element->title.'</a>'
            ],
          //  'catalog_element_id',
           // 'user_id',
            [
                'attribute'=>'user_id',
                'format'=>'html',
                'value'=>'<a href="/backend/users/default/update/?id='.$model->user->id.'">'.$model->fullName.'</a>'
            ]
        ],
    ]) ?>

</div>
