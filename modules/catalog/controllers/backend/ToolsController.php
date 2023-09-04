<?php

namespace common\modules\catalog\controllers\backend;

use common\modules\catalog\models\Field;
use Yii;
use backend\components\Controller;
use common\modules\catalog\components\Tools;
use yii\helpers\Html;

class ToolsController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => [
                    'index','tool1','tool2','tool3','tool4','tool5','tool6','ajax',
                    'tool7','tool7-get-fields', 'tool7-get-manufacturers', 'tool7-set-fields'
                ],
                'roles' => ['@']
            ]
        ];

        return $behaviors;
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionTool1()
    {
        $alias = Yii::$app->request->post('alias');
        if (isset($alias) && $alias != '') {
            return $this->render('tool1',[
                'mes' => Tools::tool1($alias)
            ]);
        } else {
            return $this->render('tool1');
        }
    }

    public function actionTool2()
    {
        $alias = Yii::$app->request->post('alias');
        if (isset($alias) && $alias != '') {
            return $this->render('tool2',[
                'mes' => Tools::tool2($alias)
            ]);
        } else {
            return $this->render('tool2');
        }
    }

    public function actionTool3()
    {
        return $this->render('tool3');
    }

    public function actionTool4()
    {
        $alias = Yii::$app->request->post('alias');
        if (isset($alias) && $alias != '') {
            return $this->render('tool4',[
                'mes' => Tools::tool4($alias)
            ]);
        } else {
            return $this->render('tool4');
        }
    }

    public function actionTool5()
    {
        $alias = Yii::$app->request->post('alias');
        if (isset($alias) && $alias != '') {
            return $this->render('tool5',[
                'mes' => Tools::tool5($alias)
            ]);
        } else {
            return $this->render('tool5');
        }
    }

    public function actionTool6()
    {
        $alias = Yii::$app->request->post('alias');
        if (isset($alias) && $alias != '') {
            return $this->render('tool6',[
                'mes' => Tools::tool6($alias)
            ]);
        } else {
            return $this->render('tool6');
        }
    }

    // Tool 7 =================================

    public function actionTool7()
    {
        return $this->render('tool7');
    }

    public function actionTool7GetFields()
    {
        echo json_encode(Tools::tool7GetFields());
    }

    public function actionTool7GetManufacturers()
    {
        echo json_encode(Tools::tool7GetManufacturers());
    }

    public function actionTool7SetFields()
    {
        Tools::tool7SetFields();
    }

    // /tool 7 =================================


    public function actionAjax()
    {
        if (1) { //if(Yii::$app->request->isAjax){

            $input = Yii::$app->request->post('input');
            $start = Yii::$app->request->post('start');
            $alias = Yii::$app->request->post('alias');

            /*
            $input = '34558000+31368000+182000200+teka272010200+h20008';
            $start = '1';
            $alias = 'faucet';
            */

            // Точно работает: 1-7
            if ($start==1)
            {

                $urls = explode('+',$input);
                $urls = array_chunk($urls, 7);

                $arr = [];
                $arrTitle = [];
                $group='';

                foreach ($urls as $url) {

                    $url = implode('+',$url);
                    $html = file_get_contents('http://catalog.onliner.by/compare/'.$url);

                    $facets = json_decode(Tools::getSslPage('https://catalog.api.onliner.by/facets/'.$alias));

                    $dictionaries = $facets->dictionaries;
                    $facets = $facets->facets->general->items;

                    $document = \phpQuery::newDocumentHTML($html);

                    foreach (pq($document)->find(".product-table__group tr") as $tr) {
                        if (!pq($tr)->hasClass('product-table__row_empty')) {
                            if (pq($tr)->hasClass('product-table__title')) {
                                $group = trim(pq($tr)->text());
                            } else {
                                $title = trim(pq($tr)->find('td:eq(0)')->find('span:eq(0)')->text());
                                if (!in_array($title,$arrTitle)) {
                                    $arrTitle[] = $title;
                                    $descr = '';
                                    if(trim(pq($tr)->find('td:eq(0)')->find('div:eq(0)')->html()) != '') {
                                        $descr =  trim(pq($tr)->find('td:eq(0)')->find('div:eq(0)')->find('span:eq(0)')->attr('data-tip-text'));
                                    }

                                    $url = 'https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20150714T074908Z.f2e7ab0d12e54312.73df893fd48854d8652cecb880be0fc594a02dff&lang=ru-en&text='.$title;
                                    $data = json_decode(file_get_contents($url));
                                    $alias = strtolower($data->text['0']);
                                    $alias = str_replace(' ','_',$alias);

                                    // Алиас, если есть в фильтре
                                    foreach($facets as $facet) {
                                        if($facet->name != 'Производитель' && $facet->name == $title) {
                                            $alias = $facet->parameter_id;
                                            break;
                                        }
                                    }

                                    // Если список с вариантами, то берём их
                                    $dictionariesNew = [];
                                    $vars = '';
                                    foreach($facets as $facet) {
                                        if($facet->type == 'dictionary') {
                                            $dictionariesI = 1;
                                            foreach ($dictionaries as $dictionaryKey => $dictionary) {
                                                if ($dictionaryKey == $alias) {
                                                    foreach($dictionary as $d) {
                                                        $dictionariesNew[] = [
                                                            'title' => $d->name,
                                                            'alias' => $d->id,
                                                            'db_val' => $dictionariesI * 10,
                                                            'sort' => $dictionariesI,
                                                            'check' => 0,
                                                        ];
                                                        $dictionariesI++;
                                                    }
                                                }
                                            }
                                            $vars = serialize($dictionariesNew);
                                            break;
                                        }
                                    }
                                    unset($dictionariesNew);

                                    $arr[] = [
                                        'group' => $group,
                                        'title' => $title,
                                        'alias' => $alias,
                                        'descr' => $descr,
                                        'vars'  => $vars,
                                    ];
                                }
                            }
                        }
                    }

                    \phpQuery::unloadDocuments();
                    gc_collect_cycles();
                }

                /*
                                $arr = array();
                                $arr[]=array(
                                    'group' => '11',
                                    'title' => '12',
                                    'title_filter' => '12',
                                    'alias' => '13',
                                    'descr' => '14',
                                );
                                $arr[]=array(
                                    'group' => '21',
                                    'title' => '22',
                                    'title_filter' => '12',
                                    'alias' => '23',
                                    'descr' => '24',
                                );
                */

                /*
                //$arr = 'a:29:{i:0;a:4:{s:5:"group";s:16:"Основные";s:5:"title";s:37:"Определитель номера";s:5:"alias";s:9:"caller_id";s:5:"descr";s:286:"Помимо стандартного современного метода определения номера - Caller ID (CLIP), на старых АТС поддерживается советский АОН, срабатывающий в момент поднятия трубки.";}i:1;a:4:{s:5:"group";s:16:"Основные";s:5:"title";s:15:"ЖК-экран";s:5:"alias";s:10:"lcd_screen";s:5:"descr";s:127:"Многие факсимильные аппараты оснащаются ЖК-экраном, обычно строчным.";}i:2;a:4:{s:5:"group";s:16:"Основные";s:5:"title";s:43:"Многоканальный телефон";s:5:"alias";s:16:"multi-line_phone";s:5:"descr";s:148:"Многоканальные телефоны подключаются сразу к нескольким аналоговым (POTS) линиям.";}i:3;a:4:{s:5:"group";s:16:"Основные";s:5:"title";s:31:"Телефонная книга";s:5:"alias";s:9:"phonebook";s:5:"descr";s:169:"Дорогие модели проводных телефонов имеют телефонную книгу на небольшое количество номеров.";}i:4;a:4:{s:5:"group";s:16:"Основные";s:5:"title";s:44:"Журнал входящих вызовов";s:5:"alias";s:21:"the_incoming_call_log";s:5:"descr";s:241:"Журнал входящих вызовов содержит номера последних звонивших абонентов. Для его работы необходима функция определения номера (АОН).";}i:5;a:4:{s:5:"group";s:16:"Основные";s:5:"title";s:46:"Журнал исходящих вызовов";s:5:"alias";s:21:"the_outgoing_call_log";s:5:"descr";s:115:"Журнал исходящих вызовов содержит последние набранные номера.";}i:6;a:4:{s:5:"group";s:16:"Основные";s:5:"title";s:28:"Набор на трубке";s:5:"alias";s:15:"set_on_the_tube";s:5:"descr";s:166:"Существуют модели телефонов, у которых набор номера осуществляется на трубке, а не на базе.";}i:7;a:4:{s:5:"group";s:16:"Основные";s:5:"title";s:41:"Регулировка громкости";s:5:"alias";s:20:"adjusting_the_volume";s:5:"descr";s:178:"Многие телефонные аппараты позволяют регулировать громкость как динамика в трубке, так и звонка.";}i:8;a:4:{s:5:"group";s:16:"Основные";s:5:"title";s:31:"Подсветка кнопок";s:5:"alias";s:19:"button_illumination";s:5:"descr";s:105:"Подсветка кнопок удобна для работы с аппаратом в темноте.";}i:9;a:4:{s:5:"group";s:16:"Основные";s:5:"title";s:41:"Подключение гарнитуры";s:5:"alias";s:18:"headset_connection";s:5:"descr";s:148:"Иногда к проводному телефону можно подключить гарнитуру - наушники с микрофоном.";}i:10;a:4:{s:5:"group";s:16:"Основные";s:5:"title";s:53:"Возможность монтажа на стену";s:5:"alias";s:35:"the_possibility_of_mounting_on_wall";s:5:"descr";s:99:"Многие проводные телефоны можно монтировать на стену.";}i:11;a:4:{s:5:"group";s:14:"Питание";s:5:"title";s:14:"Питание";s:5:"alias";s:4:"food";s:5:"descr";s:278:"Проводной телефон может питаться: 1) только от телефонной сети; 2) от сети 220V (обычно это многофункциональные аппараты с АОН); 3) от аккумуляторов/батареек.";}i:12;a:4:{s:5:"group";s:51:"Функциональные особенности";s:5:"title";s:41:"Цифровой автоответчик";s:5:"alias";s:25:"digital_answering_machine";s:5:"descr";s:156:"Некоторые аппараты оснащаются цифровым автоответчиком с памятью на несколько минут.";}i:13;a:4:{s:5:"group";s:51:"Функциональные особенности";s:5:"title";s:39:"Дуплексный спикерфон";s:5:"alias";s:19:"duplex_speakerphone";s:5:"descr";s:365:"Большинство факсимильных аппаратов имеют функцию мониторинга линии (воспроизведение сигнала на динамике), но не у всех есть дуплексный спикерфон, позволяющий полноценно использовать громкую связь.";}i:14;a:4:{s:5:"group";s:51:"Функциональные особенности";s:5:"title";s:37:"Однокнопочный набор";s:5:"alias";s:14:"one-touch_dial";s:5:"descr";s:139:"Многие аппараты имеют программируемые кнопки набора номера одним касанием.";}i:15;a:4:{s:5:"group";s:51:"Функциональные особенности";s:5:"title";s:31:"Ускоренный набор";s:5:"alias";s:10:"redialling";s:5:"descr";s:148:"Некоторые аппараты имеют функцию ускоренного набора номера по его коду в памяти.";}i:16;a:4:{s:5:"group";s:51:"Функциональные особенности";s:5:"title";s:31:"Индикатор вызова";s:5:"alias";s:14:"the_call_light";s:5:"descr";s:261:"Некоторые модели телефонов имеют световой индикатор вызова (входящего звонка), который полезен, если громкость звонка установлена на минимум.";}i:17;a:4:{s:5:"group";s:51:"Функциональные особенности";s:5:"title";s:31:"Удержание вызова";s:5:"alias";s:9:"call_hold";s:5:"descr";s:116:"При удержании вызова аппарат, как правило, проигрывает мелодию.";}i:18;a:4:{s:5:"group";s:51:"Функциональные особенности";s:5:"title";s:20:"Автодозвон";s:5:"alias";s:6:"redial";s:5:"descr";s:77:"Функция автодозвона по набранному номеру.";}i:19;a:4:{s:5:"group";s:51:"Функциональные особенности";s:5:"title";s:29:"Повторный набор";s:5:"alias";s:6:"redial";s:5:"descr";s:64:"Повторный набор последнего номера.";}i:20;a:4:{s:5:"group";s:51:"Функциональные особенности";s:5:"title";s:33:"Блокировка набора";s:5:"alias";s:8:"lock_set";s:5:"descr";s:89:"В ряде моделей можно заблокировать набор номера.";}i:21;a:4:{s:5:"group";s:51:"Функциональные особенности";s:5:"title";s:39:""Детский звонок"";s:5:"alias";s:0:"";s:5:"descr";s:448:""Детский звонок" - это функция автоматического набора выбранного номера при случайном нажатии на любую клавишу. Функция актуальна для детей или пожилых людей, которые остаются дома в одиночестве и не способны самостоятельно набрать номер.";}i:22;a:4:{s:5:"group";s:51:"Функциональные особенности";s:5:"title";s:46:"Отключение микрофона (Mute)";s:5:"alias";s:35:"switching_off_the_microphone_(mute)";s:5:"descr";s:120:"Как правило, у многих телефонов есть кнопка отключения микрофона.";}i:23;a:4:{s:5:"group";s:51:"Функциональные особенности";s:5:"title";s:40:"Будильник и календарь";s:5:"alias";s:24:"alarm_clock_and_calendar";s:5:"descr";s:143:""Навороченные" телефоны обычно имеют функции будильника, календаря и т.д.";}i:24;a:4:{s:5:"group";s:51:"Функциональные особенности";s:5:"title";s:22:"Калькулятор";s:5:"alias";s:10:"calculator";s:5:"descr";s:73:"Калькулятор при помощи клавиш телефона.";}i:25;a:4:{s:5:"group";s:24:"Размеры и вес";s:5:"title";s:12:"Ширина";s:5:"alias";s:5:"width";s:5:"descr";s:39:"Ширина в миллиметрах.";}i:26;a:4:{s:5:"group";s:24:"Размеры и вес";s:5:"title";s:12:"Высота";s:5:"alias";s:6:"height";s:5:"descr";s:60:"Высота устройства в миллиметрах.";}i:27;a:4:{s:5:"group";s:24:"Размеры и вес";s:5:"title";s:14:"Глубина";s:5:"alias";s:5:"depth";s:5:"descr";s:41:"Глубина в миллиметрах.";}i:28;a:4:{s:5:"group";s:24:"Размеры и вес";s:5:"title";s:6:"Вес";s:5:"alias";s:6:"weight";s:5:"descr";s:28:"Вес устройства.";}}';
                //$arr = preg_replace('!s:(\d+):"(.*?)";!e', "'s:'.strlen('$2').':\"$2\";'", $arr);
                //echo json_encode(unserialize($arr));

                //$arr = unserialize('');
                */

                echo json_encode($arr);

            }
            else if ($start==2)
            {
                header("Content-type: text/html; charset=utf-8");
                if ($input!='') {
                    echo json_encode(unserialize($input));
                } else {
                    echo json_encode($input);
                }
            }
            else if ($start==3)
            {
                header("Content-type: text/html; charset=utf-8");
                echo serialize($input);
            }
            else if ($start==4)
            {
                echo Tools::print_r_reverse($input);
            }
            else if ($start==5)
            {
                header("Content-type: text/html; charset=utf-8");
                foreach ($input as $k=>$v) {
                    $input[$k]['vars'] = explode(',',$input[$k]['vars']);
                }
                echo serialize($input);
            }
            // В SQL
            else if ($start==6)
            {
                $input = json_decode($input);
                $group_sort = 1;
                // Обходим все поля
                foreach ($input as $f) {

                    // Проверка существования группы, если нет, то добавить. Если есть изменить порядок(единственное что может поменяться, если заголовок, то нужно в базу лесть)
                    $command = \common\modules\catalog\models\backend\FieldGroup::find()
                        ->select('id')
                        ->where('title=:title AND id_category=:id_category',[':title'=>$f->group,':id_category'=>$f->id_category])
                        ->limit(1)->asArray()->one();
                    $id_group = $command['id'];

                    // Существует. Обновим сортировку.
                    if (isset($id_group) && $id_group > 0) {
                        Yii::$app->db->createCommand()->update('tbl_catalog_field_group', ['sort' => $group_sort], 'title=:title', [':title' => $f->group])->execute();
                    }
                    else
                    {
                        $model = new \common\modules\catalog\models\backend\FieldGroup();
                        $model->id_category = $f->id_category;
                        $model->title = $f->group;
                        $model->sort = $group_sort;
                        if (!$model->save()) {
                            //@todo: error
                            throw new HttpException('Группа свойств не создана');
                            return false;
                        }
                        $id_group = Yii::$app->db->getLastInsertID();

                        $group_sort++;
                    }

                    // Танцы с сериализацией
                    if ($f->copy!='') {
                        $f->dop['copy'] = $f->copy;
                    }
                    if ($f->val_in_text!='') {
                        $f->dop['val_in_text'] = $f->val_in_text;
                    }
                    if ($f->check_var!='') {
                        $f->dop['check_var'] = $f->check_var;
                    }
                    if ($f->izm_in_text!='') {
                        $f->dop['izm_in_text'] = $f->izm_in_text;
                    }
                    if ($f->razdelitel!='') {
                        $f->dop['razdelitel'] = $f->razdelitel;
                    }
                    if ($f->to_category!='') {
                        $f->dop['to_category'] = $f->to_category;
                    }

                    $dop = serialize($f->dop);

                    // Проверка на существование поля. (если id поля получили, значит оно есть, не получили значит нет)
                    $id_element = $f->field_id;

                    // Если существует обновляем
                    if ($id_element > 0)
                    {
                        Yii::$app->db->createCommand()
                            ->update('tbl_catalog_field',
                                [
                                    //'id_category'=>$f->id_category,
                                    'id_group'=>$id_group,
                                    'alias'=>$f->alias,
                                    'type'=>$f->type,
                                    'variant'=>$f->variant,
                                    'name'=>$f->title,
                                    'name_filter'=>$f->title_filter,
                                    'description'=>$f->desc,
                                    'unit'=>$f->metering,
                                    'sort'=>$f->sort,
                                    'dop'=>$dop,
                                    'compare'=>$f->compare,
                                    'published'=>$f->published,
                                ],
                                'id=:id',
                                [':id'=>$id_element])->execute();
                    }
                    //Иначе добавляем
                    else
                    {
                        $model = new \common\modules\catalog\models\backend\Field();
                        //$model->id_category = $f->id_category;
                        $model->id_group = $id_group;
                        $model->alias = $f->alias;
                        $model->type = $f->type;
                        $model->variant = $f->variant;
                        $model->name = $f->title;
                        $model->name_filter = $f->title_filter;
                        $model->description = $f->desc;
                        $model->unit = $f->metering;
                        $model->sort = $f->sort;
                        $model->dop = $dop;
                        $model->compare = $f->compare;
                        $model->published = $f->published;
                        if (!$model->save()) {
                            //@todo: error
                            throw new HttpException('Поле '.$f->title.' не создано');
                            return false;
                        }
                    }
                }
                echo 'готово';
            }
            // Из SQL
            else if ($start==7)
            {
                $query = new \yii\db\Query;
                $query
                    ->select('cf.*, cf.name as title, cf.name_filter as title_filter, cf.unit as metering, cf.description as desc, cfg.title as group')
                    ->from('{{%catalog_field}} cf')
                    ->leftJoin('{{%catalog_field_group}} cfg', 'cf.id_group = cfg.id')
                    ->where('cfg.id_category=:id_category', [':id_category' => $input])
                    ->orderBy('cf.sort asc');
                $command = $query->createCommand();
                $filters = $command->queryAll();
                foreach ($filters as $k=>$filter) {
                    $dop = unserialize($filter['dop']);
                    if ($dop['check_var']) $filters[$k]['check_var'] = $dop['check_var'];
                    if ($dop['copy']) $filters[$k]['copy'] = $dop['copy'];
                    if ($dop['val_in_text']) $filters[$k]['val_in_text'] = $dop['val_in_text'];
                    if ($dop['izm_in_text']) $filters[$k]['izm_in_text'] = $dop['izm_in_text'];
                    if ($dop['razdelitel']) $filters[$k]['razdelitel'] = $dop['razdelitel'];
                    if ($dop['to_category']) $filters[$k]['to_category'] = $dop['to_category'];
                }
                echo json_encode($filters);
            }
            // сортировка товаром в порядке зколичества заполненных товаров
            else if ($start==8)
            {
                //Берём из файла сериализованные данные о товарах
                $array = [];
                $f = fopen($_SERVER['DOCUMENT_ROOT'] . "/txt/" . $input . "_step1_data.txt", "r");
                while (!feof($f)) {
                    $arrE = fgets($f);
                    if ($arrE != '') {
                        $array[] =  unserialize($arrE);
                    }
                }
                fclose($f);

                // УЗнаём варианты полей
                $arr = [];

                foreach ($array as $ind=>$html) {

                    $html = str_replace("<!--Description-->", "", trim($html));
                    $html = str_replace("<!--/ Specs-->", "", trim($html));

                    $html = \phpQuery::newDocumentHTML($html);
                    $alias = pq($html)->find('#alias')->text();

                    //Перебор разделов свойств
                    if (!empty(pq($html)->find('tbody:eq(0)'))) {
                        foreach (pq($html)->find('tbody') as $tbody) {
                            //Перебор свойств раздела
                            foreach (pq($tbody)->find("tr") as $tr) {
                                if (!pq($tr)->hasClass('product-specs__table-title')) {
                                    // Имя поля
                                    if (pq($tr)->hasClass('product-specs__table-spread')) {
                                        $keyField = 'Описание';
                                    } else {
                                        $keyField = trim(pq($tr)->find('td:eq(0)')->html());
                                        $keyField = explode('<div', $keyField);
                                        $keyField = trim($keyField['0']);
                                    }
                                    // Добавить
                                    if ($arr[$alias]!== null && !in_array($keyField, $arr[$alias])) {
                                        $arr[$alias][] = $keyField;
                                    } else {
                                        $arr[$alias][] = $keyField;
                                    }
                                }
                            }
                        }
                    }

                    unset($array[$ind]);
                    \phpQuery::unloadDocuments();
                    gc_collect_cycles();
                }

                $arrVspom = [];
                $result = [];

                foreach($arr as $alias_key=>$product) { //элемент
                    foreach ($product as $field) { //свойства
                        if (!in_array($field, $arrVspom) && $field != '') { //Свойства нет в товаре
                            $arrVspom[] = $field;
                            if (!in_array($alias_key, $result)) { //и товар пока не добавлен
                                $result[] = $alias_key; // добавим товар
                            }
                        }
                    }
                    unset($arr[$alias_key]);
                }

                $arr =  ['str'=>implode('+',$result),'cnt'=>count($arrVspom),'arr'=>$arrVspom];
                echo json_encode($arr);
            }
            else if ($start==9) //Tool2 Добавление значений не спарсенных полей
            {
                $input = json_decode($input);
                foreach($input as $inp) {
                    // Добавляем раз
                    $ddd1 = '';
                    $ddd2 = 0;
                    $sql = "INSERT INTO `tbl_catalog_value`(`value`, `dop`, `text`) VALUES (:value, :dop, :text)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":value", $inp->value, PDO::PARAM_STR);
                    $command->bindParam(":dop", $ddd1, PDO::PARAM_INT);
                    $command->bindParam(":text", $ddd2, PDO::PARAM_STR);
                    $command->execute();
                    $command->reset();
                    $last_id = Yii::app()->db->getLastInsertID();
                    // Добавляем два
                    $sql = "INSERT INTO `tbl_catalog_element_field_value`(`id_field`, `id_element`, `id_value`) VALUES (:id_field, :id_element, :id_value)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":id_element", $inp->id_element, PDO::PARAM_INT);
                    $command->bindParam(":id_field", $inp->id_field, PDO::PARAM_INT);
                    $command->bindParam(":id_value", $last_id, PDO::PARAM_INT);
                    $command->execute();
                    $command->reset();
                }
                echo 'готово';
            }
            else if ($start==10)
            {
                $id = Yii::$app->request->post('field_id');
                Field::deleteAll('id = :id', [':id' => $id]);
            }
            // Завершаем приложение
            Yii::$app->end();
        }
    }

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

}







