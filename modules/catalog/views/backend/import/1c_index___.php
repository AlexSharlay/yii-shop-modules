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
                currency – валюта(BYR, USD, EUR, RUB);<br/>
                stock – сколько на складе.
            </div>
            <b>Вид запроса:</b> <br/>
            <div id="json_editor_1">
                [
                {
                "code":1213,
                "price":150000,
                "currency":"BYR",
                "in_stock":5
                },
                {
                "code":1263,
                "price":150,
                "currency":"USD",
                "in_stock":4
                },
                {
                "code":1273,
                "price":100,
                "currency":"EUR",
                "in_stock":3
                },
                {
                "code":1253,
                "price":15000,
                "currency":"RUB",
                "in_stock":7
                }
                ]
            </div>
        </div>

        <hr/>

        <div class="content-group">
            <b>URL:</b> /backend/catalog/one/orders/{status}<br/>
            <b>Метод:</b> GET<br/>
            <b>Описание:</b> Получить с сайта список новых заказов.<br/>
            <b>Параметр:</b> data<br/>
            <b>Переменные:</b><br/>
            <div class="ml-20">
                '0' => 'Не оплачен. Не отгружен.',<br/>
                '1' => 'Не оплачен. Отгружен.',<br/>
                '2' => 'Оплачен. Не отгружен.',<br/>
                '3' => 'Оплачен. Отгружен.',<br/>
                '4' => 'Заявка отменена.',<br/>
                '5' => 'Оплата просрочена. Счет-фактура анулирована.',<br/>
                '6' => 'Внимание! Не весь товар на складе.',<br/>
                '7' => 'Не оплачен. Не отгружен. Обработан 1С',<br/>
                '8' => 'Ждёт согласования с менеджером. Обработан 1С',<br/>
                '9' => 'Ждёт согласования с менеджером.',<br/>
                '10' => 'Согласован с 1С',
            </div>
            <b>1 запрос - 1 ответ:</b>.<br/>
            <div class="ml-20">
                error - true (если всё ок false)<br/>
                message - сообщение об ошибке<br/>
                code - код ошибки
            </div>
            <b>Ответ в случае успеха:</b> <br/>
            <div id="json_editor_2">
                [
                {
                "order":"2",
                "products":
                [
                {
                "code_1c":"99762",
                "count":"1"
                "price":"21.56"
                },
                {
                "code_1c":"99763",
                "count":"2",
                "price":"30.50"
                }
                ],
                "status":"0",
                "firm":"Наименование компании",
                "ynp":"100000000",
                "deliveryTime": "",
                "deliveryType": "",
                "deliveryCity": "",
                "deliveryAddress": "",
                },
                {
                "order":"3",
                "products":
                [
                {
                "code_1c":"99762",
                "count":"1",
                "price":"30.50"
                }
                ],
                "status":"0",
                "firm":"Наименование компании",
                "ynp":"100000000",
                "deliveryTime": "",
                "deliveryType": "",
                "deliveryCity": "",
                "deliveryAddress": "",
                }
                ]
            </div>
            <b>Ответ, если ошибка:</b> <br/>
            <div id="json_editor_3">
                [
                {
                "error":true,
                "message":"\u041d\u0435\u0432\u0435\u0440\u043d\u044b\u0439 \u0441\u0442\u0430\u0442\u0443\u0441",
                "code":100
                }
                ]
            </div>
        </div>

        <hr/>

        <div class="content-group">
            <b>URL:</b> /backend/catalog/one/order/{order_id}{status}<br/>
            <b>Метод:</b> POST<br/>
            <b>Описание:</b> Отметить результат обработки заказа в 1С.<br/>
            <b>Параметр:</b> data<br/>
            <b>Переменные:</b><br/>
            <div class="ml-20">
                '0' => 'Не оплачен. Не отгружен.',<br/>
                '1' => 'Не оплачен. Отгружен.',<br/>
                '2' => 'Оплачен. Не отгружен.',<br/>
                '3' => 'Оплачен. Отгружен.',<br/>
                '4' => 'Заявка отменена.',<br/>
                '5' => 'Оплата просрочена. Счет-фактура анулирована.',<br/>
                '6' => 'Внимание! Не весь товар на складе.',<br/>
                '7' => 'Не оплачен. Не отгружен. Обработан 1С',<br/>
                '8' => 'Ждёт согласования с менеджером. Обработан 1С',<br/>
                '9' => 'Ждёт согласования с менеджером.',<br/>
                '10' => 'Согласован с 1С',
            </div>
            <b>1 запрос - 1 заказ</b><br/>
            <b>Вид запроса:</b> <br/>
            <b>Ответ, если ошибка:</b> <br/>
            <div id="json_editor_4">
                [
                {
                "error":true,
                "message":"\u041d\u0435\u0432\u0435\u0440\u043d\u044b\u0439 \u0441\u0442\u0430\u0442\u0443\u0441"
                }
                ]
            </div>
            <b>Ответ в случае успеха:</b> <br/>
            <div id="json_editor_5">
                [
                {
                "error":false,
                "message": ""
                }
                ]
            </div>

        </div>

