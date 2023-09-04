<?php

use backend\widgets\Box;

/* @var $this yii\web\View */
/* @var $model common\modules\shop\models\Delivery */


$this->title = 'Доставки';
$this->params['subtitle'] = 'Создать доставку';
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        'url' => ['index'],
    ],
    $this->params['subtitle']
]; ?>
<div class="row">
    <div class="col-sm-12">
        <?php $box = Box::begin(
            [
                'title' => $this->params['subtitle'],
                'renderBody' => false,
                'options' => [
                    'class' => 'box-primary'
                ],
                'bodyOptions' => [
                    'class' => 'table-responsive'
                ],
                'buttonsTemplate' => '{cancel}'
            ]
        );
        echo $this->render(
            '_form',
            [
                'model' => $model,
            ]
        );
        Box::end(); ?>
    </div>
</div>