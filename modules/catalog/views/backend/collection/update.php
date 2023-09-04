<?
use backend\widgets\Box;

$this->title = 'Коллекции';
$this->params['subtitle'] = 'Изменить коллекцию';
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        'url' => ['index'],
    ],
    $this->params['subtitle']
];
$boxButtons = ['{cancel}'];

if (Yii::$app->user->can('BCreateCatalogCollection')) {
    $boxButtons[] = '{create}';
}
if (Yii::$app->user->can('BDeleteCatalogCollection')) {
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
                'categoryArray' => $categoryArray,
                'manufacturerArray' => $manufacturerArray,
            ]
        );
        Box::end(); ?>
    </div>
</div>
