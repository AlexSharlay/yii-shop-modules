<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\modules\mods\mods_news\models\backend\News */

$this->title = 'Create News';
$this->params['breadcrumbs'][] = ['label' => 'News', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
