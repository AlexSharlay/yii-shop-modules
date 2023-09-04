<? $bundle = \backend\themes\shop\pageAssets\catalog\import\one::register($this); ?>

<? $bundle = \backend\themes\shop\pageAssets\catalog\import\one::register($this); ?>

<div class="row">
    <div class="col-lg-12">

        <p>
            Каждый запрос должен содержать параметры login и password.<br/>
            login: 1C<br/>
            password: hG7rFDm95D<br/><br/>

            Пример: POST /backend/catalog/one/order/?order_id=123&status=1&login=1C&password=hG7rFDm95D<br/><br/>

            Со стороны сайта:<br/>
            - написать дополнительно логирование ошибок.<br/>
        </p>

        <hr/>

        <div class="content-group">
            <b>URL:</b> /backend/catalog/one/prices/<br/>
            <b>Метод:</b> POST<br/>
            <b>Описание:</b> Отправить на сайт все цены товаров из 1С. Если товар передаётся в валюте(RUR, USD,
            EUR), то цена будет переведена в белки по курсу нацбанка.<br/>
            <b>Параметр:</b> data<br/>
            <b>Переменные:</b> <br/>
            <div class="ml-20">
                code – id товара в 1С;<br/>
                price – цена товара;<br/>
                price_old – старая цена товара;<br/>
                currency – валюта(BYN, USD, EUR, RUB);<br/>
                status - статус товара (3-экспозиция и т.д.);<br/>
<!--                halva - можно ли продавать товар по рассрочке (0-нельзя, 1-можно);<br/>-->
                halva - предельная цена для продажи по рассрочке (карта Халва, Черепаха и т.п.);<br/>
                in_stock – количество на складе.
            </div>
            <b>Вид запроса:</b> <br/>
            <div id="json_editor_1">
                [
                {
                "code":1213,
                "price":150000,
                "price_old":160000,
                "currency":"BYN",
                "status":3,
                "halva":140000,
                "in_stock":5
                },
                {
                "code":1263,
                "price":150,
                "price_old":1600,
                "currency":"USD",
                "status":0,
                "halva":150,
                "in_stock":4
                },
                {
                "code":1273,
                "price":100,
                "price_old":160,
                "currency":"EUR",
                "status":0,
                "halva":200,
                "in_stock":3
                },
                {
                "code":1253,
                "price":15000,
                "price_old":16000,
                "currency":"RUB",
                "status":0,
                "halva":10000,
                "in_stock":7
                }
                ]
            </div>
        </div>




    </div>
</div>

