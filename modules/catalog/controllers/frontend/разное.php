<?php

/*public static function getXLS($xls){
    $objPHPExcel = PHPExcel_IOFactory::load($xls);
    $objPHPExcel->setActiveSheetIndex(0);
    $aSheet = $objPHPExcel->getActiveSheet();

    //этот массив будет содержать массивы содержащие в себе значения ячеек каждой строки
    $array = array();
    //получим итератор строки и пройдемся по нему циклом
    foreach($aSheet->getRowIterator() as $row){
        //получим итератор ячеек текущей строки
        $cellIterator = $row->getCellIterator();
        //пройдемся циклом по ячейкам строки
        //этот массив будет содержать значения каждой отдельной строки
        $item = array();
        foreach($cellIterator as $cell){
            //заносим значения ячеек одной строки в отдельный массив
            array_push($item, iconv('utf-8', 'cp1251', $cell->getCalculatedValue()));
        }
        //заносим массив со значениями ячеек отдельной строки в "общий массв строк"
        array_push($array, $item);
    }
    return $array;
}*/

/*$products = (new Query)->select('id, price')
           ->from('{{%catalog_element}}')
           ->all();

       foreach($products as $product) {
           $newPrice = (int)round($product['price'] / 100);
           Element::updateAll(
               [
                   'price' => $newPrice,
                   'price_1c' => $newPrice,
               ],
               'id = :id',
               [
                   ':id' => $product['id'],
               ]
           );
       }*/

/*
    $items = self::getXLS($_SERVER['DOCUMENT_ROOT'].'/'.'manuf.xlsx');
    unset($items[0]);

    $vals = array();
    foreach($items as $item) {
        $vals[$item[0]][] = $item[1];
    }

    $result = [];
    foreach($vals as $key=>$vls) {
        // Получить id коллекции
        $id_collection = (new Query())->select('id')->from('{{%catalog_collection}}')->where('alias = :alias', [':alias' => $key])->one()['id'];
        foreach($vls as $val) {
            $id_product = (new Query())->select('id')->from('{{%catalog_element}}')->where('article = :article', [':article' => $val])->one()['id'];
            if ($id_collection && $id_product) {
                $result[] = [
                    'id_collection' => $id_collection,
                    'id_element' => $id_product
                ];
            } else {
                echo $val.'<br/>';
            }
        }
    }

    $command = Yii::$app->db->createCommand();
    foreach($result as $item) {
        $command->insert('{{%catalog_collection_rel}}', [
            'id_collection' => $item['id_collection'],
            'id_element' => $item['id_element'],
        ])->execute();
    }*/

/*
$a = [
    new Query()
];
$b = new Query();

if (in_array($b,$a,1)) {
    echo '+';
} else {
    echo '-';
}
*/

/*
        $xmlValues = ['test1' => 'value1', 'test2' => 'value2'];

        //set content type xml in response
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml');


        foreach($xmlValues as $key => $value){
            echo '<?xml version="1.0"?><setting id="'.$key.'">'.$value.'</setting></xml>';
        }*/

/*

$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://catalog.api.onliner.by/facets/radiators');
$a = curl_exec($ch);
curl_close($ch);

$a = json_decode($a);

$a->placeholders = [];

echo json_encode($a);*/

/*
 * dictionary_range -> number_range
 *
$a = '{"facets":{"gener.....8\u0441"}]}}';
$a = json_decode($a);

$a->facets->additional->items['2']->type = 'number_range';
$a->facets->additional->items['2']->unit = 'мм.';
$a->facets->additional->items['2']->ratio = '1';
unset($a->facets->additional->items['2']->dictionary_id);
unset($a->facets->additional->items['2']->predefined_ranges);

echo json_encode($a);
*/

Helper::jsonHeader();
/*
 Биде
 */