<!--        <hr/>-->

        <!--div class="content-group">
            <b>URL:</b> /backend/catalog/one/products/ <span class="label bg-warning-400">Не готово</span><br/>
            <b>Метод:</b> POST<br/>
            <b>Описание:</b> Отправить на сайт все товары из 1С. Если товара нет на сайте он создаётся неопубликованным, если есть и отличается количество, то обновляется.<br/>
            <b>Переменные:</b><br/>
            <div class="ml-20">
                id товара в 1с<br/>
                артикул товара<br/>
                название товара<br/>
                цена товара<br/>
                количество товаров<br/>
                категория товара<br/>
                производитель<br/>
                комплект (тут вам виднее как указать связи)<br/>
                все фото (без понятия как это делается в 1с)<br/>
                (Поля для онлайнера)<br/>
                Info_manufacturer – информация о изготовителе<br/>
                Info_importer – информация о импортере<br/>
                Info_service – информация о сервисном центре
            </div>
            <b>Ответ, если ошибка:</b> <br/>
            <div id="json_editor_6">
[
{
    "error":true,
    "message":"\u041d\u0435\u0432\u0435\u0440\u043d\u044b\u0439 \u0441\u0442\u0430\u0442\u0443\u0441"
}
]
            </div>
            <b>Ответ в случае успеха:</b> <br/>
            <div id="json_editor_7">
