<?
use backend\widgets\Box;

$this->title = 'Создать производителся';
$this->params['subtitle'] = 'Создать производителся';
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        'url' => ['index'],
    ],
    $this->params['subtitle']
];
$boxButtons = ['{cancel}'];

if (Yii::$app->user->can('BCreateCatalogManufacturer')) {
    $boxButtons[] = '{create}';
}
if (Yii::$app->user->can('BDeleteCatalogManufacturer')) {
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
                'countryArray' => $countryArray,
                'id_manufacturer' => $id_manufacturer,
            ]
        );
        Box::end(); ?>
    </div>
</div>
