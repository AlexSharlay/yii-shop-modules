<div class="breadcrumb-line">
    <ul class="breadcrumb breadcrumb_upd">
        <li><a href="/">Главная</a></li>
        <li class="active">Заказ оформлен верно</li>
    </ul>
</div>
<div class="main_content cart_warp">
    <div class="warp">
        <h1 class="rubric akcent alpha"><i></i>Спасибо</h1>
        <div class="full_cart_warp" style="padding: 30px 0;">
            <? if ($order_id > 0) { ?>
                <p><b>Ваш номер для оплаты в системе ЕРИП: <?= $order_id ?></b></p>
                <p>Спасибо за покупку!</p>
                <p>В ближайшее время с вами свяжется наш специалист!</p>
            <? } else { ?>
                <p>Спасибо за покупку!</p>
                <p>В ближайшее время с вами свяжется наш специалист!</p>
            <? } ?>
        </div>
    </div>
</div>