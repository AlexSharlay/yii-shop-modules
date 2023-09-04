<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\modules\shop\models\backend\UserCity */

$this->title = 'Create User City';
$this->params['breadcrumbs'][] = ['label' => 'User Cities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-city-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
