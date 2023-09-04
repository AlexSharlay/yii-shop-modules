<?php

namespace common\modules\catalog\controllers\frontend;

use common\modules\catalog\models\frontend\Category;
use export\resources\cms\classes\OneKProduct;
use Yii;
use backend\components\Controller;
use yii\filters\VerbFilter;
use DOMDocument;

use yii\db\Query;

class ExportController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => [
                    'cron',
                ],
                'roles' => ['@', '?'],
            ],
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'cron' => ['post', 'get'],
            ]
        ];
        return $behaviors;
    }

    public function actionCron()
    {
        $categories = Category::getCategoriesForMenu('vanny');
        foreach ($categories as $item) {
            $category[$item['id']]['id'] = $item['id'];
            $category[$item['id']]['id_parent'] = $item['id_parent'];
            $category[$item['id']]['title'] = $item['title'];
            if (isset($item['childs'])) {
                foreach ($item['childs'] as $item2) {
                    $category[$item2['id']]['id'] = $item2['id'];
                    $category[$item2['id']]['id_parent'] = $item2['id_parent'];
                    $category[$item2['id']]['title'] = $item2['title'];
                    if (isset($item2['childs'])) {
                        foreach ($item2['childs'] as $item3) {
                            $category[$item3['id']]['id'] = $item3['id'];
                            $category[$item3['id']]['id_parent'] = $item3['id_parent'];
                            $category[$item3['id']]['title'] = $item3['title'];
                        }
                    }
                }
            }
        }

        if ($category['423']) {
            unset($category['423']);
        }

        $list_categories = '';
        $array_categories = [];
        foreach ($category as $item) {
            $list_categories .= '\'' . $item['id'] . '\',';
            $array_categories[] = $item['id'];
        }
        $list_categories = substr($list_categories, 0, strlen($list_categories) - 1);

//выбор товаров дороже 1коп.
        $products = (new Query())
            ->select(['ce.id as id_element', 'ce.code_1c as article', 'ce.title', 'title_model', 'ce.price', 'ce.price_old', 'ce.title_model', 'title_before',
                'ce.id_category', 'cm.title as title_manufacturer', 'ce.seo_desc', 'cp.name as picture',
                "CONCAT_WS('/', '',cc4.alias,cc3.alias, cc2.alias, cc1.alias, ce.alias, '') AS url"])
            ->from('{{%catalog_element}} ce')
            ->leftJoin('{{%catalog_category}} cc1', 'cc1.id = ce.id_category')
            ->leftJoin('{{%catalog_category}} cc2', 'cc2.id = cc1.id_parent')
            ->leftJoin('{{%catalog_category}} cc3', 'cc3.id = cc2.id_parent')
            ->leftJoin('{{%catalog_category}} cc4', 'cc4.id = cc3.id_parent')
            ->leftJoin('{{%catalog_manufacturer}} cm', 'cm.id = ce.id_manufacturer')
            ->leftJoin('{{%catalog_photo}} cp', 'cp.id_element = ce.id')
            ->andWhere('ce.published = 1')
            ->andWhere('cc1.published = 1')
            ->andWhere('ce.price > 1')
            ->andWhere('ce.in_stock > 0')
            ->andWhere('ce.in_status <> 3')
            ->andWhere('cp.is_cover = 1')
//            ->andWhere(['in', 'cc1.id_category', ['10'])
            ->all();


        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->createTextNode('<!DOCTYPE yml_catalog SYSTEM "shops.dtd">');
//        $dom->loadHTML('<!DOCTYPE yml_catalog SYSTEM "shops.dtd">');



//добавление корня - <yml_catalog>
//        $yml_catalog = $dom->appendChild($dom->createElement('yml_catalog'));

        $yml_catalog = $dom->createElement('yml_catalog');
        $date = $dom->createAttribute('date');
        $date->value = date('Y-m-d H:i');
        $yml_catalog->appendChild($date);
        $dom->appendChild($yml_catalog);


//добавление элемента <shop> в <yml_catalog>
        $shop = $yml_catalog->appendChild($dom->createElement('shop'));

// добавление элемента <name> в <shop>
        $name = $shop->appendChild($dom->createElement('name'));
// добавление элемента текстового узла <name> в <name>
        $name->appendChild(
            $dom->createTextNode('Краник'));

        $company = $shop->appendChild($dom->createElement('company'));
        $company->appendChild(
            $dom->createTextNode('Сантехпром'));

        $url = $shop->appendChild($dom->createElement('url'));
        $url->appendChild(
            $dom->createTextNode('https://kranik.by'));

        // <-- currencies
        $currencies = $shop->appendChild($dom->createElement('currencies'));
        $currency = $currencies->appendChild($dom->createElement('currency'));

        $attr = $dom->createAttribute('id');
        $attr->value = 'BYN';
        $currency->appendChild($attr);

        $attr = $dom->createAttribute('rate');
        $attr->value = '1';
        $currency->appendChild($attr);
        // currencies -->


        // <-- categories
        $categories = $shop->appendChild($dom->createElement('categories'));
        foreach ($category as $item) {
            $category = $dom->createElement('category');

            $attr = $dom->createAttribute('id');
            $attr->value = $item['id'];
            $category->appendChild($attr);

            $attr = $dom->createAttribute('parentId');
            $attr->value = $item['id_parent'];
            $category->appendChild($attr);

            $title = $dom->createTextNode($item['title']);
            $category->appendChild($title);

            $categories->appendChild($category);
        }
        // categories -->


        // <-- offers
        $offers = $shop->appendChild($dom->createElement('offers'));

        foreach ($products as $product) {
            if (in_array($product['id_category'], $array_categories)) {


                $offer = $dom->createElement('offer');

                $attr = $dom->createAttribute('id');
                $attr->value = $product['id_element'];
                $offer->appendChild($attr);

                $attr = $dom->createAttribute('available');
                $attr->value = 'true';
                $offer->appendChild($attr);

                // <-- offer
                $url = $offer->appendChild($dom->createElement('url'));
                $url->appendChild(
                    $dom->createTextNode('https://kranik.by' . $product['url']));

                $price = $offer->appendChild($dom->createElement('price'));
                $price->appendChild(
                    $dom->createTextNode($product['price'] / 100));

                if (!empty($product['price_old']) AND ($product['price_old'] > ($product['price'] * 1.05)) AND ($product['price_old'] < ($product['price'] * 1.95))) {
                    $oldprice = $offer->appendChild($dom->createElement('oldprice'));
                    $oldprice->appendChild(
                        $dom->createTextNode($product['price_old'] / 100));
                }

                $currencyId = $offer->appendChild($dom->createElement('currencyId'));
                $currencyId->appendChild(
                    $dom->createTextNode('BYN'));

                $categoryId = $offer->appendChild($dom->createElement('categoryId'));
                $categoryId->appendChild(
                    $dom->createTextNode($product['id_category']));

                $picture = $offer->appendChild($dom->createElement('picture'));
                $picture->appendChild(
                    $dom->createTextNode('https://kranik.by/statics/catalog/photo/images/' . $product['picture']));

                $delivery = $offer->appendChild($dom->createElement('delivery'));
                $delivery->appendChild(
                    $dom->createTextNode('true'));

                $local_delivery_cost = $offer->appendChild($dom->createElement('local_delivery_cost'));
                $local_delivery_cost->appendChild(
                    $dom->createTextNode('0'));

//                $vendor = $offer->appendChild($dom->createElement('typePrefix'));
//                $vendor->appendChild(
//                    $dom->createTextNode($product['title_before']));

                $vendor = $offer->appendChild($dom->createElement('vendor'));
                $vendor->appendChild(
                    $dom->createTextNode($product['title_manufacturer']));

                if (!empty($product['title_model'])) {
                    $model = $offer->appendChild($dom->createElement('model'));
                    $model->appendChild(
                        $dom->createTextNode($product['title_model']));
                } else {
                    $model = $offer->appendChild($dom->createElement('model'));
                    $model->appendChild(
                        $dom->createTextNode($product['title']));
                }

                $name = $offer->appendChild($dom->createElement('name'));
                $name->appendChild(
                    $dom->createTextNode($product['title_manufacturer'] . ' ' . $product['title']));

                $description = $offer->appendChild($dom->createElement('description'));
                $description->appendChild(
                    $dom->createTextNode($product['title'] . ' купить в Минске по цене ниже рыночной! ✔В наличии! ✔Доставка по всей Беларуси!'));

                $offers->appendChild($offer);
            }
        }

        // offer -->
        // offers -->


//генерация xml
        $dom->formatOutput = true; // установка атрибута formatOutput domDocument в значение true
// save XML as string or file
//        $test1 = $dom->saveXML(); // передача строки в test1
        $dom->save('../../export/export_files/yandex_catalog.xml'); // сохранение файла

//        echo $dom->saveXML();


        /*        $xml = new XmlConstructor();
                $in = [
                    [
                        'tag' => 'shop',
                        'elements' => [
                            [
                                'tag' => 'name',
                                'content' => 'Краник',
                            ],
                            [
                                'tag' => 'company',
                                'content' => 'Сантехпром',
                            ],
                            [
                                'tag' => 'url',
                                'content' => 'https://kranik.by',
                            ],
                            [
                                'tag' => 'currencies',
                                'elements' => [
                                    [
                                        'tag' => 'currency',
                                        'attributes' => [
                                            'id' => 'BYN',
                                            'rate' => '1',
                                        ],
                                    ],
                                ],
                            ],
                            [
                                'tag' => 'categories',
                                'elements' => [
                                    [
                                        'tag' => 'category',
                                        'attributes' => [
                                            'id' => '1',
                                            'parentId' => '0',
                                        ],
                                        'content' => 'Душевые кабины',
                                    ],
                                ],
                            ],
                            [
                                'tag' => 'offers',
                                'elements' => [
                                    [
                                        'tag' => 'offer',
                                        'elements' => [
                                            [
                                                'tag' => 'url',
                                                'content' => 'https://kranik.by/smesiteli-i-komplektuyuschie/nakladnye-paneli/',
                                            ],
                                            [
                                                'tag' => 'price',
                                                'content' => '330.80',
                                            ],
                                            [
                                                'tag' => 'oldprice',
                                                'content' => '440.80',
                                            ],
                                            [
                                                'tag' => 'currencyId',
                                                'content' => 'BYN',
                                            ],
                                            [
                                                'tag' => 'categoryId',
                                                'content' => '28',
                                            ],
                                            [
                                                'tag' => 'picture',
                                                'content' => 'https://kranik.by/media/files/products/1476448694-15743000.jpg',
                                            ],
                                            [
                                                'tag' => 'delivery',
                                                'content' => 'true',
                                            ],
                                            [
                                                'tag' => 'local_delivery_cost',
                                                'content' => '0',
                                            ],
                                            [
                                                'tag' => 'typePrefix',
                                                'content' => 'Ванна',
                                            ],
                                            [
                                                'tag' => 'vendor',
                                                'content' => 'Roca',
                                            ],
                                            [
                                                'tag' => 'model',
                                                'content' => 'Сиденье для унитаза FRONTALIS с мех-мом "мягкое закрывание" бел',
                                            ],
                                            [
                                                'tag' => 'description',
                                                'content' => 'Код: 98701. Официальный импортер в РБ. Профессиональная консультация. Все виды платежей, Официальная гарантия, чек. Скидки. Доставка сантехники по РБ. Вся Сантехника и Керамическая Плитка - в магазине kranik.by.',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ];

                echo $xml->fromArray($in)->toOutput();*/


//        return 'Hello, world!';
//        return $this->render('cron', []);
    }

}