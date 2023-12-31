<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\mods\mods_job\models\backend\Job */

$this->title = 'Update Job: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Jobs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="job-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
