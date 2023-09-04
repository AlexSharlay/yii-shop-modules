<?php

return [
    'minDeliveryPrice' => 10,                       // Минимальная цена заказа, сумма в копейках
    'maxDeliveryPrice' => 100000000,                // Максимальнаяя цена заказа, сумма в копейках
    'adminEmail' => 'no-reply@shop.by',       //
    'orderEmail' => [                               // Заказы
        'php@shop.by',
        'info@shop.by',
    ],
    'errorEmail' => [                               // Ошибки
        'php@shop.by',
    ],
    'infoEmail' => [                                // Оповещения
        'php@shop.by',
    ],
    'newClientEmail' => [                           // Оповещения
        'a.yurchenko@shop.by',
        '1c@shop.by',
        'kav@shop.by',
        'poverennova@kranik.by',
    ],
    'messageEmails' => [                            // Сообщения пользователей в форме связи
        'a.yurchenko@shop.by',
        'rusrsk@kranik.by',
        'php@shop.by',
        'seo@shop.by',
        'poverennova@kranik.by',
    ],

    // Куда отсылать копии писем с заказами
    'emailCopyOrder' => [

        'olga.y@shop.by' => [
            'a.yurchenko@shop.by',
        ],

        'abramovich@shop.by' => [
            'a.yurchenko@shop.by',
            'goman@shop.by',
        ],
        'gorelov@shop.by' => [
            'a.yurchenko@shop.by',
            'goman@shop.by',
        ],
        'bichik@shop.by' => [
            'a.yurchenko@shop.by',
            'goman@shop.by',
        ],
        'inzhener@shop.by' => [
            'a.yurchenko@shop.by',
            'goman@shop.by',
        ],

        'lazovskaya@shop.by' => [
            'a.yurchenko@shop.by',
            'elena.m@shop.by',
        ],
        'tamara@shop.by' => [
            'a.yurchenko@shop.by',
            'elena.m@shop.by',
        ],
        'vitebsk@shop.by' => [
            'a.yurchenko@shop.by',
            'elena.m@shop.by',
        ],
        'minobl@shop.by' => [
            'a.yurchenko@shop.by',
            'elena.m@shop.by',
        ],
        'minobl2@shop.by' => [
            'a.yurchenko@shop.by',
            'elena.m@shop.by',
        ],

        'moroz@shop.by' => [
            'a.yurchenko@shop.by',
            'kav@shop.by',
        ],
        'grodno@shop.by' => [
            'a.yurchenko@shop.by',
            'kav@shop.by',
        ],
        'brest@shop.by' => [
            'a.yurchenko@shop.by',
            'kav@shop.by',
        ],
        'brest1@shop.by' => [
            'a.yurchenko@shop.by',
            'kav@shop.by',
        ],

        'rutkevich@shop.by' => [
            'a.yurchenko@shop.by',
            'zapadrb@shop.by',
        ],
        'tyshkevich@shop.by' => [
            'a.yurchenko@shop.by',
            'zapadrb@shop.by',
        ],
        'montazhnik@shop.by' => [
            'a.yurchenko@shop.by',
            'zapadrb@shop.by',
        ],

        'astahov@shop.by' => [
            'a.yurchenko@shop.by',
        ],
    ]
];
