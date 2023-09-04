<?
use backend\widgets\Box;

$this->title = 'Изменить товар';
$this->params['subtitle'] = 'Изменить товар';
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        'url' => ['index'],
    ],
    $this->params['subtitle']
];
$boxButtons = ['{cancel}'];

if (Yii::$app->user->can('BCreateCatalogElement')) {
    $boxButtons[] = '{create}';
}
if (Yii::$app->user->can('BDeleteCatalogElement')) {
    $boxButtons[] = '{delete}';
}
$boxButtons = !empty($boxButtons) ? implode(' ', $boxButtons) : null; ?>

<div id="message" class="mt-20">
    <div></div>
</div>

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
                'modelPhoto' => $modelPhoto,
                'publishedArray' => $publishedArray,
                'categoryArray' => $categoryArray,
                'manufacturerArray' => $manufacturerArray,
                'measurementArray' => $measurementArray,
                'photos' => $photos,
                'tab' => $tab,
                'complects' => $complects,
                'models' => $models,
                'parent' => $parent,
            ]
        );
        Box::end(); ?>
    </div>
</div>