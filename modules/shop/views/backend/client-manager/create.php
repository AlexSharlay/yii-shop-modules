<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\modules\shop\models\backend\ClientManager */

$this->title = 'Create Client Manager';
$this->params['breadcrumbs'][] = ['label' => 'Client Managers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-manager-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
