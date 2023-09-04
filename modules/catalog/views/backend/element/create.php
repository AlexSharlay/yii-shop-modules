<?php

use backend\widgets\Box;

/* @var $this yii\web\View */
/* @var $model common\modules\catalog\models\Element */


$this->title = 'Создать товар';
$this->params['subtitle'] = 'Создать товар';
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
                'modelPhoto' => $modelPhoto,
                'publishedArray' => $publishedArray,
                'categoryArray' => $categoryArray,
                'manufacturerArray' => $manufacturerArray,
                'measurementArray' => $measurementArray
            ]
        );
        Box::end(); ?>
    </div>
</div>