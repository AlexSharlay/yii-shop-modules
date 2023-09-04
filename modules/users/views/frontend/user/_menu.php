<ul class="dropdown-menu" style="display: block; position: static; width: 100%; margin-top: 0; float: none;">
    <li class="dropdown-header"><i class="icon-menu7"></i> Личный кабинет</li>
    <li <?= (Yii::$app->controller->module->module->requestedRoute == 'users/user/update') ? 'class="active"' : '';?>><a href="/my/settings/update/"><i class="icon-cog3"></i> Профиль</a></li>
    <!--li <?= (Yii::$app->controller->module->module->requestedRoute == 'users/user/discounts') ? 'class="active"' : '';?>><a href="/my/settings/discounts/"><i class="icon-coins"></i> Скидки</a></li-->
    <li <?= (Yii::$app->controller->module->module->requestedRoute == 'shop/order/orders') ? 'class="active"' : '';?>><a href="/my/orders/"><i class="icon-bag"></i> Заказы</a></li>
    <li class="divider"></li>
    <li><a href="/logout/">Выход</a></li>
</ul>

