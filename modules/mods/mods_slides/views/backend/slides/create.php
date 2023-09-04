<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\modules\mods\mods_slides\models\backend\Slides */

$this->title = 'Create Slides';
$this->params['breadcrumbs'][] = ['label' => 'Slides', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