[
{
    "error":false,
    "message": ""
}
]
            </div>
        </div-->

        <hr/>

        <div class="content-group">
            <b>URL:</b> /backend/catalog/one/invoices/{order_id}{filename}<br/>
            <b>Метод:</b> POST<br/>
            <b>Описание:</b> Отметить на сайте что заказ с указанным order_id обработан в 1С.<br/>
            <b>Параметр:</b> data<br/>
            <b>Переменные:</b><br/>
            <div class="ml-20">
                order_id – id заказа на сайте;<br/>
                filename = имя файла - счёт на оплату.pdf
            </div>
            <b>Ответ, если ошибка:</b> <br/>
            <div id="json_editor_6">
                [
                {
                "error":true,
                "message":"\u041d\u0435\u0432\u0435\u0440\u043d\u044b\u0439 \u0441\u0442\u0430\u0442\u0443\u0441"
                }
                ]
            </div>
            <b>Ответ в случае успеха:</b> <br/>
            <div id="json_editor_7">
                [
                {
                "error":false,
                "message": ""
                }
                ]
            </div>

        </div>

        <hr/>

        <div class="content-group">
            <b>URL:</b> /backend/catalog/one/manager/{username}{email}...<br/>
            <b>Метод:</b> PUT<br/>
            <b>Описание:</b> Создать менеджера.<br/>
            <b>Параметры:</b><br/>
            <div class="ml-20">
                username – id менеджера в 1С<br/>
                +- email – email<br/>
                name – Имя<br/>
                patronymic – Отчество<br/>
                surname – Фамилия<br/>
                +- phone1 – +375 44 771 40 99<br/>
                +- phone2 – +375 17 507 60 36 (доб. 194)
            </div>
            <b>Ответ в случае успеха:</b> <br/>
            <div id="json_editor_8">
                [
                {
                "error":false,
                "message": ""
                }
                ]
            </div>

        </div>

        <hr/>

        <div class="content-group">
            <b>URL:</b> /backend/catalog/one/manager/{username}{email}...<br/>
            <b>Метод:</b> POST<br/>
            <b>Описание:</b> Изменить менеджера.<br/>
            <b>Параметры:</b><br/>
            <div class="ml-20">
                username – id менеджера в 1С<br/>
                +- email – email<br/>
                name – Имя<br/>
                patronymic – Отчество<br/>
                surname – Фамилия<br/>
                +- phone1 – +375 44 771 40 99<br/>
                +- phone2 – +375 17 507 60 36 (доб. 194)
            </div>
            <b>Ответ в случае успеха:</b> <br/>
            <div id="json_editor_9">
                [
                {
                "error":false,
                "message": ""
                }
                ]
            </div>

        </div>

        <hr/>

        <div class="content-group">
            <b>URL:</b> /backend/catalog/one/cm/{client_ynp}{manager_username}<br/>
            <b>Метод:</b> PUT<br/>
            <b>Описание:</b> Создать связь клиент - менеджер.<br/>
            <b>Параметры:</b><br/>
            <div class="ml-20">
                client_ynp – УНП клиента<br/>
                manager_username – id менеджера в 1С
            </div>
            <b>Ответ в случае успеха:</b> <br/>
            <div id="json_editor_10">
                [
                {
                "error":false,
                "message": ""
                }
                ]
            </div>

        </div>

        <hr/>

        <div class="content-group">
            <b>URL:</b> /backend/catalog/one/cm/{client_ynp}{manager_username}<br/>
            <b>Метод:</b> POST<br/>
            <b>Описание:</b> Изменить связь клиент - менеджер. У клиента меняем менеджера.<br/>
            <b>Параметры:</b><br/>
            <div class="ml-20">
                client_ynp – УНП клиента<br/>
                manager_username – id менеджера в 1С
            </div>
            <b>Ответ в случае успеха:</b> <br/>
            <div id="json_editor_11">
                [
                {
                "error":false,
                "message": ""
                }
                ]
            </div>

        </div>

        <hr/>

        <div class="content-group">
            <b>URL:</b> /backend/catalog/one/discount/{client_ynp}{discount}<br/>
            <b>Метод:</b> POST<br/>
            <b>Описание:</b> Назначить клиенту скидку на категории.<br/>
            <b>Параметры:</b><br/>
            <div class="ml-20">
                client_ynp – УНП клиента<br/>
                discount – скидка
            </div>
            <b>Ответ в случае успеха:</b> <br/>
            <div id="json_editor_12">
                [
                {
                "error":false,
                "message": ""
                }
                ]
            </div>

        </div>

        <hr/>

        <div class="content-group">
            <b>URL:</b> /backend/catalog/one/change-order/{data}<br/>
            <b>Метод:</b> POST<br/>
            <b>Описание:</b> 13. Изменение информации о заказе. Количество, цена.<br/>
            <b>Параметры:</b><br/>
            <div class="ml-20">
                <div id="json_editor_13">
                    {"orderId":123,"products":[{"code":961548,"count":2,"price":200},{"code":588741,"count":1,"price":150}],"priceTotal":550}

                    data = [
                    'orderId' => 123,
                    'products' => [
                    '0' => [
                    'code' => 961548,
                    'count' => 2,
                    'price' => 200,
                    ],
                    '1' => [
                    'code' => 588741,
                    'count' => 1,
                    'price' => 150,
                    ],
                    ],
                    'priceTotal' => 550,
                    ];
                </div>
            </div>

        </div>

        <hr/>

        <div class="content-group">
            <b>URL:</b> /backend/catalog/one/change-in-stock/{data}<br/>
            <b>Метод:</b> POST<br/>
            <b>Описание:</b> 14. Изменение информации о количестве товара.<br/>
            Передавать информацию о проводке/отмене проводки счетов созданных локально в 1С.<br/>
            <b>Параметр:</b> data<br/>
            <b>Переменные:</b> <br/>
            <div class="ml-20">
                type – '1' в случае отмены проводки счета (освободился товар) / '0' - проведен счет (товар в
                резерве);<br/>
                code_1c – код товара из 1С;<br/>
                count – количество товара.
            </div>
            <b>Ответ в случае успеха:</b> <br/>
            <div id="json_editor_14">
                {"type":0,"products":{"99034":2,"99480":3}}

                $data = [
                'type' => 1,
                'products' => [
                'code_1c' => $count, // '99034' => 2,
                'code_1c' => $count, // '99480' => 3,
                ...
                'code_1c' => $count,
                ]
                ];

                echo json_encode($data);
            </div>

        </div>

        <hr/>

        <div class="content-group">
            <b>URL:</b> /backend/catalog/one/tovs/{data}<br/>
            <b>Метод:</b> POST<br/>
            <b>Описание:</b> 15. Вернуть на сайт id товаров отсутствующих в 1С.<br/>
            <b>Параметры:</b><br/>
            <div class="ml-20">
                order_id – id заказа на сайте;<br/>
                products - массив id товаров;
            </div>
            <b>Ответ в случае успеха:</b> <br/>
            <div id="json_editor_15">
                $order_id = 999;
                $products = [100001, 100002, 200099];
            </div>

            <hr/>

            <div class="content-group">
                <b>URL:</b> /backend/catalog/one/invoice-number/{data}<br/>
                <b>Метод:</b> POST<br/>
                <b>Описание:</b> 16. Вернуть на сайт, что заказу с указанным order_id присвоен номер счёта из
                1С.<br/>
                <b>Параметры:</b><br/>
                <div class="ml-20">
                    order_id – id заказа на сайте;<br/>
                    invoice_number – номер этого заказа в 1С;
                </div>
                <b>Ответ в случае успеха:</b> <br/>
                <div id="json_editor_16">
                    {"order_id":157,"invoice_number":"Счет-фактура - Протокол No 267280 от 28 Ноября 2016 г."}

                    $order_id = 157;
                    $invoice_number = 'Счет-фактура - Протокол No 267280 от 28 Ноября 2016 г.';
                </div>

            </div>

            <hr/>

            <div class="content-group">
                <b>URL:</b> /backend/catalog/one/change-invoice/{data}<br/>
                <b>Метод:</b> POST<br/>
                <b>Описание:</b> 17. Вернуть на сайт измененный счёт из 1С.<br/>
                <b>Параметры:</b><br/>
                <div class="ml-20">
                    order_id – id заказа на сайте;<br/>
                    sum – итоговая сумма заказа в 1С;<br/>
                    <br/>
                    code_1c - код товара из заказа 1С;<br/>
                    title - наименование позиции товара;<br/>
                    count - количество;<br/>
                    price - цена с НДС, шт;<br/>
                    priceAll - цена с НДС, итого;<br/>
                </div>
                <b>Ответ в случае успеха:</b> <br/>
                <div id="json_editor_17">
{"order_id":170,"sum":"900.00","products":[{"code_1c":111,"title":"Tovar1","count":"3","price":"200.00","priceAll":"600.00"},{"code_1c":222,"title":"Tovar2","count":"2","price":"150.00","priceAll":"300.00"}]}

                    $data = [
                    'order_id' => 170,
                    'sum' => "900.00",
                    'products' => [
                    [
                    'code_1c' => 111,
                    'title' => 'Tovar1',
                    'count' => '3',
                    'price' => "200.00",
                    'priceAll' => "600.00",
                    ],
                    [
                    ... ,
                    ],
                    [
                    'code_1c' => 222,
                    'title' => 'Tovar2',
                    'count' => '2',
                    'price' => "150.00",
                    'priceAll' => "300.00",
                    ]
                    ],
                    ];
                </div>
            </div>

            <hr/>

            <div class="content-group">
                <b>URL:</b> /backend/catalog/one/category1c/{data}<br/>
                <b>Метод:</b> POST<br/>
                <b>Описание:</b> 18. Отправить на сайт дерево категорий с товарами.<br/>
                <b>Параметр:</b> data<br/>
                <b>Переменные:</b> <br/>
                <div class="ml-20">
                    id - id записи;<br/>
                    code_category_1c – код категории из 1С;<br/>
                    id_parent – родительская категория;<br/>
                    title – наименование категории;<br/>
                    code_1c – массив товаров с кодом 1С, принадлежащих данной категории.
                </div>
                <b>Вид запроса:</b> <br/>
                <div id="json_editor_18">
