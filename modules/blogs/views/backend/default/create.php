<?php

/**
 * Blog create view.
 *
 * @var \yii\base\View $this View
 * @var \common\modules\blogs\models\backend\Blog $model Model
 * @var \backend\widgets\Box $box Box widget instance
 * @var array $statusArray Statuses array
 */

use backend\widgets\Box;
use common\modules\blogs\Module;

$this->title = Module::t('blogs', 'BACKEND_CREATE_TITLE');
$this->params['subtitle'] = Module::t('blogs', 'BACKEND_CREATE_SUBTITLE');
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
                'statusArray' => $statusArray,
                'box' => $box
            ]
        );
        Box::end(); ?>
    </div>
</div>