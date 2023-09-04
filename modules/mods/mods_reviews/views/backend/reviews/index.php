<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\widgets\Box;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Отзывы о товарах';
$this->params['subtitle']="Отзывы о товарах";
$this->params['breadcrumbs'][] = $this->title;

$gridId = 'mods_reviews-grid';

$boxButtons = $actions = [];
$showActions = false;

if (Yii::$app->user->can('BCreateModsReviews')) {
    $boxButtons[] = '{create}';
}

if (Yii::$app->user->can('BUpdateModsReviews')) {
    $actions[] = '{update}';
    $showActions = $showActions || true;
}

if (Yii::$app->user->can('BDeleteModsReviews')) {
    $actions[] = '{delete}';
    $showActions = $showActions || true;
}

if ($showActions === true) {
    $gridConfig['columns'][] = [
        'class' => ActionColumn::className(),
        'template' => '{view} '.implode(' ', $actions)
    ];
}
$boxButtons = !empty($boxButtons) ? implode(' ', $boxButtons) : null;
?>


<div class="row">
    <div class="col-xs-12">
        <?php Box::begin([
            'title'=>$this->params['subtitle'],
            'buttonsTemplate' => $boxButtons,
            'grid'=>$gridId
        ]);?>

        <p>
            <?= Html::a('<i class="icon icon-plus3"></i>', ['create'], ['class' => 'btn btn-sm btn-default']) ?>
        </p>
        <?= GridView::widget([
            'tableOptions'=>[
                'class'=>'table table-bordered table-hover dataTable'
            ],
            'dataProvider' => $dataProvider,
            'columns' => [

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
                // 'vote_up',
                // 'vote_down',
                'rating',
                [
                    'attribute'=>'published',
                    'format'=>'html',
                    'value'=>function($model){
                        return ($model->published===1)? '<span class="label label-success">Опубликован</span>':'<span class="label label-warning">Не опубликован</span>';
                    }
                ],
                //'created_at',
                [
                    'attribute'=>'created_at',
                    'format'=>['date','dd.MM.yyyy']
                ],
                // 'catalog_element_id',
                // 'user_id',
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
        <? Box::end(); ?>
    </div>
</div>
