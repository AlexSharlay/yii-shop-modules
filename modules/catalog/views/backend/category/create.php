<?php

use backend\widgets\Box;
use common\modules\catalog\Module;

/* @var $this yii\web\View */
/* @var $model common\modules\catalog\models\Category */


$this->title = 'Создать категорию';
$this->params['subtitle'] = 'Создать категорию';
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
                'publishedArray' => $publishedArray,
                'showInMenuArray' => $showInMenuArray,
                'parentArray' => $parentArray,
                'parentFilterArray' => $parentFilterArray,
            ]
        );
        Box::end(); ?>
    </div>
</div>