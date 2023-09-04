<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>








<div class="breadcrumbs"><ul><li><a href="/">Главная</a> ></li><li><a href="/shopping_cart/">Корзина</a> ></li><li><span class="breadcrumbs_end">Оформление заказа</span></li></ul></div>



<style>
    .cart_error {
        font-weight: normal;
        font-size: 14px;
        color: #c21310;
        padding: 20px;
        margin: 20px 0;
        border: 1px solid #c21310;
        background: #fcfcfc;
        clear: both;
    }

    .cart_error p {
        margin-bottom: 10px;
    }

    .cart_error ul {
        margin-left: 20px;
    }

    .cart_error ul li{
        line-height: 24px;
    }
</style>

<div class="main_content cart_warp">
    <div class="warp">
        <h1 class="rubric akcent alpha"><i></i>Оформление заказа</h1>

<!--
        <div class="full_cart_warp">
            <table class="full_cart">
                <tr>
                    <th class="tcenter">Фото</th>
                    <th class="tleft">Название товара</th>
                    <th class="tcenter">Цена за шт.</th>
                    <th class="tcenter">Количество</th>
                    <th class="tcenter">Итого</th>
                    <th class="tcenter"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></th>
                </tr>
                <tbody class="tshad">

                <tr>
                    <td class="fcart_product_img tcenter">
                        <img src="/media/files/products/small-1489130223-STD500.400.jpg" alt=""/>
                    </td>
                    <td class="fcart_prod_name">
                        <b>
                            <a style="color:#000;" href="/moyki-kuhonnye/moyki-iz-nerghaveyuschey-stali/moyka-nakladnaya-std500400-4c-c-/">Мойка накладная STD500.400</a>
                        </b>
                        <p class="brand_name"><span class="brand">Бренд:</span> UKINOX</p>
                        <p class="articul">Код: <span class="articul_no">128348</span></p>
                    </td>
                    <td class="fcart_price_item tcenter">
                        <span style="font-size: 14px;">26 руб. 87 коп.</span>
                    </td>
                    <td class="fcart_product_qn tcenter">
                        1
                    </td>
                    <td class="fcart_price_total tcenter">
                        <span style="font-size: 14px;">26 руб. 87 коп.</span>
                    </td>
                </tr>
                <tfoot class="tshad">
                <tr>
                    <td colspan="3"></td>
                    <td class="sendcart_total_descr">Итого: </td>
                    <td class="sendcart_total">
                        <span style="font-size: 12px !important;"><span style="font-size: 14px;">26 руб. 87 коп.</span></span><br/>
                    </td>
                </tr>
                <tr id="withDiscountRow" style="display: none;">
                    <td colspan="3"></td>
                    <td class="sendcart_total_descr" style="color:red;">Всего: </td>
                    <td class="sendcart_total">
                        <span id="withDiscountPrice"></span>
                    </td>
                </tr>
                </tfoot>
                </tbody>
            </table>
        </div>
-->

        <br />
        <div class="table-responsive full_cart_warp">
            <table class="table table-hover table-striped " style="border-top-width: 1px;">
                <thead>
                <tr>
                    <th class="tcenter">Фото</th>
                    <th class="tleft">Название товара</th>
                    <th class="tcenter">Цена за шт.</th>
                    <th class="tcenter">Количество</th>
                    <th class="tcenter">Итого</th>
                    <th class="tcenter"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($session['cart'] as $id => $item):?>
                    <tr>
                        <td class="fcart_product_img tcenter"><?= \yii\helpers\Html::img("@web/statics/catalog/photo/images/{$item['img']}", ['alt' => $item['title'], 'height' => 70]) ?></td>
                        <td class="fcart_prod_name">
                            <a style="color:#000; font-weight: bold; href="<?= Url::to(['catalog/default/product', 'id' => $id])?>"><?= $item['title']?></a>
                            <p class="brand_name"><span class="brand">Бренд: </span></p>
                            <p class="articul">Код: <span class="articul_no"><?= $item['article']?></span></p>
                        </td>
                        <td class="fcart_price_item tcenter"><?= price($item['price'])?></td>
                        <td class="fcart_product_qn tcenter"><?= $item['qty']?></td>
                        <td class="fcart_price_total tcenter"><?= price($item['qty'] * $item['price'])?></td>
                        <td class="tcenter"><span data-id="<?= $id?>" class="glyphicon glyphicon-remove text-danger del-item" aria-hidden="true"></span></td>
                    </tr>
                <?php endforeach?>