[{"id":1,"code_category_1c":"GR00128","id_parent":0,"title":"COLOMBO","arr":[123456,123456]},
{"id":2,"code_category_1c":"GR00110","id_parent":0,"title":"KALDEWEI","arr":[123333,123444],
"0":{"id":3,"code_category_1c":"GR00308","id_parent":2,"title":"CAYONO","arr":[123333,123444,123555]},
"1":{"id":4,"code_category_1c":"GR0310","id_parent":2,"title":"PURO","arr":[223333,223444,223555]}}]

                    $data = [
                        [
                            "id" => 1,
                            "code_category_1c" => "ГР00128",
                            "id_parent" => 0,
                            "title" => "Ванны акриловые COLOMBO (Украина)",
                            arr => [123456, 123456],
                        ],
                        [
                            "id" => 2,
                            "code_category_1c" => "ГР00110",
                            "id_parent" => 0,
                            "title" => "Ванны стальные KALDEWEI (Германия)",
                            arr => [123333, 123444],
                            [
                                "id" => 3,
                                "code_category_1c" => "ГР00308",
                                "id_parent" => 2,
                                "title" => "Ванны стальные CAYONO",
                                arr => [123333, 123444, 123555],
                            ],
                            [
                                "id" => 4,
                                "code_category_1c" => "ГР00310",
                                "id_parent" => 2,
                                "title" => "Ванны стальные PURO",
                                arr => [223333, 223444, 223555],
                            ],
                        ],

                    ];
                </div>
            </div>

            <hr/>

            <div class="content-group">
                <b>URL:</b> /backend/catalog/one/discount1c/{data}<br/>
                <b>Метод:</b> POST<br/>
                <b>Описание:</b> 19. Отправить на сайт id клиентов с присвоенными им скидками.<br/>
                <b>Параметр:</b> data<br/>
                <b>Переменные:</b> <br/>
                <div class="ml-20">
                    id_user - id клиента;<br/>
                    "Наименование группы" => "размер скидки".
                </div>
                <b>Вид запроса:</b> <br/>
                <div id="json_editor_19">
[{"id_user":1,"0":{"GR00110":"10","GR00128":"20"}},{"id_user":173,"0":{"GR00308":"20","GR03100":"10"}}]

$data = [
    [
        "id_user" => 1,
        [
            "GR00110" => "10",
            "GR00128" => "20",
        ],
    ],
    [
        "id_user" => 173,
        [
            "GR00308" => "20",
            "GR03100" => "10",
        ],
    ],
];
                </div>
            </div>



        </div>
    </div>
</div>
