<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\mods\mods_manufacturer\models\backend\Manufacturer */

$this->title = 'Update mods_manufacturer: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'mods_manufacturer', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mods-mods_manufacturer-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