echo json_encode([
    'facets' => [
        'general' => [
            'items' => [
                [
                    'type' => 'dictionary',
                    'parameter_id' => 'mfr',
                    'dictionary_id' => 'mfr',
                    'name' => 'Производитель',
                    'description' => '',
                    'operation' => 'union',
                    'max_count' => '10',
                    'popular_dictionary_values' => [
                        '0' => 'colombo',
                        '1' => 'keramag',
                        '2' => 'laufen',
                        '3' => 'roca',
                        '4' => 'vitra',
                    ],
                ],
                [
                    'type' => 'number_range',
                    'parameter_id' => 'price',
                    'name' => 'Цена',
                    'description' => '',
                    'unit' => '',
                ],
                [
                    'type' => 'dictionary',
                    'parameter_id' => 'material',
                    'dictionary_id' => 'material',
                    'name' => 'Материал',
                    'description' => '',
                    'operation' => 'union',
                    'max_count' => '10',
                    'popular_dictionary_values' => [
                        '0' => 'porcelain',
                        '1' => 'faience',
                        '2' => 'ceramics',
                    ],
                ],
                [
                    'type' => 'dictionary',
                    'parameter_id' => 'installation',
                    'dictionary_id' => 'installation',
                    'name' => 'Монтаж',
                    'description' => '',
                    'operation' => 'union',
                    'max_count' => '10',
                    'popular_dictionary_values' => [
                        '0' => 'suspension',
                        '1' => 'floor',
                    ],
                ],
                [
                    'type' => 'boolean',
                    'parameter_id' => 'hole_mixer',
                    'name' => 'Отверстие под смеситель',
                    'description' => '',
                    'bool_type' => 'checkbox',
                ],
                [
                    'type' => 'number_range',
                    'parameter_id' => 'length',
                    'name' => 'Длина',
                    'description' => '',
                    'unit' => 'см',
                    'ratio' => '1',
                    'overlap' => 'partial',
                ],
                [
                    'type' => 'number_range',
                    'parameter_id' => 'width',
                    'name' => 'Ширина',
                    'description' => '',
                    'unit' => 'см',
                    'ratio' => '1',
                    'overlap' => 'partial',
                ],
                [
                    'type' => 'number_range',
                    'parameter_id' => 'height',
                    'name' => 'Высота',
                    'description' => '',
                    'unit' => 'см',
                    'ratio' => '1',
                    'overlap' => 'partial',
                ],
            ]
        ],
        'additional' => [
            'items' => []
        ]
    ],
    'placeholders' => [],
    'dictionaries' => [
        'mfr' => [
            [
                'id' => 'colombo',
                'name' => 'Colombo',
            ],
            [
                'id' => 'keramag',
                'name' => 'Keramag',
            ],
            [
                'id' => 'laufen',
                'name' => 'Laufen',
            ],
            [
                'id' => 'roca',
                'name' => 'Roca',
            ],
            [
                'id' => 'vitra',
                'name' => 'Vitra',
            ],
        ],
        'material' => [
            [
                'id' => 'porcelain',
                'name' => 'фарфор',
            ],
            [
                'id' => 'faience',
                'name' => 'фаянс',
            ],
            [
                'id' => 'ceramics',
                'name' => 'сантехкерамика',
            ],
        ],
        'installation' => [
            [
                'id' => 'suspension',
                'name' => 'подвесной',
            ],
            [
                'id' => 'floor',
                'name' => 'напольный',
            ],
        ],
    ]
]);


/* Канализация
        echo json_encode([
            'facets' => [
                'general' => [
                    'items' => [
                        [
                            'type' => 'number_range',
                            'parameter_id' => 'price',
                            'name' => 'Цена',
                            'description' => '',
                            'unit' => '',
                        ],
                        [
                            'type' => 'dictionary',
                            'parameter_id' => 'use',
                            'dictionary_id' => 'use',
                            'name' => 'Назначение',
                            'description' => '',
                            'operation' => 'union',
                            'max_count' => '10',
                        ],
                    ]
                ],
                'additional' => [
                    'items' => []
                ]
            ],
            'placeholders' => [],
            'dictionaries' => [
                'use' => [
                    [
                        'id' => 'internal',
                        'name' => 'Внутренняя',
                    ],
                    [
                        'id' => 'outdoor',
                        'name' => 'Наружная',
                    ],
                    [
                        'id' => 'pressurized',
                        'name' => 'Напорная',
                    ],
                ],
            ]
        ]);
        */

