<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\modules\mods\mods_review\models\backend\Review */

$this->title = 'Create Review';
$this->params['breadcrumbs'][] = ['label' => 'Reviews', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="review-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
