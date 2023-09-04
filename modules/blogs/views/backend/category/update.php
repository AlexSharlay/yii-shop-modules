<?php

use yii\helpers\Html;
use backend\widgets\Box;

/* @var $this yii\web\View */
/* @var $model common\modules\blogs\models\backend\Category */

$this->params['breadcrumbs'] = [
    [
        'label' => 'Категории',
        'url' => ['index'],
    ],
    [
        'label' => $model->title,
        'url' => ['view', 'id' => $model->id]
    ],
    [
        'label' => 'Изменение категории: ' . ' ' . $model->title
    ]
];
$boxButtons = ['{cancel}'];

if (Yii::$app->user->can('BCreateBlogsCategory')) {
    $boxButtons[] = '{create}';
}
if (Yii::$app->user->can('BDeleteBlogsCategory')) {
    $boxButtons[] = '{delete}';
}
$boxButtons = !empty($boxButtons) ? implode(' ', $boxButtons) : null;
?>
<div class="row">
    <div class="col-sm-12">
        <?php $box = Box::begin(
            [
                'title' => 'Изменение категории: ' . ' ' . $model->title,
                'renderBody' => false,
                'options' => [
                    'class' => 'box-success'
                ],
                'bodyOptions' => [
                    'class' => 'table-responsive'
                ],
                'buttonsTemplate' => $boxButtons
            ]
        );
        echo $this->render(
            '_form',
            [
                'model' => $model,
                'statusArray' => $statusArray,
                'box' => $box
            ]
        );
        Box::end(); ?>
    </div>
</div>