<!--                <tfoot class="tshad">-->
                <tr>
                    <td colspan="3"></td>
                    <td class="sendcart_total_descr">Итого: </td>
                    <td colspan="2" class="fcart_product_qn tcenter"><?= $session['cart.qty']?></td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td class="sendcart_total_descr">На сумму: </td>
                    <td colspan="2" class="fcart_price_total tcenter"><?= price($session['cart.sum'])?></td>
                </tr>
<!--                </tfoot>-->
                </tbody>
            </table>
        </div>




        <br />
        <h2 class="head_sendcart">Персональные данные</h2>
        <?php $form = ActiveForm::begin()?>
        <?= $form->field($order, 'name')?>
        <?= $form->field($order, 'email')?>
        <?= $form->field($order, 'phone')?>
        <?= $form->field($order, 'info')?>


        <?= Html::submitButton('Оформить заказ', ['class' => 'btn smb'])?>
        <?php ActiveForm::end()?>


        <div class="form_sendcart_warp">
            <form class="sendcart_form" action="/shopping_cart/order/" onsubmit="return checkContactForm();" method="POST">
                <h2 class="head_sendcart">Способы доставки</h2>
                <fieldset class="radio">
                    <div class="line_radio">
                        <label>
                            <input type="radio" name="form[delivery_radio]" id="DR" value="7" checked  onclick="sum(150000);"/>
                            <b>Доставка по Минску <span style="font-size: 14px;">15 руб. 00 коп.</span></b><br>
                            <p>Если Ваш заказ составляет сумму от 20 до 50 руб., доставка по г. Минску в пределах МКАД составит сумму 15 руб.</p>
                            <p>Вы можете ознакомиться с тарифами на доставку&nbsp;<a href="../../../dostavka/">тут</a></p>													</label>
                    </div>
                    <div class="line_radio">
                        <label>
                            <input type="radio" name="form[delivery_radio]" id="DR" value="12"   onclick="sum(0);"/>
                            <b>Самовывоз <span style="font-size: 14px;">0 руб. 00 коп.</span></b><br>
                            <p><a title="Посмотреть на карте" href="../../../contacts/" target="_blank">а/г. Озерцо,<span>&nbsp;ул. Центральная 1Б (за авторынком "Малиновка")<br /></span></a></p>														<span><span style="color: red;">Скидка -2% (действует только на товар не участвующий в акции)</span></span>
                        </label>
                    </div>
                    <div class="line_radio">
                        <label>
                            <input type="radio" name="form[delivery_radio]" id="DR" value="16"   onclick="sum(300000);"/>
                            <b>Доставка по Беларуси <span style="font-size: 14px;">30 руб. 00 коп.</span></b><br>
                            <p>Стоимость доставки рассчитывается сотрудником интернет-магазина согласно тарифам, описанным в разделе</p>
                            <p>Вы можете ознакомиться с тарифами на доставку&nbsp;<a href="../../../dostavka/">тут</a></p>													</label>
                    </div>
                </fieldset>

                <script type="text/javascript">
                    $(document).ready(function(){
                        sum(150000);
                    });
                </script>


                <script>
                    $(function() {
                        $('#phone1').inputmask("+375 (99) 999-99-99");
                    });
                </script>
                <h2 class="head_sendcart">Способы оплаты </h2>
                <fieldset>
                    <div class="line_radio">
                        <label>
                            <input type="radio" name="form[payment]" value="cash"  checked="checked"/>
                            <b>Наличными</b><br/>
                            Оплата наличными средствами при получении товара.
                        </label>
                    </div>


                    <div class="line_radio">
                        <label>
                            <input type="radio" name="form[payment]" value="assist"  />
                            <b>Банковской картой онлайн</b> <br/>
                            <span>Вы можете оплатить товар с помощью банковской карточки.</span> <br/>
                            <span><span style="color: red;">Скидка -2% (действует только на товар не участвующий в акции)</span></span>
                        </label>
                    </div>

                    <div class="line_radio">
                        <label>
                            <input type="radio" name="form[payment]" value="assist_courier"  />
                            <b>Банковской картой</b>
                        </label>
                    </div>
                    <div class="line_radio">
                        <label>
                            <input type="radio" name="form[payment]" value="cashless"  />
                            <b>Безналичный расчет</b>
                        </label>
                    </div>

                    <div class="line_radio">
                        <label>
                            <input type="radio" name="form[payment]" value="erip"  />
                            <b>ЕРИП</b><br/>
                            <span><span style="color: red;">Цена +2%</span></span>
                        </label>
                    </div>


                    <div class="line_text">
                        <input type="text" placeholder="промокод" name="stock_code" id="stock_code" value="">
                        <span>Применить</span>
                    </div>

                </fieldset>

                <h2 class="head_sendcart">Персональные данные</h2>
                <fieldset class="sendcart_personal">
                    <legend class="sendcart_section_head">Контактная информация</legend>

                    <div class="line_text">
                        <value><span class="pse_label">Ваше имя: <span class="mark">*</span></span>
                            <input type="text" name="form[family]" id="family" value=""/></value>
                    </div>
                    <div class="line_text">
                        <label><span class="pse_label">Email: <span class="mark"></span></span>
                            <input type="text" name="form[email]" placeholder="Для отправки Вам копии заказа" id="email" value=""/></label>
                    </div>
                    <div class="line_text">
                        <label><span class="pse_label">Телефон: <span class="mark">*</span></span>
                            <input style="float: none;" type="text" name="form[phone1]" id="phone1" value=""/>
                    </div>
                </fieldset>
                <fieldset class="sendcart_address">
                    <legend class="sendcart_section_head">
                        Адрес <span class="sec_info">(если информации нет, установите галочку в поле “Нет”)</span>
                    </legend>

                    <div class="line_text">
                        <label>
                            <span class="pse_label">Город: <span class="mark">*</span></span>
                            <input type="text" name="form[city]" id="city" value=""/>
                        </label>
                        <label>
                            <span class="pse_label">Улица: <span class="mark">*</span></span>
                            <input type="text" name="form[street_other]" id="street_other" value=""/>
                        </label>
                        <label>
                            <span class="pse_label">Дом: <span class="mark">*</span></span>
                            <input type="text" name="form[home]" id="home" value=""/>
                        </label>
                        <label>
                            <span class="pse_label">Корпус:</span>
                            <input type="text" name="form[corpus]" id="corpus" value=""/>
                        </label>
                        <label>
                            <input type="checkbox" onchange="chkforms();" name="form[clinet_nokorpus]" class="styled" id="clinet_nokorpus" />
                            <span class="pse_label">Нет</span>
                        </label>
                    </div>
                    <div class="line_text">
                        <label>
                            <span class="pse_label">Подъезд:</span>
                            <input type="text" name="form[home_level]" id="home_level" value=""/>
                        </label>
                        <label class="check_label"><input type="checkbox" onchange="chkforms();" name="form[clinet_noentrance]" class="styled" id="clinet_noentrance" />Нет
                        </label>
                        <label>
                            <span class="pse_label">Код <br />подъезда:</span>
                            <input type="text" name="form[home_level_code]" id="client_code" value=""/>
                        </label>
                        <label class="check_label">
                            <input type="checkbox" onchange="chkforms();" name="form[clinet_nocode]" class="styled" id="clinet_nocode" />Нет</label>
                        <label class="c1">
                            <span class="pse_label">Квартира: <span class="mark">*</span></span>
                            <input type="text" name="form[flat]" id="flat" value=""/>
                        </label>
                        <label class="check_label">
                            <input type="checkbox" onchange="chkforms();" name="form[clinet_noroom]" class="styled" id="clinet_noroom" />Нет</label>
                    </div>
                    <div class="line_text">
                        <label>
                            <span class="pse_label">Этаж:</span>
                            <input type="text" name="form[stage]" id="stage" value=""/>
                        </label>
                        <label class="check_label">
                            <input type="checkbox" onchange="chkforms();" name="form[clinet_nofloor]" class="styled" id="clinet_nofloor" />Нет</label>
                        <label>
                            <span class="pse_label c2">Лифт: <span class="mark">*</span></span>
                        </label>
                        <label class="check_label">
                            <input type="checkbox" onchange="chkforms();" name="form[clinet_lift]" id="clinet_lift" checked="checked" class="styled"/>Нет</label>
                    </div>

                </fieldset>
                <fielset class="sendcart_dop">
                    <legend class="sendcart_section_head">Дополнительная информация</legend>
                    <textarea rows="10" cols="45" name="form[info]"></textarea>
                </fielset>
                <h2 class="head_sendcart">Откуда вы узнали о нас?</h2>
                <!-- Откуда вы узнали о нас? -->
                <div style="margin:20px 0;" class="form_how">
                    <input type="radio" name="form[how]" value="1k.by"> 1k.by<br>
                    <input type="radio" name="form[how]" value="kupi.tut.by"> kupi.tut.by<br>
                    <input type="radio" name="form[how]" value="shop.by"> shop.by<br>
                    <input type="radio" name="form[how]" value="deal.by"> deal.by<br>
                    <input type="radio" name="form[how]" value="onliner.by"> onliner.by - Если Вам все понравилось - Оставьте положительный отзыв на <a href=”https://7743.shop.onliner.by/#reviews”>onliner.by</a> и получите в подарок фирменную кружку<br>
                    <input type="radio" name="form[how]" value="реклама в Google"> реклама в Google<br>
                    <input type="radio" name="form[how]" value="реклама в Yandex"> реклама в Yandex<br>
                    <input type="radio" name="form[how]" value="реклама в магазине"> реклама в магазине<br>
                    <input type="radio" name="form[how]" value="shop.by"> shop.by<br>
                    <input type="radio" name="form[how]" value="реклама с билборда"> реклама с билборда<br>
                    <input type="radio" name="form[how]" value="покупал ранее, посоветовали"> покупал ранее, посоветовали<br>
                    <input type="radio" name="form[how]" value="не помню"> не помню<br>
                    <input type="text" name="form[how_text]" value="" placeholder="Свой вариант" style="margin: 5px;">
                </div>
                <!-- /откуда вы узнали о нас? -->

                <span class="mark">*</span>
                <div class="g-recaptcha" data-sitekey="6LcvtfYSAAAAAGeLalj_w4sKZPpGKUFAcEWNjymP"></div>

                <br/>

                <div class="btn_warp">
                    <button type="submit" class="btn smb" onClick="_gaq.push(['_trackEvent', 'checkout2', 'oformit-zakaz2',,, false]);">Оформить заказ</button>
                </div>

            </form>
        </div>
        <br />


    </div>
