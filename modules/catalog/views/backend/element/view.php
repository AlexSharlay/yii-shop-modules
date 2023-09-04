<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\modules\catalog\models\Element */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Elements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="element-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
            'alias',
            'title',
            'title_model',
            'desc_mini:ntext',
            'desc_full:ntext',
            'desc_yml:ntext',
            'id_category',
            'id_manufacturer',
            'id_measurement',
            'published',
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'datetime',
            ],
            'article',
            'guarantee',
            'price_1c',
            'price',
            'price_old',
            'in_stock',
            'is_defect',
            'is_main',
            'is_model',
            'is_custom',
            'hit',
            'info_manufacturer',
            'info_importer',
            'info_service',
            'tip_1c',

            'tp_onliner_by_alias',
            'tp_onliner_by_title',
            'tp_onliner_by_url',

            'tp_1k_by_title',
            'tp_1k_by_alias',
            'tp_1k_by_url',

            'tp_market_yandex_by_title',
            'tp_market_yandex_by_alias',
            'tp_market_yandex_by_url',

            'tp_shop_by_title',
            'tp_shop_by_alias',
            'tp_shop_by_url',

            'tp_unishop_by_title',
            'tp_unishop_by_alias',
            'tp_unishop_by_url',

            'seo_title',
            'seo_keyword',
            'seo_desc',
        ],
    ]) ?>

</div>