/*
  * Запорная арматура
 echo json_encode([
     'facets' => [
         'general' => [
             'items' => [
                 [
                     'type' => 'dictionary',
                     'parameter_id' => 'mfr',
                     'dictionary_id' => 'mfr',
                     'name' => 'Производитель',
                     'description' => '',
                     'operation' => 'union',
                     'max_count' => '10',
                     'popular_dictionary_values' => [
                         '0' => 'grohe',
                         '1' => 'bonomini',
                         '2' => 'ferro',
                         '3' => 'valvex',
                         '4' => 'itap',
                     ],
                 ],
                 [
                     'type' => 'number_range',
                     'parameter_id' => 'price',
                     'name' => 'Цена',
                     'description' => '',
                     'unit' => '',
                 ],
                 [
                     'type' => 'dictionary',
                     'parameter_id' => 'appointment',
                     'dictionary_id' => 'appointment',
                     'name' => 'Назначение',
                     'description' => '',
                     'operation' => 'union',
                     'max_count' => '10',
                 ],
             ]
         ],
         'additional' => [
             'items' => []
         ]
     ],
     'placeholders' => [],
     'dictionaries' => [
         'mfr' => [
             [
                 'id' => 'bonomini',
                 'name' => 'Bonomini',
             ],
             [
                 'id' => 'ferro',
                 'name' => 'Ferro',
             ],
             [
                 'id' => 'grohe',
                 'name' => 'Grohe',
             ],
             [
                 'id' => 'hansgrohe',
                 'name' => 'Hansgrohe',
             ],
             [
                 'id' => 'itap',
                 'name' => 'Itap',
             ],
             [
                 'id' => 'sobime',
                 'name' => 'Sobime',
             ],
             [
                 'id' => 'valvex',
                 'name' => 'Valvex',
             ],
             [
                 'id' => 'viega',
                 'name' => 'Viega',
             ],
             [
                 'id' => 'цветлит',
                 'name' => 'Цветлит',
             ],
         ],
         'appointment' => [
             [
                 'id' => 'water_supply',
                 'name' => 'для водоснабжения',
             ],
             [
                 'id' => 'heating',
                 'name' => 'для отопления',
             ],
             [
                 'id' => 'gas',
                 'name' => 'для газа',
             ],
         ],
     ]
 ]);
*/


/*
 * Инсталляции
echo json_encode([
    'facets' => [
        'general' => [
            'items' => [
                [
                    'type' => 'dictionary',
                    'parameter_id' => 'mfr',
                    'dictionary_id' => 'mfr',
                    'name' => 'Производитель',
                    'description' => '',
                    'operation' => 'union',
                    'max_count' => '10',
                ],
                [
                    'type' => 'number_range',
                    'parameter_id' => 'price',
                    'name' => 'Цена',
                    'description' => '',
                    'unit' => '',
                ],
                [
                    'type' => 'dictionary',
                    'parameter_id' => 'appointment',
                    'dictionary_id' => 'appointment',
                    'name' => 'Назначение',
                    'description' => '',
                    'operation' => 'union',
                    'max_count' => '10',
                ],
            ]
        ],
        'additional' => [
            'items' => []
        ]
    ],
    'placeholders' => [],
    'dictionaries' => [
        'mfr' => [
            [
                'id' => 'roca',
                'name' => 'Roca',
            ],
            [
                'id' => 'grohe',
                'name' => 'Grohe',
            ],
            [
                'id' => 'belezzo',
                'name' => 'Belezzo',
            ],
        ],
        'appointment' => [
            [
                'id' => 'toilet',
                'name' => 'для унитаза',
            ],
            [
                'id' => 'urinals',
                'name' => 'для писуара',
            ],
        ],
    ]
]);
*/


