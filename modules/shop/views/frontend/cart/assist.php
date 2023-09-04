<div class="breadcrumb-line">
    <ul class="breadcrumb breadcrumb_upd">
        <li><a href="/">Главная</a></li>
        <li class="active">Оплата заказа</li>
    </ul>
</div>
<div class="main_content cart_warp">
    <div class="warp">
        <h1 class="rubric akcent alpha"><i></i>Оплата заказа</h1>
        <div class="full_cart_warp" style="padding: 30px 0;">

            <p style="font-size: 16px;">Для оплаты заказа, перейдите в платежный терминал...</p>
            <form action="https://pay118.paysec.by/pay/order.cfm" method="POST">
                <input type="hidden" name="Merchant_ID" value="684351">
                <input type="hidden" name="OrderNumber" value="<?= $_SESSION['_assist']->id ?>">
                <input type="hidden" name="OrderAmount" value="<?= $_SESSION['_assist']->sum_with_delivery / 100 ?>">
                <input type="hidden" name="OrderCurrency" value="BYN">
                <input type="hidden" name="FirstName" value="">
                <input type="hidden" name="LastName" value="">
                <input type="hidden" name="MobilePhone" value="<?= $_SESSION['_assist']->phone ?>">
                <input type="hidden" name="Email" value="<?= $_SESSION['_assist']->email ?>">
                <input type="hidden" name="OrderComment" value="Оплата товара в магазине Kranik.by">

                <input type="hidden" name="TestMode" value="0">
                <input type="hidden" name="URL_RETURN" value="/">
                <input type="hidden" name="URL_RETURN_OK" value="/cart/order/">
                <input type="hidden" name="URL_RETURN_NO" value="/cart/order-error/">

                <input type="submit">
            </form>

        </div>
    </div>
</div>