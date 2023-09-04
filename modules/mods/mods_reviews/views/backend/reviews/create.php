<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\modules\mods\mods_reviews\models\Review */

$this->title = 'Создать отзыв о товаре';
$this->params['breadcrumbs'][] = ['label' => 'Отзывы о товарах', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="review-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