/*
 * Инсталляции
echo json_encode([
    'facets' => [
        'general' => [
            'items' => [
                [
                    'type' => 'dictionary',
                    'parameter_id' => 'mfr',
                    'dictionary_id' => 'mfr',
                    'name' => 'Производитель',
                    'description' => '',
                    'operation' => 'union',
                    'max_count' => '10',
                    'popular_dictionary_values' => [
                        '0' => 'grohe',
                        '1' => 'viega',
                        '2' => 'geberit',
                        '3' => 'keramag',
                        '4' => 'cersanit',
                    ],
                ],
                [
                    'type' => 'number_range',
                    'parameter_id' => 'price',
                    'name' => 'Цена',
                    'description' => '',
                    'unit' => '',
                ],
                [
                    'type' => 'dictionary',
                    'parameter_id' => 'appointment',
                    'dictionary_id' => 'appointment',
                    'name' => 'Назначение',
                    'description' => '',
                    'operation' => 'union',
                    'max_count' => '10',
                ],
                [
                    'type' => 'dictionary',
                    'parameter_id' => 'drain_mode',
                    'dictionary_id' => 'drain_mode',
                    'name' => 'Режим слива воды',
                    'description' => '',
                    'operation' => 'union',
                    'max_count' => '10',
                ],
                [
                    'type' => 'number_range',
                    'parameter_id' => 'width',
                    'name' => 'Ширина',
                    'description' => '',
                    'unit' => 'см',
                    'ratio' => '1',
                    'overlap' => 'partial',
                ],
                [
                    'type' => 'number_range',
                    'parameter_id' => 'height',
                    'name' => 'Высота',
                    'description' => '',
                    'unit' => 'см',
                    'ratio' => '1',
                    'overlap' => 'partial',
                ],
                [
                    'type' => 'number_range',
                    'parameter_id' => 'depth',
                    'name' => 'Глубина',
                    'description' => '',
                    'unit' => 'см',
                    'ratio' => '1',
                    'overlap' => 'partial',
                ],
            ]
        ],
        'additional' => [
            'items' => []
        ]
    ],
    'placeholders' => [],
    'dictionaries' => [
        'mfr' => [
            [
                'id' => 'cersanit',
                'name' => 'Cersanit',
            ],
            [
                'id' => 'geberit',
                'name' => 'Geberit',
            ],
            [
                'id' => 'keramag',
                'name' => 'Keramag',
            ],
            [
                'id' => 'grohe',
                'name' => 'Grohe',
            ],
            [
                'id' => 'jika',
                'name' => 'Jika',
            ],
            [
                'id' => 'keramag',
                'name' => 'Keramag',
            ],
            [
                'id' => 'viega',
                'name' => 'Viega',
            ],
            [
                'id' => 'vitra',
                'name' => 'Vitra',
            ],
            [
                'id' => 'jimten',
                'name' => 'Jimten',
            ],
        ],
        'appointment' => [
            [
                'id' => 'bide',
                'name' => 'для биде',
            ],
            [
                'id' => 'pod_unitaz',
                'name' => 'для подвесного унитаза',
            ],
            [
                'id' => 'pisuari',
                'name' => 'для писуаров',
            ],
            [
                'id' => 'rakovini',
                'name' => 'для раковин',
            ],
        ],
        'drain_mode' => [
            [
                'id' => 'two',
                'name' => '2-х объемный',
            ],
            [
                'id' => 'start_stop',
                'name' => 'старт/стоп',
            ],
            [
                'id' => 'continuous',
                'name' => 'непрерывный',
            ],
        ],
    ]
]);
*/




