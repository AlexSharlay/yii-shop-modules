<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\mods\mods_reviews\models\Review */

$this->title = 'Модерация отзыва: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Reviews', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Модерация отзыва';
?>
<div class="review-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
