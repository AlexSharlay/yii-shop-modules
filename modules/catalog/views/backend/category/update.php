<?
use backend\widgets\Box;

$this->title = 'Категории';
$this->params['subtitle'] = 'Редактировать категорию';
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        'url' => ['index'],
    ],
    $this->params['subtitle']
];
$boxButtons = ['{cancel}'];

if (Yii::$app->user->can('BCreateCatalogCategory')) {
    $boxButtons[] = '{create}';
}
if (Yii::$app->user->can('BDeleteCatalogCategory')) {
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
                'publishedArray' => $publishedArray,
                'showInMenuArray' => $showInMenuArray,
                'parentArray' => $parentArray,
                'parentFilterArray' => $parentFilterArray
            ]
        );
        Box::end(); ?>
    </div>
</div>