//Helper::jsonHeader();
/*
 * Плитка
echo json_encode([
    'facets' => [
        'general' => [
            'items' => [
                [
                    'type' => 'dictionary',
                    'parameter_id' => 'country',
                    'dictionary_id' => 'country',
                    'name' => 'Страна',
                    'description' => '',
                    'max_count' => '10',
                ],
                [
                    'type' => 'dictionary',
                    'parameter_id' => 'mfr',
                    'dictionary_id' => 'mfr',
                    'name' => 'Производитель',
                    'description' => '',
                    'max_count' => '10',
                ],
                [
                    'type' => 'dictionary',
                    'parameter_id' => 'color',
                    'dictionary_id' => 'color',
                    'name' => 'Цвет',
                    'description' => '',
                    'operation' => 'union',
                    'max_count' => '10',
                    'popular_dictionary_values' => [
                        '0' => 'white',
                        '1' => 'beige',
                        '2' => 'cream',
                        '3' => 'gray',
                        '4' => 'red',
                    ],
                ],
                [
                    'type' => 'dictionary',
                    'parameter_id' => 'design',
                    'dictionary_id' => 'design',
                    'name' => 'Дизайн',
                    'description' => '',
                    'operation' => 'union',
                    'max_count' => '10',
                    'popular_dictionary_values' => [
                        '0' => 'tree',
                        '1' => 'stone',
                        '2' => 'high_tech',
                        '3' => 'clay',
                        '4' => 'picture',
                    ],
                ],
                [
                    'type' => 'dictionary',
                    'parameter_id' => 'surface',
                    'dictionary_id' => 'surface',
                    'name' => 'Поверхность',
                    'description' => '',
                    'operation' => 'union',
                    'max_count' => '10',
                ],
                [
                    'type' => 'dictionary',
                    'parameter_id' => 'material',
                    'dictionary_id' => 'material',
                    'name' => 'Материал',
                    'description' => '',
                    'operation' => 'union',
                    'max_count' => '10',
                ],
            ]
        ],
        'additional' => [
            'items' => []
        ]
    ],
    'placeholders' => [],
    'dictionaries' => [
        'country' => [
            [
                'id' => 'spain',
                'name' => 'Испания',
            ],
            [
                'id' => 'italy',
                'name' => 'Италия',
            ],
            [
                'id' => 'turkey',
                'name' => 'Турция',
            ],
        ],
        'mfr' => [
            [
                'id' => 'roca',
                'name' => 'Roca',
            ],
            [
                'id' => 'pamessa',
                'name' => 'Pamessa',
            ],
            [
                'id' => 'delconca',
                'name' => 'Delconca',
            ],
            [
                'id' => 'prissmacer',
                'name' => 'Prissmacer',
            ],
            [
                'id' => 'vitra',
                'name' => 'Vitra',
            ],
            [
                'id' => 'naxos',
                'name' => 'Naxos',
            ],
        ],
        'color' => [
            [
                'id' => 'anthracite',
                'name' => 'антрацит',
            ],
            [
                'id' => 'white',
                'name' => 'белый',
            ],
            [
                'id' => 'beige',
                'name' => 'бежевый',
            ],
            [
                'id' => 'turquoise',
                'name' => 'бирюзовый',
            ],
            [
                'id' => 'vinous',
                'name' => 'бордовый',
            ],
            [
                'id' => 'blue',
                'name' => 'голубой',
            ],
            [
                'id' => 'yellow',
                'name' => 'желтый',
            ],
            [
                'id' => 'green',
                'name' => 'зеленый',
            ],
            [
                'id' => 'golden',
                'name' => 'золотистый',
            ],
            [
                'id' => 'coral',
                'name' => 'коралловый',
            ],
            [
                'id' => 'brown',
                'name' => 'коричневый',
            ],
            [
                'id' => 'red',
                'name' => 'красный',
            ],
            [
                'id' => 'cream',
                'name' => 'кремовый',
            ],
            [
                'id' => 'orange',
                'name' => 'оранжевый',
            ],
            [
                'id' => 'pink',
                'name' => 'розовый',
            ],
            [
                'id' => 'silver',
                'name' => 'серебристый',
            ],
            [
                'id' => 'gray',
                'name' => 'серый',
            ],
            [
                'id' => 'navy_blue',
                'name' => 'синий',
            ],
            [
                'id' => 'lilac',
                'name' => 'сиреневый',
            ],
            [
                'id' => 'terracotta',
                'name' => 'терракотовый',
            ],
            [
                'id' => 'violet',
                'name' => 'фиолетовый',
            ],
            [
                'id' => 'black',
                'name' => 'черный',
            ],
        ],
        'design' => [
            [
                'id' => 'tree',
                'name' => 'под дерево',
            ],
            [
                'id' => 'stone',
                'name' => 'под камень',
            ],
            [
                'id' => 'high_tech',
                'name' => 'high-tech',
            ],
            [
                'id' => 'clay',
                'name' => 'глина',
            ],
            [
                'id' => 'picture',
                'name' => 'изображение',
            ],
            [
                'id' => 'classic',
                'name' => 'классика',
            ],
            [
                'id' => 'metal',
                'name' => 'металл',
            ],
            [
                'id' => 'multicolor',
                'name' => 'многоцветный',
            ],
            [
                'id' => 'mosaic',
                'name' => 'мозаика',
            ],
            [
                'id' => 'monocolor',
                'name' => 'моноколор',
            ],
            [
                'id' => 'ornament',
                'name' => 'орнамент',
            ],
            [
                'id' => 'bamboo',
                'name' => 'под бамбук',
            ],
            [
                'id' => 'brick',
                'name' => 'под кирпич',
            ],
            [
                'id' => 'skin',
                'name' => 'под кожу',
            ],
            [
                'id' => 'mosaic',
                'name' => 'под мозаику',
            ],
            [
                'id' => 'marble',
                'name' => 'под мрамор',
            ],
            [
                'id' => 'wallpaper',
                'name' => 'под обои',
            ],
            [
                'id' => 'parquet',
                'name' => 'под паркет',
            ],
            [
                'id' => 'cork',
                'name' => 'под пробку',
            ],
            [
                'id' => 'glass',
                'name' => 'под стекло',
            ],
            [
                'id' => 'textile',
                'name' => 'под текстиль',
            ],
        ],
        'surface' => [
            [
                'id' => 'glossy',
                'name' => 'глянцевая',
            ],
            [
                'id' => 'matt',
                'name' => 'матовая',
            ],
            [
                'id' => 'polished',
                'name' => 'полированная',
            ],
            [
                'id' => 'matt_gloss',
                'name' => 'матовая/глянцевая',
            ],
            [
                'id' => 'polished',
                'name' => 'полированная',
            ],
            [
                'id' => 'relief',
                'name' => 'рельефная',
            ],
            [
                'id' => 'structural',
                'name' => 'структурная',
            ],
        ],
        'material' => [
            [
                'id' => 'glazed_ceramic_tiles',
                'name' => 'глазурованная керамическая плитка',
            ],
            [
                'id' => 'ceramic_tile',
                'name' => 'керамическая плитка',
            ],
            [
                'id' => 'stone',
                'name' => 'камень',
            ],
            [
                'id' => 'granite',
                'name' => 'керамогранит',
            ],
            [
                'id' => 'glass',
                'name' => 'стекло',
            ],
            [
                'id' => 'porcelain',
                'name' => 'фарфор',
            ],
        ],
    ]
]);
*/

