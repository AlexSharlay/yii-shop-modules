<?php
$bundle = \frontend\themes\shop\pageAssets\shop\order::register($this);

use kartik\datetime\DateTimePicker;
use yii\web\JsExpression;

?>
<script>
    window.PageOrder = 1;
</script>

<div id="order" data-bind="if: lines().length">
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">Оформление заказа</h5>
        </div>
        <div class="table-responsive">
            <table class="table" id="orderProducts">
                <thead>
                <tr>
                    <th></th>
                    <th>Наименование</th>
                    <th>Количество</th>
                    <th>Цена с НДС, шт</th>
                    <th>Цена с НДС, итого</th>
                    <th></th>
                </tr>
                </thead>
                <tbody data-bind='foreach: lines'>
                <tr>
                    <td><img class="img-circle img-sm" alt="" data-bind="attr: { 'src' : img }"></td>
                    <td>
                        <a class="media-heading" data-bind="attr: { 'href' : url }">
                            <span data-bind="text: product"></span>
                            <span data-bind="if: kit"> (Сборка)</span>
                        </a>
                        <div data-bind="if: kits().length">
                            <div data-bind="foreach: { data: kits, as: 'k' }">
                                + <span data-bind="text: k.title"></span>
                            </div>
                        </div>
                    </td>
                    <td><input data-bind='numeric, value: quantity, valueUpdate: "afterkeydown"' type="number" min="1"/>
                    </td>
                    <td data-bind='text: formatCurrency(priceGet())'></td>
                    <td data-bind='text: formatCurrency(subtotal())'></td>
                    <td><span class="icon icon-close2 del-from-cart"
                              data-bind="attr: { 'id' : id }, click: $parent.deleteProduct"></span></td>
                </tr>
                </tbody>
            </table>
            <div class="orderTotal">
                Итого: <span data-bind='text: formatCurrency(grandTotal())'>
            </div>
        </div>
        <div class="panel-body row form-group" style="padding-bottom:0px;">
            <div class="col-lg-3 col-md-3 col-xs-3 col-sm-4">
                <input type="hidden" id="city_id" value="<?= $cityData['id']; ?>">
                <input type="hidden" id="city_title" value="<?= $cityData['city']; ?>">
                <label class="">
                    <input type="radio" name="delivery" value="1" class="styled" checked="checked">
                    Доставка<br/>
                    (<b><?= $cityData['city']; ?></b>: <?= $cityData['day'] ?>)
                    <span class="text-default" data-toggle="modal" data-target="#modal_city"
                          style="border-bottom: 1px dotted #999; cursor: pointer;">Изменить</span>
                </label>
                <?
                // Не показываем дни вне доставки
                $daysOfWeekDisabled = [];
                if (strstr(mb_strtolower($cityData['day']), 'понед') === false) $daysOfWeekDisabled[] = 1;
                if (strstr(mb_strtolower($cityData['day']), 'вторн') === false) $daysOfWeekDisabled[] = 2;
                if (strstr(mb_strtolower($cityData['day']), 'сред') === false) $daysOfWeekDisabled[] = 3;
                if (strstr(mb_strtolower($cityData['day']), 'четве') === false) $daysOfWeekDisabled[] = 4;
                if (strstr(mb_strtolower($cityData['day']), 'пятни') === false) $daysOfWeekDisabled[] = 5;
                if (strstr(mb_strtolower($cityData['day']), 'субот') === false) $daysOfWeekDisabled[] = 6;
                if (strstr(mb_strtolower($cityData['day']), 'воскр') === false) $daysOfWeekDisabled[] = 0;

                // Сегодняшний день
                $dayNow = jddayofweek(cal_to_jd(CAL_GREGORIAN, date("m"), date("d"), date("Y")), 0);


                if ($cityData['city'] == 'Минск') {
                    if (date('H') < 12) {
                        $startDate = date("d-m-Y H:i");
                        $hoursDisabled = array_merge(range(0, 13), range(18, 24));
                    } else {
                        $startDate = date("d-m-Y H:i", strtotime(date('d-m-Y H:i') . " +1 day -" . date('H') . ' hour -' . date('i') . ' minute'));
                        $hoursDisabled = array_merge(range(0, 9), range(18, 24));
                    }
                } else {
                    $startDate = (date('H') < 16)
                        ? date("d-m-Y H:i", strtotime(date('d-m-Y H:i') . " +1 day -" . date('H') . ' hour -' . date('i') . ' minute'))
                        : date("d-m-Y H:i", strtotime(date('d-m-Y H:i') . " +2 day -" . date('H') . ' hour -' . date('i') . ' minute'));
                }

                echo DateTimePicker::widget([
                    'name' => 'delivery1',
                    'type' => DateTimePicker::TYPE_INPUT,
                    'pluginOptions' => [
                        'todayHighlight' => true,
                        'todayBtn' => true,
                        'autoclose' => true,
                        'format' => 'dd-mm-yyyy hh:ii',
                        'startDate' => $startDate,
                        'endDate' => date("d-m-Y H:i", strtotime(date('d-m-Y') . " +1 month")),
                        'daysOfWeekDisabled' => $daysOfWeekDisabled,
                        'hoursDisabled' => $hoursDisabled,
                    ]
                ]);
                ?>
                Время учитывается как желаемое


                <div id="address" class="mt-10" data-bind="if: deliveries().length">
                    <b>Адрес доставки:</b>
                    <div data-bind="foreach: {data: deliveries, as: 'delivery'}">
                        <div>
                            <input type="radio" name="deliveries" data-bind="attr: { 'value' : delivery.title }"/>
                            <span data-bind="text: delivery.title"></span>
                            <!-- ko if: delivery.id -->
                            <span class="icon icon-cross3 del-from-cart"
                                  data-bind="attr: { 'id' : delivery.id }, click: $parent.deleteDelivery"></span>
                            <!-- /ko -->
                        </div>
                    </div>
                </div>
                <div class="row mt-10">
                    <div class="col-lg-8 col-md-8 col-xs-8 col-sm-7">
                        <input class="form-control" data-bind='value: deliveryToAdd, valueUpdate: "afterkeydown"'
                               placeholder="Новый адрес"/>
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-4 col-sm-5">
                        <button class="btn" data-bind="click: vmCart.addDelivery">Добавить</button>
                    </div>
                </div>

            </div>
            <div class="col-lg-3 col-md-3 col-xs-3 col-sm-4">
                <label class="">
                    <input type="radio" name="delivery" value="2" class="styled">
                    Самовывоз<br/>
                    (пн-пт 8:00-20:00, сб-вс 9:00-17:30)
                </label>
                <?
                echo DateTimePicker::widget([
                    'name' => 'delivery2',
                    'type' => DateTimePicker::TYPE_INPUT,
                    'value' => date("d-m-Y H:i", strtotime(date('d-m-Y H:i') . ' +15 minute')),
                    'pluginOptions' => [
                        'todayBtn' => false,
                        'autoclose' => true,
                        'format' => 'dd-mm-yyyy hh:ii',
                        'startDate' => date("d-m-Y H:i", strtotime(date('d-m-Y H:i') . ' +15 minute')),
                        'endDate' => date("d-m-Y H:i", strtotime(date('d-m-Y') . " +1 month")),
                        //'disabledTimeIntervals' => new JsExpression("[[moment().hour(0).minutes(0), moment().hour(6).minutes(0)], [moment().hour(21).minutes(0), moment().hour(23).minutes(0)]]"),
                        //'disabledTimeIntervals' => new JsExpression("[[moment({ h: 0 }), moment({ h: 8 })], [moment({ h: 18 }), moment({ h: 24 })]]"),
                        'hoursDisabled' => (in_array(getdate()['weekday'], ['Saturday', 'Sunday'])) ? array_merge(range(0, 8), range(17, 24)) : array_merge(range(0, 7), range(20, 24)),
                    ]
                ]);

                ?>
            </div>
        </div>

