<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\mods\mods_slides\models\backend\Slides */

$this->title = 'Update Slides: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Slides', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="news-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
