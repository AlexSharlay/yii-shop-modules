<?php

/**
 * Blog update view.
 *
 * @var yii\base\View $this View
 * @var common\modules\blogs\models\backend\Blog $model Model
 * @var \backend\widgets\Box $box Box widget instance
 * @var array $statusArray Statuses array
 */

use backend\widgets\Box;
use common\modules\blogs\Module;

$this->title = Module::t('blogs', 'BACKEND_UPDATE_TITLE');
$this->params['subtitle'] = Module::t('blogs', 'BACKEND_UPDATE_SUBTITLE');
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        'url' => ['index'],
    ],
    $this->params['subtitle']
];
$boxButtons = ['{cancel}'];

if (Yii::$app->user->can('BCreateBlogs')) {
    $boxButtons[] = '{create}';
}
if (Yii::$app->user->can('BDeleteBlogs')) {
    $boxButtons[] = '{delete}';
}
$boxButtons = !empty($boxButtons) ? implode(' ', $boxButtons) : null; ?>
<div class="row">
    <div class="col-sm-12">
        <?php $box = Box::begin(
            [
                'title' => $this->params['subtitle'],
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