<?php //if (Yii::$app->user->getId() == 173) {?>
        <div class="panel-body">
            <p><input id="checkout_order" type="checkbox" name="checkout_order" value="yes"> Требуется согласование с менеджером (товар не резервируется).</p>
        </div>
<?php //}?>


        <div data-bind="if: grandTotal() >= <?= Yii::$app->params['minDeliveryPrice']; ?>">
            <div class="panel-body">
                <p>Во избежание <strong>автоматической блокировки аккаунта</strong> просим оплачивать заказ не позднее <strong>3 дней</strong> с момента оформления.</p>
                <p> При оформлении заказа, Вы <span style="text-decoration:underline; font-weight: bold;">соглашаетесь с <a href="/public-offer/" target="_blank">условиями</a> договора.</span></p>
                <input id="checkout" class="btn btn-success btn-sm" value="Оформить заказ"/>
            </div>
        </div>
        <div data-bind="if: grandTotal() < <?= Yii::$app->params['minDeliveryPrice']; ?>">
            <div class="panel-body">
                <span class="label bg-danger">Оформление невозможно по причине низкой суммы заказа. Минимальная сумма заказа: <?= number_format(Yii::$app->params['minDeliveryPrice'] / 100, 2, '.', ' '); ?>
                    BYN</span>
            </div>
        </div>
        <div data-bind="if: grandTotal() > <?= Yii::$app->params['maxDeliveryPrice']; ?>">
            <div class="panel-body">
                <span class="label bg-danger">Оформление невозможно по причине высокой суммы заказа. Максимальная сумма заказа: <?= number_format(Yii::$app->params['maxDeliveryPrice'] / 100, 2, '.', ' '); ?>
                    BYN</span>
            </div>
        </div>

        <div data-bind="if: grandTotal() < 50000">
            <div class="panel-body">
                <span class="label bg-danger">При сумме заказа до <?= number_format(500, 2, '.', ' '); ?> BYN доставка платная.</span>
            </div>
        </div>

    </div>
</div>


<!-- City modal -->
<div id="modal_city" class="modal fade">
    <div class="modal-dialog modal-xs">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Выбор города доставки</h5>
            </div>

            <div class="modal-body">

                <select class="selectpicker bootstrap-select" data-live-search="true" data-width="100%">
                    <? $data = \common\modules\shop\models\backend\UserCity::getCities(); ?>
                    <? foreach ($data as $region => $cities) { ?>
                        <optgroup label="<?= $region; ?>">
                            <? foreach ($cities as $id => $city) { ?>
                                <option value="<?= $id; ?>"><?= $city; ?></option>
                            <? } ?>
                        </optgroup>
                    <? } ?>
                </select>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">Отменить</button>
                <button type="button" class="btn btn-primary" id="changeCity">Выбрать</button>
            </div>
        </div>
    </div>
</div>
<!-- /city modal -->