/*
 * Мебель
echo json_encode([
    'facets' => [
        'general' => [
            'items' => [
                [
                    'type' => 'dictionary',
                    'parameter_id' => 'country',
                    'dictionary_id' => 'country',
                    'name' => 'Страна',
                    'description' => '',
                    'max_count' => '10',
                ],
                [
                    'type' => 'dictionary',
                    'parameter_id' => 'mfr',
                    'dictionary_id' => 'mfr',
                    'name' => 'Производитель',
                    'description' => '',
                    'operation' => 'union',
                    'max_count' => '10',
                ],
                [
                    'type' => 'dictionary',
                    'parameter_id' => 'color',
                    'dictionary_id' => 'color',
                    'name' => 'Цвет',
                    'description' => '',
                    'operation' => 'union',
                    'max_count' => '10',
                    'popular_dictionary_values' => [
                        '0' => 'white',
                        '1' => 'white_wenge',
                        '2' => 'red',
                        '3' => 'black',
                        '4' => 'beige',
                    ],
                ],
                [
                    'type' => 'dictionary',
                    'parameter_id' => 'installation',
                    'dictionary_id' => 'installation',
                    'name' => 'Монтаж',
                    'description' => '',
                    'operation' => 'union',
                    'max_count' => '10',
                ],
                [
                    'type' => 'dictionary',
                    'parameter_id' => 'style',
                    'dictionary_id' => 'style',
                    'name' => 'Стиль',
                    'description' => '',
                    'operation' => 'union',
                    'max_count' => '10',
                ],
            ]
        ],
        'additional' => [
            'items' => []
        ],
    ],
    'placeholders' => [],
    'dictionaries' => [
        'country' => [
            [
                'id' => 'russia',
                'name' => 'Россия',
            ],
            [
                'id' => 'spain',
                'name' => 'Испания',
            ],
            [
                'id' => 'germany',
                'name' => 'Германия',
            ],
            [
                'id' => 'switzerland',
                'name' => 'Швейцария',
            ],
            [
                'id' => 'ukraine',
                'name' => 'Украина',
            ],
            [
                'id' => 'czech',
                'name' => 'Чехия',
            ],
        ],
        'mfr' => [
            [
                'id' => 'roca',
                'name' => 'Roca',
            ],
            [
                'id' => 'akvaton',
                'name' => 'Акватон',
            ],
            [
                'id' => 'keramag',
                'name' => 'Keramag',
            ],
            [
                'id' => 'laufen',
                'name' => 'Laufen',
            ],
            [
                'id' => 'opadiris',
                'name' => 'Opadiris',
            ],
            [
                'id' => 'akva_rodos',
                'name' => 'Аква Родос',
            ],
            [
                'id' => 'ravak',
                'name' => 'Ravak',
            ],
        ],
        'color' => [
            [
                'id' => 'white',
                'name' => 'белый',
            ],
            [
                'id' => 'white_wenge',
                'name' => 'белый-венге',
            ],
            [
                'id' => 'red',
                'name' => 'красный',
            ],
            [
                'id' => 'black',
                'name' => 'черный',
            ],
            [
                'id' => 'beige',
                'name' => 'бежевый',
            ],
            [
                'id' => 'beige_brown',
                'name' => 'бежевый-коричневый',
            ],
            [
                'id' => 'white_beige',
                'name' => 'белый-бежевый',
            ],
            [
                'id' => 'white_blue',
                'name' => 'белый-голубой',
            ],
            [
                'id' => 'white_brown',
                'name' => 'белый-коричневый',
            ],
            [
                'id' => 'white_red',
                'name' => 'белый-красный',
            ],
            [
                'id' => 'white_olive',
                'name' => 'белый-оливка',
            ],
            [
                'id' => 'white_pink',
                'name' => 'белый-розовый',
            ],
            [
                'id' => 'white_lime',
                'name' => 'белый-салатовый',
            ],
            [
                'id' => 'turquoise',
                'name' => 'бирюзовый',
            ],
            [
                'id' => 'vinous',
                'name' => 'бордовый',
            ],
            [
                'id' => 'blue',
                'name' => 'голубой',
            ],
            [
                'id' => 'yellow',
                'name' => 'желтый',
            ],
            [
                'id' => 'brown',
                'name' => 'коричневый',
            ],
            [
                'id' => 'orange',
                'name' => 'оранжевый',
            ],
            [
                'id' => 'gray',
                'name' => 'серый',
            ],
            [
                'id' => 'purple',
                'name' => 'фиолетовый',
            ],
        ],
        'installation' => [
            [
                'id' => 'floor',
                'name' => 'Напольный',
            ],
            [
                'id' => 'suspension',
                'name' => 'Подвесной',
            ],
        ],
        'style' => [
            [
                'id' => 'modern',
                'name' => 'Современный',
            ],
            [
                'id' => 'classical',
                'name' => 'Классический',
            ],
            [
                'id' => 'retro',
                'name' => 'Ретро',
            ],
        ],
    ],
]);
*/