</div>






end form --------------------------------------------------------------------------------------------------
<hr>
--------------------------------------------------------------------------------------------------

<div class="container_cart">
    <?php if( Yii::$app->session->hasFlash('success') ): ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo Yii::$app->session->getFlash('success'); ?>
        </div>
    <?php endif;?>

    <?php if( Yii::$app->session->hasFlash('error') ): ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo Yii::$app->session->getFlash('error'); ?>
        </div>
    <?php endif;?>



    <?php if(!empty($session['cart'])): ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th>Фото</th>
                    <th>Артикул</th>
                    <th>Наименование</th>
                    <th>Кол-во</th>
                    <th>Цена</th>
                    <th>Сумма</th>
                    <th><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($session['cart'] as $id => $item):?>
                    <tr>
                        <td><?= \yii\helpers\Html::img("@web/statics/catalog/photo/images/{$item['img']}", ['alt' => $item['title'], 'height' => 50]) ?></td>
                        <td><a href="<?= Url::to(['catalog/default/product', 'id' => $id])?>"><?= $item['article']?></a></td>
                        <td><?= $item['title']?></td>
                        <td><?= $item['qty']?></td>
                        <td><?= $item['price']?></td>
                        <td><?= $item['qty'] * $item['price']?></td>
                        <td><span data-id="<?= $id?>" class="glyphicon glyphicon-remove text-danger del-item" aria-hidden="true"></span></td>
                    </tr>
                <?php endforeach?>
                <tr>
                    <td colspan="6">Итого: </td>
                    <td><?= $session['cart.qty']?></td>
                </tr>
                <tr>
                    <td colspan="6">На сумму: </td>
                    <td><?= $session['cart.sum']?></td>
                </tr>
                </tbody>
            </table>
        </div>

        <hr/>
        <?php $form = ActiveForm::begin()?>
        <?= $form->field($order, 'name')?>
        <?= $form->field($order, 'email')?>
        <?= $form->field($order, 'phone')?>
        <?= $form->field($order, 'address')?>
        <?= Html::submitButton('Заказать', ['class' => 'btn btn-success'])?>
        <?php ActiveForm::end()?>
    <?php else: ?>
        <h3>Корзина пуста</h3>
    <?php endif;?>
</div>