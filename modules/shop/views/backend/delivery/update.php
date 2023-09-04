<?

use backend\widgets\Box;

$this->title = 'Доставки';
$this->params['subtitle'] = 'Изменить доставку';
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        'url' => ['index'],
    ],
    $this->params['subtitle']
];
$boxButtons = ['{cancel}'];

if (Yii::$app->user->can('BCreateShopDelivery')) {
    $boxButtons[] = '{create}';
}
if (Yii::$app->user->can('BDeleteShopDelivery')) {
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
                'paymentArray' => $paymentArray,
                'id_delivery' => $id_delivery,
            ]
        );
        Box::end(); ?>
    </div>
</div>
