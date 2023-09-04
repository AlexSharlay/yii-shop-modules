<?php

namespace common\modules\catalog\components;
include($_SERVER['DOCUMENT_ROOT'].'/vendor/electrolinux/phpquery/phpQuery/phpQuery.php');
use common\modules\catalog\models\backend\Category;
use common\modules\catalog\models\backend\Element;
use common\modules\catalog\models\backend\Field;
use common\modules\catalog\models\backend\FieldElementValue;
use common\modules\catalog\models\backend\FieldValue;
use common\modules\catalog\models\backend\Manufacturer;
use frontend\themes\shop\pageAssets\blogs\objects;
use yii\base\ErrorException;
use yii\db\Query;
use yii\helpers\Html;
use yii\web\HttpException;
use Yii;

ini_set('memory_limit', '-1');
set_time_limit(0);

class Tools
{

    public static function tool1($alias) {
        $links_file = Tools::getStep1Url($alias);                      // Получить ссылки уже скачанных товаров из _step1_url.txt
        $links_url_new  = Tools::getStep1UrlNew($alias);               // _step1_url_new.txt
        $links_url  = Tools::getLinksUrl($alias);                       // Получить все ссылки товаров с онлайнера
        $links = Tools::saveLinksFile($alias,$links_url,$links_url_new,$links_file);   // Узнать какие ссылки не скачаны и сохранить в _step1_url_new.txt

        return Tools::getProducts($alias,$links);                       // Скачать эти товары по ссылкам из _step1_url_new.txt в _step1_data.txt
    }

    public static function getStep1Url($alias) {
        $array = [];
        $f = fopen($_SERVER['DOCUMENT_ROOT'] . "/txt/".$alias."_step1_url.txt", "a+");
        while (!feof($f)) {
            $arrE = rtrim(fgets($f));
            if ($arrE != '') {
                $array[] =  $arrE;
            }
        }
        fclose($f);
        return $array;
    }

    public static function getStep1UrlNew($alias) {
        $array = [];
        $f = fopen($_SERVER['DOCUMENT_ROOT'] . "/txt/".$alias."_step1_url_new.txt", "a+");
        while (!feof($f)) {
            $arrE = rtrim(fgets($f));
            if ($arrE != '') {
                $array[] =  $arrE;
            }
        }
        fclose($f);
        return $array;
    }

    public static function getLinksUrl($alias) {
        $obj = json_decode(Tools::getResponse('https://catalog.api.onliner.by/search/'.$alias));
        if ($obj->message != 'Invalid schema '.$alias) {
            $last_page = $obj->page->last;
            $arrLinks = [];
            for($i=1;$i<=$last_page;$i++) {
                $obj = json_decode(Tools::getResponse('https://catalog.api.onliner.by/search/'.$alias.'?page='.$i.'&group=0'));
                if (is_array($obj->products)) {
                    foreach($obj->products as $product) {
                        $arrLinks[] = rtrim($product->html_url);
                    }
                }
            }
            $obj = null;
            return $arrLinks;
        } else {
            throw new \yii\web\NotFoundHttpException('Error');
        }
    }

    public static function saveLinksFile($alias,$links_url,$links_url_new,$links_file) {
        $links = array_diff($links_url, array_merge($links_file,$links_url_new));

        $f = fopen($_SERVER['DOCUMENT_ROOT']."/txt/".$alias."_step1_url_new.txt", 'a+');
        foreach($links as $link) {
            fwrite($f, $link.PHP_EOL);
        }
        fclose($f);
        unset($links);
        return self::getStep1UrlNew($alias);
    }

    public static function getProducts($alias,$links) {

        foreach($links as $link) {
            $link = trim($link);

            // Скачиваем товар, парсим, удаляем мусор
            $product = Tools::getResponse($link);
            $document = \phpQuery::newDocumentHTML($product)->find('div.product-primary-i');
            pq($document)->find('script')->remove();
            pq($document)->find('style')->remove();
            pq($document)->find('link')->remove();
            pq($document)->find('#product-review')->remove();
            pq($document)->find('.b-offers-desc__info-price-social')->remove();
            pq($document)->find('.b-offers-desc__info-rating')->remove();
            pq($document)->find('.product-specs__bottom')->remove();

            // Сохраняем товар в _step1_data.txt
            $f = fopen($_SERVER['DOCUMENT_ROOT']."/txt/".$alias."_step1_data.txt", 'a+');
            fwrite($f, serialize(str_replace(["\r", "\n"], '', '<div><div id="alias">'.array_pop(explode('/',$link)).'</div>'.$document->html().'</div>')).PHP_EOL);
            fclose($f);

            // Сохроаняем ссылку на этот товар
            $f = fopen($_SERVER['DOCUMENT_ROOT']."/txt/".$alias."_step1_url.txt", 'a');
            fwrite($f, $link.PHP_EOL);
            fclose($f);

            $file_out = self::getStep1UrlNew($alias);
            for ($i=0; $i<count($file_out); $i++) {
                if(trim($file_out[$i]) == $link){
                    unset($file_out[$i]);
                    break;
                }
            }

            // Очистить
            $fp = fopen($_SERVER['DOCUMENT_ROOT']."/txt/".$alias."_step1_url_new.txt", 'a');
            ftruncate($fp, 0);
            fclose($fp);

            // Сохранить
            self::saveLinksFile($alias,$file_out,[],[]);

            \phpQuery::unloadDocuments();
            gc_collect_cycles();
            unset($file_out);
            unset($product);
            unset($document);
        }
        return 'ГОТОВО!';
    }

    public static function convert($size)
    {
        $unit = ['b','kb','mb','gb','tb','pb'];
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    }

    public static function getResponse($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $obj = curl_exec($ch);
        curl_close($ch);
        return $obj;
    }


    public static function tool2($alias) {
        $products = Tools::getProductsFromFile($alias); // Получить данные уже скачанных товаров из _step1_data.txt
        return Tools::viewFields($products);            // Узнать все поля иварианты значений
    }

    public static function getProductsFromFile($alias) {
        $array = array();
        $f = fopen($_SERVER['DOCUMENT_ROOT']."/txt/".$alias."_step1_data.txt", "r");
        while (!feof($f)) {
            $arrE = rtrim(unserialize(fgets($f)));
            if ($arrE != '') {
                $array[] = $arrE;
            }
        }
        fclose($f);
        return $array;
    }

    public static function viewFields($products) {

        // УЗнаём варианты полей
        $arr = array();
        foreach ($products as $product) {

            //Закидываем в парсер
            $html = \phpQuery::newDocumentHTML($product);

            foreach(pq($html)->find('.product-specs__table tbody') as $group) {
                $keyGroup = "";
                $keyField = "";
                foreach(pq($group)->find('tr') as $tr) {
                    if (pq($tr)->hasClass('product-specs__table-title'))
                    {
                        // Группа
                        $keyGroup = trim(pq($tr)->text());
                    }
                    else if (pq($tr)->hasClass('product-specs__table-spread'))
                    {
                        // Поле: описание
                        $arr[$keyGroup]['Описание']['type'] = 5;
                    }
                    else
                    {

                        // Другие поля

                        //if (!isset($arr[$keyGroup]['Описание']['val'])) $arr[$keyGroup]['Описание']['val'] = array();

                        // Название поля
                        $keyField = trim(pq($tr)->find('td:eq(0)')->html());
                        $keyField = explode('<', $keyField);
                        $keyField = trim($keyField['0']);
                        if (!is_array($arr[$keyGroup][$keyField])) $arr[$keyGroup][$keyField] = array();

                        // Значения полей и тип

                        // X
                        // V
                        // V (текст), X (текст)
                        // V 1, V 100, V 1 номер, 100 номер
                        // V 1 (текст), V 100 (текст), V 1 номер (текст), 100 номер (текст)

                        // в <td></td>

                        // <span class="i-x"></span>
                        // <span class="i-tip"></span>
                        // <span class="i-tip"></span>&nbsp;(20 номеров)
                        // <span class="i-tip"></span><span class="value__text">1</span>
                        // <span class="i-tip"></span><span class="value__text">4&nbsp;линий</span>&nbsp;(SIP)

                        $a = trim(pq($tr)->find('td:eq(1)')->html());

                        if ($arr[$keyGroup][$keyField]['type'] == '1' && $arr[$keyGroup][$keyField]['dop'] == 'time')
                        {
                            // Нет смысла опять теребить время это или нет, если уже итак ясно
                        }
                        else
                        {
                            // Тип время
                            if (Tools::isTime($a)) {
                                $arr[$keyGroup][$keyField]['type'] = '1';
                                $arr[$keyGroup][$keyField]['dop'] = 'time';
                            } // Другие типы
                            else {
                                if (mb_strpos($a, 'i-x', 0, 'utf-8') || mb_strpos($a, 'i-tip', 0, 'utf-8')) {
                                    // Узнаём V или X, если есть
                                    if (mb_strpos($a, 'i-x', 0, 'utf-8')) {
                                        $arr[$keyGroup][$keyField]['type'] = '3';
                                        $v = 'val=0 dop=0';
                                        if (@!in_array($v, $arr[$keyGroup][$keyField]['val'])) $arr[$keyGroup][$keyField]['val'][] = $v;
                                        unset($v);
                                        $a = trim(Tools::str_replace_once('<span class= "i-x" ></span>', '', $a)); // Замена на ничего
                                    }
                                    if (mb_strpos($a, 'i-tip', 0, 'utf-8')) {
                                        $arr[$keyGroup][$keyField]['type'] = '3';
                                        $v = 'val=1 dop=1';
                                        if (@!in_array($v, $arr[$keyGroup][$keyField]['val'])) $arr[$keyGroup][$keyField]['val'][] = $v;
                                        unset($v);
                                        $a = trim(Tools::str_replace_once('<span class= "i-tip" ></span>', '', $a));
                                    }

                                    // Если в $a есть span значит есть значение, иначе остальное текст
                                    if (mb_strpos($a, 'span', 0, 'utf-8')) {
                                        $a = trim(Tools::str_replace_once('<span class="value__text">', '', $a));

                                        // <span class="i-tip"></span>
                                        // <span class="value__text">белая светодиодная</span>
                                        // (текст)

                                        $spans = explode('</span>', $a);
                                        $spans = array_diff($spans, array(''));

                                        // Если есть запятая, значит вариантов несколькою Иначе значение одно
                                        if (mb_strpos($spans['0'], ',', 0, 'utf-8') !== false) {
                                            $arr[$keyGroup][$keyField]['dop'] = 'full';
                                            $vars = explode(', ', $spans['0']);
                                            foreach ($vars as $v) {
                                                $v = trim(Tools::strChangeSpecChar($v));
                                                if ($v != '') {
                                                    //Если в массиве такого значения нет - добавить
                                                    if (@!in_array($v, $arr[$keyGroup][$keyField]['val'])) {
                                                        $arr[$keyGroup][$keyField]['val'][] = $v;
                                                    }
                                                }
                                                unset($v);
                                            }
                                        } else {
                                            if ($spans['0'] != '') {
                                                $v = trim(Tools::strChangeSpecChar($spans['0']));
                                                if (@!in_array($v, $arr[$keyGroup][$keyField]['val'])) {
                                                    $arr[$keyGroup][$keyField]['val'][] = $v;
                                                }
                                                unset($v);
                                                if ($arr[$keyGroup][$keyField]['dop'] != 'full') {
                                                    $arr[$keyGroup][$keyField]['dop'] = 'cut';
                                                }
                                            }
                                        }
                                        // Если есть текст ()
                                        $t = trim(Tools::strChangeSpecChar($spans['1']));
                                        if ($t != '' && @!in_array($t, $arr[$keyGroup][$keyField]['valText'])) {
                                            $arr[$keyGroup][$keyField]['valText'][] = $t;
                                        }
                                        unset($t);
                                    } else {
                                        // Текст всегда в ковычках ()
                                        $t = trim(Tools::strChangeSpecChar($a));
                                        if ($t != '' && @!in_array($t, $arr[$keyGroup][$keyField]['valText'])) {
                                            $arr[$keyGroup][$keyField]['valText'][] = $t;
                                        }
                                        unset($t);
                                    }
                                    unset($a);
                                } else if (mb_strpos($a, 'value__text', 0, 'utf-8')) {
                                    $a = trim(Tools::str_replace_once('<span class="value__text">', '', $a));
                                    $spans = explode('</span>', $a);
                                    $spans = array_diff($spans, array(''));

                                    if (!isset($arr[$keyGroup][$keyField]['val'])) $arr[$keyGroup][$keyField]['val'] = array();

                                    // Если есть запятая, значит вариантов несколькою Иначе значение одно
                                    if (mb_strpos($spans['0'], ',', 0, 'utf-8') !== false) {
                                        $vars = explode(', ', $spans['0']);
                                        foreach ($vars as $v) {
                                            $v = trim(Tools::strChangeSpecChar($v));
                                            if ($v != '') {
                                                //Если в массиве такого значения нет - добавить
                                                if (@!in_array($v, $arr[$keyGroup][$keyField]['val'])) {
                                                    //echo $v.' - '.$keyGroup.' - '.$keyField.'<br/>';
                                                    $arr[$keyGroup][$keyField]['val'][] = $v;
                                                }
                                            }
                                            unset($v);
                                        }
                                        unset($vars);
                                    } else {
                                        $v = trim(Tools::strChangeSpecChar($spans['0']));
                                        if ($v != '') {
                                            //echo $v.' - '.$keyGroup.' - '.$keyField.'<br/>';
                                            //Если в массиве такого значения нет - добавить
                                            if (@!in_array($v, $arr[$keyGroup][$keyField]['val'])) {
                                                $arr[$keyGroup][$keyField]['val'][] = $v;
                                            }
                                        }
                                        unset($v);
                                    }
                                    // Если есть текст ()
                                    $t = trim(Tools::strChangeSpecChar($spans['1']));
                                    if ($t != '' && @!in_array($t, $arr[$keyGroup][$keyField]['valText'])) {
                                        $arr[$keyGroup][$keyField]['valText'][] = $t;
                                    }
                                    unset($t);
                                } else {
                                    $t = trim(Tools::strChangeSpecChar($tr->find('td', 1)->innertext));
                                    if ($t != '' && @!in_array($t, $arr[$keyGroup][$keyField]['valText'])) {
                                        $arr[$keyGroup][$keyField]['valText'][] = $t;
                                    }
                                    unset($t);
                                }
                            }
                        }
                    }
                }
            }

            \phpQuery::unloadDocuments();
            gc_collect_cycles();
        }

        echo '<pre>';
        print_r($arr);
        echo '</pre>';

        echo "<hr/>";

        // Группы
        $group = array();
        $group_c = 0;
        foreach ($arr as $key_group => $group) {
            echo $key_group . '<br/>';
            $group_c++;
        }
        echo '<br/><b>Групп</b>: ' . $group_c . '<br/><br/>';

        echo "<hr/>";

        // Свойства
        $field = array();
        $field_c = 0;
        foreach ($arr as $key_group => $group) {
            echo '<br/>' . $key_group . '<br/>';
            foreach ($group as $key_field => $field) {
                if ($key_field != '') {
                    echo ' - ' . $key_field . '<br/>';
                    $field_c++;
                } else {
                    echo ' ОШИБКА. Пустое названия поля. Значения:';
                    echo "<pre>";
                    print_r($field);
                    echo "</pre>";
                    echo "<hr/>";
                }
            }
        }
        echo '<br/><b>Свойств</b>: ' . $field_c . '<br/>';

    }

    public static function strChangeSpecChar($a) {
        $a = str_replace("&nbsp;", " ", $a);
        $a = trim($a);
        $a = str_replace("&quot;", '"', $a);
        $a = str_replace("&lt;", '<', $a);
        $a = str_replace("&gt;", '>', $a);
        return $a;
    }

    public static function timeToMinut($time) {

        $arrTimeNedeli = array('недель','неделя','недели');
        $arrTimeSytki = array('сутки','суток');
        $arrTimeChasi = array('час','часов','часа');
        $arrTimeMinyti = array('минут','минута','минуты');

        $i=0;
        $ii=0;
        $arr= array();
        $time = explode(" ",$time);
        foreach ($time as $t) {
            if ($i==0) {
                $arr[$ii][$i] = $t;
                $i++;
            } else {
                $arr[$ii][$i] = $t;
                $i--;
                $ii++;
            }
        }

        $sum = 0;
        foreach ($arr as $a) {

            if (in_array($a['1'], $arrTimeNedeli)) {
                $sum += $a['0']*7*24*60;
            } else if(in_array($a['1'], $arrTimeSytki)) {
                $sum += $a['0']*24*60;
            } else if(in_array($a['1'], $arrTimeChasi)) {
                $sum += $a['0']*60;
            } else if(in_array($a['1'], $arrTimeMinyti)) {
                $sum += $a['0'];
            }
        }



        return $sum;

    }

    public static function isTime($time) {
        if (mb_strpos($time, 'недель', 0, 'utf-8') || mb_strpos($time, 'неделя', 0, 'utf-8') || mb_strpos($time, 'недели', 0, 'utf-8') ||
            mb_strpos($time, 'сутки', 0, 'utf-8') || mb_strpos($time, 'суток', 0, 'utf-8') ||
            mb_strpos($time, 'час', 0, 'utf-8') || mb_strpos($time, 'часов', 0, 'utf-8') || mb_strpos($time, 'часа', 0, 'utf-8') ||
            mb_strpos($time, 'минут', 0, 'utf-8') || mb_strpos($time, 'минута', 0, 'utf-8') || mb_strpos($time, 'минуты', 0, 'utf-8'))
        {
            return true;
        }
        else
        {
            return false;
        }

    }

    //заменить только первое совпадение в строке
    public static function str_replace_once($search, $replace, $text){
        $pos = mb_strpos($text, $search, 0, 'UTF-8');
        return $pos!==false ? substr_replace($text, $replace, $pos, strlen($search)) : $text;
    }


    public static function tool4($alias) {
        $array = [];
        $f = fopen($_SERVER['DOCUMENT_ROOT'] . "/txt/".$alias."_step1_url.txt", "a+");
        while (!feof($f)) {
            $arrE = rtrim(fgets($f));
            if ($arrE != '') {
                $arrE = explode('/',$arrE);
                $array[] =  array_pop($arrE);
            }
        }
        fclose($f);
        return implode('+',$array);
    }

    public static function print_r_reverse($in) {
        $arr = array();
        $a = preg_split('/\n|\r\n?/',$in);

        foreach ($a as $ak=>$av) {
            $av = explode('] =>',$av);
            if (trim($av[1])!='') {
                $arr[$ak]['title'] = trim($av[1]);

                $url = 'https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20150714T074908Z.f2e7ab0d12e54312.73df893fd48854d8652cecb880be0fc594a02dff&lang=ru-en&text='.trim($av[1]);
                $data = json_decode(file_get_contents($url));
                $data = $data->text['0'];
                $data = str_replace(" ", "_", $data);
                $data = str_replace('\\', "_", $data);
                $data = str_replace("/", "_", $data);
                $data = str_replace(",", "_", $data);
                $data = str_replace("-", "_", $data);
                $arr[$ak]['alias'] = $data; //self::GetInTranslit(trim($av[1]));
            }
        }

        // Отсортируем по title
        foreach ($arr as $k => $v) {
            $a_sort[$k] = $v['title'];
        }
        array_multisort($a_sort, SORT_ASC, $arr);


        $val_db = 1;
        $sort = 1;
        foreach ($a as $ak=>$av) {
            $arr[$ak]['val_db'] = $val_db;
            $arr[$ak]['sort'] = $sort;
            $val_db++;
            $sort++;
        }

        return json_encode($arr);
    }

    public static function GetInTranslit($string) {
        $replace=array(
            "'"=>"",
            "`"=>"",
            "а"=>"a","А"=>"a",
            "б"=>"b","Б"=>"b",
            "в"=>"v","В"=>"v",
            "г"=>"g","Г"=>"g",
            "д"=>"d","Д"=>"d",
            "е"=>"e","Е"=>"e",
            "ж"=>"zh","Ж"=>"zh",
            "з"=>"z","З"=>"z",
            "и"=>"i","И"=>"i",
            "й"=>"y","Й"=>"y",
            "к"=>"k","К"=>"k",
            "л"=>"l","Л"=>"l",
            "м"=>"m","М"=>"m",
            "н"=>"n","Н"=>"n",
            "о"=>"o","О"=>"o",
            "п"=>"p","П"=>"p",
            "р"=>"r","Р"=>"r",
            "с"=>"s","С"=>"s",
            "т"=>"t","Т"=>"t",
            "у"=>"u","У"=>"u",
            "ф"=>"f","Ф"=>"f",
            "х"=>"h","Х"=>"h",
            "ц"=>"c","Ц"=>"c",
            "ч"=>"ch","Ч"=>"ch",
            "ш"=>"sh","Ш"=>"sh",
            "щ"=>"sch","Щ"=>"sch",
            "ъ"=>"","Ъ"=>"",
            "ы"=>"y","Ы"=>"y",
            "ь"=>"","Ь"=>"",
            "э"=>"e","Э"=>"e",
            "ю"=>"yu","Ю"=>"yu",
            "я"=>"ya","Я"=>"ya",
            "і"=>"i","І"=>"i",
            "ї"=>"yi","Ї"=>"yi",
            "є"=>"e","Є"=>"e",

            'Q'=>'q', 'W'=>'w', 'E'=>'e', 'R'=>'r', 'T'=>'t', 'Y'=>'y', 'U'=>'u', 'I'=>'i', 'O'=>'o', 'P'=>'p',
            'A'=>'a', 'S'=>'s', 'D'=>'d', 'F'=>'f', 'G'=>'g', 'H'=>'h', 'J'=>'j', 'K'=>'k', 'L'=>'l',
            'Z'=>'z', 'X'=>'x', 'C'=>'c', 'V'=>'v', 'B'=>'b', 'N'=>'n', 'M'=>'m',
            '+'=>'plus', '-'=>'_', ' '=>'_', ')'=>'', '('=>'',
        );
        return $str=iconv("UTF-8","UTF-8//IGNORE",strtr($string,$replace));
    }

    public static function getOptArr($arr) {
        $newArr = array(array_shift($arr));
        $arrVspom = array();
        foreach($arr as $k=>$vals) {
            // Массив уже имеющихся значений в новом массиве
            foreach($newArr as $n_vals) {
                foreach($n_vals as $n_v) {
                    if (!in_array($n_v,$arrVspom)) {
                        $arrVspom[] = $n_v;
                    }
                }
            }
            // Проверка если в элементе есть какие-либо новые поля
            if (count(array_diff($vals, $arrVspom))>0) {
                $newArr[] = $arr[$k];
            }
        }
        $str = '';
        foreach($arr as $k=>$vals) {
            foreach($newArr as $k_nr=>$n_vals) {
                if ($vals==$n_vals) {
                    $str .= $k.'+';
                    unset($newArr[$k_nr]);
                }
            }
        }
        $str = substr($str, 0, -1);

        // Попытка избавиться от лишних, если есть
        // Пока не нужно

        return array('str'=>$str,'cnt'=>count($arrVspom),'arr'=>$arrVspom);
    }

    public static function in_array_my($str,$arr) {
        $res = false;
        if (count($arr)>0) {
            foreach($arr as $arr_str) {
                if ($str  == $arr_str) {
                    $res = true;
                    break;
                }
            }
        }
        return $res;
    }


    public static function tool5($alias) {
        $json = self::getSslPage('https://catalog.api.onliner.by/facets/'.$alias);
        $obj = json_decode($json);
        $obj = $obj->dictionaries->mfr;
        foreach($obj as $item) {
            $id = \common\modules\catalog\models\backend\Manufacturer::find()
                ->select('id')->where('alias=:alias',[':alias'=>$item->id])->limit(1)->asArray()->one();
            if (is_null($id)) {
                $model = new \common\modules\catalog\models\backend\Manufacturer();
                $model->title = $item->name;
                $model->alias = $item->id;
                $model->published = 1;
                //$model->validate();
                //var_dump($model->errors);
                if (!$model->save()) {
                    //@todo: error
                    throw new HttpException('Ошибка создания бренда');
                }
            }
        }

        return 'Готово';
    }

    public static function getSslPage($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public static function parse($in)
    {
        /*
         * http://shop.by/backend/catalog/element/parse/
        $in = [
            'url' => 'http://catalog.onliner.by/faucet/grohe/31368000',
            'id_category' => 32,
            'id_measurement' => 3,
            'create' => 1
        ];
        */

        $url = $in['url'];

        // для добавления
        $id_category = $in['id_category'];
        $id_measurement = $in['id_measurement'];

        // для обновления
        $create = $in['create'];
        $id_element  = $in['id_element'];

        //
        $messages = [];

        // Парс страницы товара
        $html = Tools::getResponse($url);
        $document = \phpQuery::newDocumentHTML($html);

        // Получаем из бд все поля характеристик
        $fields_db = Field::find()
            ->select('cf.*, cfg.title as group')
            ->from('tbl_catalog_field cf')
            ->leftJoin('tbl_catalog_field_group cfg', 'cf.id_group = cfg.id')
            ->where('cfg.id_category=:id_category', ['id_category' => $id_category])
            ->asArray()
            ->all();

        $count_fields = count($fields_db);

        // Сбор полей
        $product = [];
        $product['manufacturer']['title'] = trim(pq($document)->find('.breadcrumbs__list .breadcrumbs__link:eq(2)')->text());
        $product['manufacturer']['alias'] = trim(array_pop(explode('/', pq($document)->find('.breadcrumbs__list .breadcrumbs__link:eq(2)')->attr('href'))));
        $product['title_before'] = trim(array_shift(explode(' ' . $product['manufacturer']['title'] . ' ', pq($document)->find('.catalog-masthead__title')->text())));
        $product['title'] = trim(array_pop(explode(' ' . $product['manufacturer']['title'] . ' ', pq($document)->find('.catalog-masthead__title')->text())));
        $product['alias'] = trim(array_pop(explode('/', $url)));
        $product['desc_mini'] = trim(pq($document)->find('.b-offers-desc__info-specs p:eq(0)')->text());

        if ($create) {
            if ($id_category != '' && $id_measurement != '') {

                // Получить id производителя
                $id_manufacturer = Manufacturer::find()->select('id')->from('tbl_catalog_manufacturer')->where('alias=:alias', [':alias' => $product['manufacturer']['alias']])->asArray()->one()['id'];

                // Если производителя нет, то создаём его
                if (is_null($id_manufacturer)) {
                    // @todo: проверка на ошибки
                    $manufacturer = new Manufacturer();
                    $manufacturer->alias = $product['manufacturer']['alias'];
                    $manufacturer->title = $product['manufacturer']['title'];
                    $manufacturer->save();
                    $id_manufacturer = Yii::$app->db->getLastInsertID();
                    $messages['success'][] = 'Добавлен новый производитель: ' . $product['manufacturer']['title'];
                }

                // Проверяем есть ли такая запись уже в бд. Проверка по алиасу
                $element_db = Element::find()->select('id')->from('tbl_catalog_element')->where('alias=:alias', ['alias' => $product['alias']])->asArray()->one();
                $element_db = $element_db['id'];

                // Если товара нет, то создаём его
                if (is_null($element_db)) {
                    // @todo: проверка на ошибки

                    $p = new Element();
                    $p->alias = $product['alias'];
                    $p->title = $product['title'];
                    $p->title_before = $product['title_before'];
                    $p->id_category = $id_category;
                    $p->id_manufacturer = $id_manufacturer;
                    $p->id_measurement = $id_measurement;
                    $p->desc_mini = $product['desc_mini'];
                    $p->published = 1;
                    $p->save();
                    $id_product = Yii::$app->db->getLastInsertID();
                    $messages['success'][] = 'Добавлен новый товар: ' . $product['title_before'] . ' ' . $product['manufacturer']['title'] . ' ' . $product['title'];
                }
            } else {
                if ($id_category != '') {
                    $messages['danger'][] = 'Не указана категория<br/>';
                } else if ($id_measurement != '') {
                    $messages['danger'][] = 'Не указано измерение<br/>';
                }
            }
        }
        else
        {
            Element::updateAll(
                [
                    'alias' => $product['alias'],
                    'title' => $product['title'],
                    'title_before' => $product['title_before'],
                    'desc_mini' => $product['desc_mini'],
                ],
                ['=', 'id', $id_element]
            );

            // Проверяем есть ли хоть одна характеристика и если есть, то удаляем все харрактеристики
            if (Element::find()->select('id')->from('tbl_catalog_field_element_value_rel')->where('id_element=:id_element', ['id_element' => $id_element])->exists()) {

                $query = "
                    DELETE cfv.*
                    FROM tbl_catalog_field_value cfv
                    LEFT JOIN tbl_catalog_field_element_value_rel cfev ON cfv.id = cfev.id_value
                    WHERE cfev.id_element = :id_element;

                    DELETE cfev.*
                    FROM tbl_catalog_field_element_value_rel cfev
                    WHERE cfev.id_element = :id_element;";

                Yii::$app->db->createCommand($query)->bindParam(':id_element',$id_element)->execute();
            }

            $id_product = $id_element;
        }

        // Для подсчёта сколько полей в товаре и сколько добавилос в бд
        $total_field_in_product = 0;

        $html = trim(pq($document)->find('.product-specs__table'));

        if ($html != '') {

            //Массив: группа - поле - значение - доп значение
            //Перебор групп свойств
            foreach (pq($html)->find('tbody') as $tbody) {

                $keyGroup = "";
                $keyField = "";

                //Соберём значения полей

                //Перебор свойств группы
                foreach (pq($tbody)->find('tr') as $tr) {
                    if (pq($tr)->hasClass('product-specs__table-title')) {
                        $keyGroup = trim(pq($tr)->text());
                    } else if (pq($tr)->hasClass('product-specs__table-spread')) {
                        // Добавить значение полю с заголовком "Описание"
                        $arr[$keyGroup]['Описание']['title'] = 'Описание';
                        $arr[$keyGroup]['Описание']['description'] = trim(pq($tr)->find("div.product-specs__table-small p")->text());
                    } else {
                        // Название поля
                        $keyField = trim(array_shift(explode('<div', trim(pq($tr)->find('td:eq(0)')->html()))));
                        $arr[$keyGroup][$keyField]['title'] = $keyField;

                        // Проверка, если поле типа время(time). Выделяется от остальных полей. Пример: 1 неделя, 3 суток
                        $is_time = false;
                        foreach ($fields_db as $b) {
                            if (trim($keyField) == trim($b['title']) && trim($keyGroup) == trim($b['group'])) {
                                if ($b['dop']) $dop = unserialize($b['dop']);
                                if ($b['type'] == '1' && $dop['check_var'] == 'time') {
                                    $is_time = true;
                                    $time1 = '';
                                    $time2 = '';
                                    $a = trim(pq($tr)->find('td:eq(1) span:eq(0)')->text());

                                    if (mb_strpos($a, '-', 0, 'utf-8')) {
                                        $aa = explode('-', $a);
                                        $a = $aa['0'];
                                        if ($a != '') {
                                            //Заполнение вторых полей
                                            $a = trim(str_replace("&nbsp;", " ", $a));
                                            $a = trim(str_replace(",", "", $a));
                                            $time1 = self::timeToMinut($a);
                                        }
                                        $a = $aa['1'];
                                        if ($a != '') {
                                            //Заполнение вторых полей
                                            $a = trim(str_replace("&nbsp;", " ", $a));
                                            $a = trim(str_replace(",", "", $a));
                                            $time2 = self::timeToMinut($a);
                                        }
                                        unset($aa);
                                    } else {
                                        //Заполнение первых полей
                                        $a = trim(str_replace("&nbsp;", " ", $a));
                                        $a = trim(str_replace(",", "", $a));
                                        $time1 = self::timeToMinut($a);
                                    }
                                    $arr[$keyGroup][$keyField]['val'][] = [$time1, $time2];
                                    unset($a);
                                    unset($time1);
                                    unset($time2);
                                }
                            }
                        }
                        // Иначе
                        if ($is_time === false) {
                            // X
                            // V
                            // V (текст), X (текст)
                            // V 1, V 100, V 1 номер, 100 номер
                            // V 1 (текст), V 100 (текст), V 1 номер (текст), 100 номер (текст)

                            // в <td></td>

                            // <span class="i-x"></span>
                            // <span class="i-tip"></span>
                            // <span class="i-tip"></span>&nbsp;(20 номеров)
                            // <span class="i-tip"></span><span class="value__text">1</span>
                            // <span class="i-tip"></span><span class="value__text">4&nbsp;линий</span>&nbsp;(SIP)

                            $a = trim(pq($tr)->find('td:eq(1)')->html());
                            $a = \phpQuery::newDocumentHTML('<div>' . $a . '</div>');

                            // X или V
                            if (pq($a)->find('span')->hasClass('i-x')) {
                                $arr[$keyGroup][$keyField]['dop']['check'] = 'no';
                            } else if (pq($a)->find('span')->hasClass('i-tip')) {
                                $arr[$keyGroup][$keyField]['dop']['check'] = 'yes';
                            }

                            //Одно или несколько значений
                            if (pq($a)->find('span.value__text')) {
                                $text = trim(pq($a)->find('span.value__text')->text());

                                // Если есть запятая, значит вариантов несколькою Иначе значение одно
                                if (mb_strpos($text, ',', 0, 'utf-8') !== false) {
                                    $vars = explode(', ', $text);
                                    foreach ($vars as $v) {
                                        $v = trim(self::strChangeSpecChar($v));
                                        if ($v != '') {
                                            //Если в массиве такого значения нет - добавить
                                            if (@!in_array($v, $arr[$keyGroup][$keyField]['val'])) {
                                                $arr[$keyGroup][$keyField]['val'][] = $v;
                                            }
                                        }
                                    }
                                    // Тут никак не может быть вариант: V 1
                                } else {
                                    if ($text != '') {
                                        $arr[$keyGroup][$keyField]['val'][] = trim(self::strChangeSpecChar($text));
                                    }
                                }
                            }

                            // Если есть текст ()
                            pq($a)->find('span')->remove();
                            $a = trim(pq($a)->text());
                            if ($a != '') {
                                $t = trim(self::strChangeSpecChar($a));
                                if ($t != '') $arr[$keyGroup][$keyField]['valText'] = $t;
                                unset($t);
                            }

                            //\phpQuery::unloadDocuments(\phpQuery::getDocumentID($a));
                            unset($a);
                        }
                    }
                    $keyField++;
                }

            }

            //Уберём из массива разделы
            $arrAll = [];
            foreach ($arr as $a_key => $a) {
                foreach ($a as $b) {
                    $b['group'] = $a_key;
                    $arrAll[] = $b;
                }
            }
            unset($b);
            unset($b);
            unset($arr);

            $insert_db_vals = []; // Собираем все значений для добавления в бд

            // Детальный разбор значений для добавки с бд
            foreach ($arrAll as $a) {
                foreach ($fields_db as $b) {
                    if (trim($a['title']) == trim($b['name']) && trim($a['group']) == trim($b['group'])) {
                        $total_field_in_product++;

                        // Узнаем id поля
                        $alias = $b['alias'];
                        $group = $a['group'];

                        $id_field = $b['id'];
                        /*
                        $id_field = Field::find()
                            ->select('cf.id as id')
                            ->from('tbl_catalog_field cf')
                            ->leftJoin('tbl_catalog_field_group cfg', 'cf.id_group = cfg.id')
                            ->where('cf.alias=:alias AND cfg.id_category=:id_category AND cfg.title=:group', [':alias' => $alias, ':id_category' => $id_category, ':group' => $group])
                            ->asArray()
                            ->one()['id'];
                        */

                        unset($value);
                        unset($text);

                        // VARIANTS
                        if ($b['variant']) {
                            $variants = unserialize($b['variant']);
                        }

                        // DOP
                        if ($b['dop']) {
                            $dop = unserialize($b['dop']);
                        }

                        // Текст
                        $text = '';
                        if (isset($a['valText'])) {
                            $text = $a['valText'];
                        }

                        if ($b['type'] == '1') {
                            $b['dop'] = unserialize($b['dop']);

                            if ($b['dop']['check_var'] == 'kb') {
                                $explode = explode(' ', $a['val']['0']);
                                $izm = array_pop($explode);
                                $value = implode('', $explode);
                                if ($izm == 'МБ') {
                                    $value = $value * 1024;
                                } else if ($izm == 'ГБ') {
                                    $value = $value * 1024 * 1024;
                                } else if ($izm == 'КБ') {
                                    $value = $value;
                                }
                                unset($explode);
                                $insert_db_vals[] = array('id_element' => $id_product, 'id_field' => $id_field, 'value' => $value, 'dop' => '', 'text' => $text);
                            } else if ($b['dop']['check_var'] == 'time') {
                                $insert_db_vals[] = array('id_element' => $id_product, 'id_field' => $id_field, 'value' => $a['val']['0']['0'], 'dop' => '1', 'text' => $text);
                                if (isset($a['val']['0']['1'])) {
                                    $insert_db_vals[] = array('id_element' => $id_product, 'id_field' => $id_field, 'value' => $a['val']['0']['1'], 'dop' => '2', 'text' => $text);
                                }
                            } else if ($b['dop']['check_var'] == 'mass') {
                                $a['val']['0'] = str_replace(chr(194) . chr(160), ' ', $a['val']['0']);
                                $explode = explode(' ', $a['val']['0']);

                                $izm = array_pop($explode);
                                $value = implode(' ', $explode);

                                if ($izm == 'т') { // проверка так ли тонны сокращены
                                    $value = $value * 1000000;
                                } else if ($izm == 'кг') {
                                    $value = $value * 1000;
                                } else if ($izm == 'г') {
                                    $value = $value;
                                }
                                unset($izm);

                                $insert_db_vals[] = array('id_element' => $id_product, 'id_field' => $id_field, 'value' => $value, 'dop' => '', 'text' => $text);
                            } else if ($dop['check_var'] == 'with_one') {

                                if ($a['dop']['check'] == 'no') {
                                    $insert_db_vals[] = array('id_element' => $id_product, 'id_field' => $id_field, 'value' => '0', 'dop' => '1', 'text' => $text);
                                }
                                if ($a['dop']['check'] == 'yes') {
                                    $insert_db_vals[] = array('id_element' => $id_product, 'id_field' => $id_field, 'value' => '1', 'dop' => '1', 'text' => $text);
                                }
                                if (count($a['val']) > 0) {
                                    foreach ($a['val'] as $aval) {
                                        if (strripos($a['val']['0'], $b['unit'], 0)) {
                                            $value = trim(substr($a['val']['0'], 0, strripos($a['val']['0'], $b['unit'], 0)));
                                            $insert_db_vals[] = array('id_element' => $id_product, 'id_field' => $id_field, 'value' => $value, 'dop' => '', 'text' => $text);
                                        }

                                    }
                                    unset($aval);
                                }

                            } else if (strripos($a['val']['0'], $b['unit'], 0)) {
                                // 2012 г. - при измерении г вернёт 2012 без точки на конце, да и вообще без конца с начала измерения
                                $value = trim(substr($a['val']['0'], 0, strripos($a['val']['0'], $b['unit'], 0)));
                                $insert_db_vals[] = array('id_element' => $id_product, 'id_field' => $id_field, 'value' => $value, 'dop' => '', 'text' => $text);

                            } else {
                                $insert_db_vals[] = array('id_element' => $id_product, 'id_field' => $id_field, 'value' => trim($a['val']['0']), 'dop' => '', 'text' => $text);
                            }

                        } else if ($b['type'] == '3') {

                            if ($a['dop']['check'] == 'no') {
                                $insert_db_vals[] = array('id_element' => $id_product, 'id_field' => $id_field, 'value' => '0', 'dop' => '1', 'text' => $text);
                                // Не обязательно быть yes чтобы были варианты
                            } else { // if ($a['dop']['check']=='yes')
                                if ($a['dop']['check'] == 'yes') {
                                    $insert_db_vals[] = array('id_element' => $id_product, 'id_field' => $id_field, 'value' => '1', 'dop' => '1', 'text' => $text);
                                }
                                if (isset($a['val']) && count($a['val']) > 0) {
                                    $count_vals = 0;

                                    foreach ($a['val'] as $var) {

                                        //copy (указывается в b, варианты указываются же в а как обычные варианты)
                                        if ($dop['copy'] != '' && array_key_exists('copy', $dop)) {
                                            $dop['copy'] = unserialize($dop['copy']);
                                            foreach ($dop['copy'] as $dop_v) {
                                                if ($var == $dop_v['vars']['0']) {
                                                    $insert_db_vals[] = array('id_element' => $id_product, 'id_field' => $id_field, 'value' => $dop_v['db_val'], 'dop' => '', 'text' => $text);
                                                    $count_vals++;
                                                }
                                            }
                                        }

                                        // izm_in_text (значение измерение. пример: 5 Мп)
                                        if ($dop['izm_in_text'] == 1 && array_key_exists('izm_in_text', $dop)) {
                                            $var = trim(substr($var, 0, strripos($var, $b['unit'], 0)));
                                            foreach ($variants as $var_v) {
                                                if ($var == $var_v['title']) {
                                                    $insert_db_vals[] = array('id_element' => $id_product, 'id_field' => $id_field, 'value' => $var_v['db_val'], 'dop' => '', 'text' => $text);
                                                    $count_vals++;
                                                }
                                            }
                                        }

                                        // Сами варианты
                                        foreach ($variants as $var_v) {
                                            if ($var == $var_v['title']) {
                                                $insert_db_vals[] = array('id_element' => $id_product, 'id_field' => $id_field, 'value' => $var_v['db_val'], 'dop' => '', 'text' => $text);
                                                $count_vals++;

                                            }
                                        }

                                    }

                                    //val_in_text (В (тексте) имеются значения)
                                    if ($dop['val_in_text'] != '' && array_key_exists('val_in_text', $dop)) {
                                        $dop['val_in_text'] = unserialize($dop['val_in_text']);


                                        foreach ($dop['val_in_text'] as $var_v) {
                                            if (mb_strpos($text, $var_v['vars']['0'], 0, 'utf-8')) {
                                                // Проверка на случай, если в тексте указывается значение, которое итак указано, но не в тексте
                                                $ins_i = 0;
                                                foreach ($insert_db_vals as $ins) {
                                                    if ($ins['id_element'] == $id_product && $ins['id_field'] == $id_field && $ins['value'] == $var_v['db_val'] && $ins['dop'] == '' && $ins['text'] == $text) {
                                                        $ins_i++;
                                                    }
                                                }
                                                if ($ins_i == 0) {
                                                    $insert_db_vals[] = array('id_element' => $id_product, 'id_field' => $id_field, 'value' => $var_v['db_val'], 'dop' => '', 'text' => $text);
                                                    $count_vals++;
                                                }
                                            }
                                        }
                                    }

                                    // Если вариант не найдер, показать элемент - поле - значение
                                    if (count($a['val']) > $count_vals) {
                                        $messages['danger'][] = 'id_element: ' . $id_product . ' - группа: ' . $a['group'] . ' - название: ' . $a['title'] . ' - ' . 'id_field: ' . $id_field . ' - Все значения: <br/>' .
                                            'Вариант не найден, показать элемент - поле - значение<br/>' .
                                            'serialize: ' . serialize($a['val']) . '<br/>';
                                    }
                                }
                            }
                        } else if ($b['type'] == '5') {
                            if ($a['title'] == 'Описание') {
                                $text = $a['description'];
                                $insert_db_vals[] = array('id_element' => $id_product, 'id_field' => $id_field, 'value' => '', 'dop' => '', 'text' => $text);
                            } else {
                                if (count($a['val']) > 0) {
                                    $text = implode(', ', $a['val']) . ' ' . $a['valText'];
                                }
                                $insert_db_vals[] = array('id_element' => $id_product, 'id_field' => $id_field, 'value' => '', 'dop' => '', 'text' => $text);
                            }
                        } else if ($b['type'] == '6') {
                            if (isset($a['val']['0']) && $a['val']['0'] != '') {
                                $explode = explode($dop['razdelitel'], $a['val']['0']);
                                $insert_db_vals[] = array('id_element' => $id_product, 'id_field' => $id_field, 'value' => trim($explode['0']), 'dop' => '1', 'text' => $text);
                                $insert_db_vals[] = array('id_element' => $id_product, 'id_field' => $id_field, 'value' => trim($explode['1']), 'dop' => '2', 'text' => $text);
                                unset($razdelitel);
                            } else if ($text != '') {
                                $insert_db_vals[] = array('id_element' => $id_product, 'id_field' => $id_field, 'value' => '', 'dop' => '', 'text' => $text);
                            }
                        }

                    }
                }
            }


            // Сколько разных полей разобрали
            $arr_fields_diff = [];
            foreach($insert_db_vals as $a) {
                if (!in_array($a['id_field'],$arr_fields_diff)) {
                    $arr_fields_diff[] = $a['id_field'];
                }
            }

            if ($count_fields != count($arr_fields_diff)) {
                $messages['warning'][] = 'Для данной категории сделано: ' . $count_fields . ' полей. Но в данном товаре добавилось только: ' . count($arr_fields_diff) . '. Необходимо дозаполнить поля.';
            }


            // Добавление значений полей в базу
            if (count($insert_db_vals) > 0) {
                foreach ($insert_db_vals as $arrInsertVal) {
                    if ($arrInsertVal['id_field'] > 0) {

                        $arrInsertVal['value'] = str_replace(' ', '', $arrInsertVal['value']); // 12 000 -> 12000
                        /*
                        $id_indb = FieldElementValue::find()
                            ->select('cfev.id as id')
                            ->from('tbl_catalog_field_element_value_rel cfev')
                            ->leftJoin('tbl_catalog_field_value cv', 'cfev.id_value = cv.id')
                            ->where(
                                'cfev.id_field=:id_field AND cfev.id_element=:id_element AND cv.value=:value AND cv.dop=:dop AND cv.text=:text',
                                [
                                    ':id_field' => $arrInsertVal['id_field'],
                                    ':id_element' => $arrInsertVal['id_element'],
                                    ':value' => $arrInsertVal['value'],
                                    ':dop' => $arrInsertVal['dop'],
                                    ':text' => $arrInsertVal['text']
                                ]
                            )
                            ->asArray()
                            ->one();

                        echo '<pre>';
                        var_dump($id_indb);
                        echo '</pre>';

                        if ($id_indb['id'] == '') {
                        */
                        // Добавляем раз
                        $fv = new FieldValue();
                        $fv->value = (float)$arrInsertVal['value'];
                        $fv->dop = $arrInsertVal['dop'];
                        $fv->text = $arrInsertVal['text'];
                        if ($fv->save()) {
                            $last_id = (int)Yii::$app->db->getLastInsertID();
                            // Добавляем два
                            $fv = new FieldElementValue();
                            $fv->id_element = $arrInsertVal['id_element'];
                            $fv->id_field = $arrInsertVal['id_field'];
                            $fv->id_value = $last_id;
                            $fv->save();
                        }

                        /* } else {
                            $messages['danger'][] = 'ERROR';
                        }
                        */
                    }
                }
            }
            else
            {
                $messages['success'][] = 'Нечего добавлять. Скорее всего не созданы поля для категории.';
            }

            $total_field_add_in_db = FieldElementValue::find()->select('COUNT(DISTINCT(id_field)) as count')->where('id_element=:id_element', ['id_element' => $id_product])->asArray()->one();
            $total_field_add_in_db = $total_field_add_in_db['count'];
            if ($total_field_in_product != $total_field_add_in_db) {
                $messages['danger'][] = 'Не совпадение в подсчётах полей. В спарсенном товаре полей: ' . $total_field_in_product . '. В бд добавилось: ' . $total_field_add_in_db;
            }
        }

        \phpQuery::unloadDocuments();

        return json_encode($messages);
    }


    public static function tool6($alias) {
        // Получить facets и удалить placeholders
        $json = self::getSslPage('https://catalog.api.onliner.by/facets/'.$alias);
        $obj = json_decode($json);
        $obj->placeholders = '';
        $json = json_encode($obj);

        // Сохранить в категорию
        \Yii::$app->db->createCommand("UPDATE {{%catalog_category}} SET facets=:facets WHERE alias=:alias")
            ->bindValue(':facets', $json)
            ->bindValue(':alias', $alias)
            ->execute();

        // Посмотреть новые данные facets
        $mes[] = json_decode(Category::find()->select('facets')->where('alias=:alias',[':alias'=>$alias])->one()['facets']);

        return $mes;
    }


    public static function tool7GetFields() {
        $facets = Category::find()
            ->select('facets')
            ->where('id = :id', [':id' => Yii::$app->request->post('id_category')])
            ->asArray()
            ->one()['facets'];
        $facets = json_decode($facets);

        $fields = [];
        $items = $facets->facets->general->items;
        $itemsHide = $facets->facets->additional->items;
        $dictionaries = $facets->dictionaries;

        $i = 1;
        foreach ($items as $item) {
            if ($item->type == 'dictionary')
            {
                $values = [];
                if (isset($dictionaries->{$item->parameter_id})) {
                    foreach($dictionaries->{$item->parameter_id} as $val) {
                        $values[] = $val->name . '** ' . $val->id;
                    }
                }

                $fields[] = [
                    'type' => 'dictionary',
                    'title' => $item->name,
                    'alias' => $item->parameter_id,
                    'desc' => $item->description,
                    'hide' => 0,
                    'sort' => $i,
                    'vars' => (count($values)) ? implode('** ', $values) : '',
                    'varsTop' => (count($item->popular_dictionary_values)) ? implode('** ', $item->popular_dictionary_values) : '',
                ];
            }
            else if ($item->type == 'number_range')
            {
                $fields[] = [
                    'type' => 'number_range',
                    'title' => $item->name,
                    'alias' => $item->parameter_id,
                    'desc' => $item->description,
                    'hide' => 0,
                    'sort' => $i,
                    'unit' => $item->unit,
                    'ratio' => $item->ratio,
                ];
            }
            else if ($item->type == 'boolean')
            {
                $fields[] = [
                    'type' => 'boolean_checkbox',
                    'title' => $item->name,
                    'alias' => $item->parameter_id,
                    'desc' => $item->description,
                    'hide' => 0,
                    'sort' => $i,
                ];
            }
            else
            {
                //throw new ErrorException('Тип поля не поддержвается: '.$item->type);
            }
            $i++;
        }

        foreach ($itemsHide as $item) {
            if ($item->type == 'dictionary')
            {
                $values = [];
                if (isset($dictionaries->{$item->parameter_id})) {
                    foreach($dictionaries->{$item->parameter_id} as $val) {
                        $values[] = $val->name . '** ' . $val->id;
                    }
                }
                $fields[] = [
                    'type' => 'dictionary',
                    'title' => $item->name,
                    'alias' => $item->parameter_id,
                    'desc' => $item->description,
                    'hide' => 1,
                    'sort' => $i,
                    'vars' => (count($values)) ? implode('** ', $values) : '',
                    'varsTop' => (count($item->popular_dictionary_values)) ? implode('** ', $item->popular_dictionary_values) : '',
                ];
            }
            else if ($item->type == 'number_range')
            {
                $fields[] = [
                    'type' => 'number_range',
                    'title' => $item->name,
                    'alias' => $item->parameter_id,
                    'desc' => $item->description,
                    'hide' => 1,
                    'sort' => $i,
                    'unit' => $item->unit,
                    'ratio' => $item->ratio,
                ];
            }
            else if ($item->type == 'boolean')
            {
                $fields[] = [
                    'type' => 'boolean_checkbox',
                    'title' => $item->name,
                    'alias' => $item->parameter_id,
                    'desc' => $item->description,
                    'hide' => 1,
                    'sort' => $i,
                ];
            }
            else
            {
                //throw new ErrorException('Тип поля не поддержвается: '.$item->type);
            }
            $i++;
        }

        return $fields;
    }

    public static function tool7GetManufacturers() {
        $mnfs = (new Query())
            ->select('distinct(m.id), m.title, m.alias')
            ->from('{{%catalog_category}} c')
            ->leftJoin('{{%catalog_element}} e', 'c.id = e.id_category')
            ->leftJoin('{{%catalog_manufacturer}} m', 'e.id_manufacturer = m.id')
            ->where('c.id = :id', [':id' => Yii::$app->request->post('id_category')])
            ->andWhere('e.published = 1')
            ->andWhere('e.in_stock > 0')
            ->all();
        $mnfsLine = [];
        foreach($mnfs as $mnf) {
            $mnfsLine[] = $mnf['title'] . '** ' . $mnf['alias'];
        }
        return implode('** ', $mnfsLine);
    }

    public static function tool7SetFields() {
        $id_category = Yii::$app->request->post('id_category');
        $fields = json_decode(Yii::$app->request->post('data'), true);

        //$fields = json_decode('[{"type":"dictionary","title":"Производитель","alias":"mfr","desc":"","hide":0,"sort":"1","vars":"1Марка, 1marka, Aessel, aessel, Alpen, alpen, Antika, antika, Appollo, appollo, Aquaform, aquaform, Aquanet, aquanet, Aquatek, aquatek, Aquator, aquator, Aquavita, aquavita, Artel Plast, artel_plast, Avanta, avanta, Bach, bach, Balteco, balteco, BAS, bas, BelBagno, belbagno, Belezzo, belezzo, Belux, belux, Bindu, bindu, BLB, blb, Cersanit, cersanit, Colombo, colombo, Composit Group, composit_group, Doctor Jet, dj, Donna Vanna, donnavanna, Duravit, duravit, Dusar, dusar, Eago, eago, Emalia, emalia, Esse, esse, Estap, estap, Eurolux, eurolux, Eurowa, eurowa, Excellent, excellent, Fituche, fituche, Flamenco, flamenco, Gemy, gemy, Gilax, gilaxspa, Goldman, goldman, H2O, h2o, Ifo, ifo, Jacob Delafon, jacobdelafon, Jacuzzi, jacuzzi, Kaldewei, kaldewei, Keramag, keramag, Kohler, kohler, Kolo, kolo, Kolpa-San, kolpasan, Laufen, laufen, Leroy, leroy, Marathon, marathon, Mario, mario, Marmite, marmite, Nimfa, nimfa, Novitek, novitek, Paa, paa, Poolspa, poolspa, Potter, potter, Que Calor, que_color, Ravak, ravak, Recor, recor, Reimar, reimar, Relisan, relisan, Riho, riho, Roca, roca, Roltechnik, roltechnik, Ruben, ruben, RUS AQUA, rus_aqua, Sanart Plus, sanart, Sanplast, sanplast, Santek, santek, Smavit, smavit, SPN, spn, Teuco, teuco, Triton, triton, VagnerPlast, vagnerplast, Vayer, vayer, Ventospa, ventospa, Victoria + Albert, victoriaalbert, Villeroy & Boch, villeroyboch, Vispool, vispool, Vitra, vitra, Walter, walter, White Wave, white_wave, Кировский завод, kzavod, Универсал, universal_russia","varsTop":"roca, universal_russia, cersanit, triton, kaldewei","unit":"","ratio":"0"},{"type":"number_range","title":"Цена (минимальная)","alias":"price","desc":"","hide":0,"sort":"2","vars":"","varsTop":"","unit":"","ratio":""},{"type":"dictionary","title":"Материал","alias":"bath_material","desc":"","hide":0,"sort":"3","vars":"акрил, aryl, чугун, castiron, сталь, steel, искусственный камень, stone, композит, stekloplastik, кварил, quaryl, искусственный мрамор, artmarble, акрил, покрытый гелькоутом, gelcoatedacryl","varsTop":"","unit":"","ratio":"0"},{"type":"dictionary","title":"Форма","alias":"bath_form","desc":"","hide":0,"sort":"4","vars":"прямая, rectangle, угловая, angle, круглая, round, овальная, oval, многогранная, polyhedral, каплевидная, teardrop, нестандартная, nonstandard","varsTop":"","unit":"","ratio":"0"},{"type":"number_range","title":"Количество мест","alias":"bath_places","desc":"В основном ванны и душевые кабины рассчитаны на одного человека, но есть и двухместные модели.","hide":0,"sort":"5","vars":"","varsTop":"","unit":"","ratio":"1"},{"type":"number_range","title":"Полезный объём","alias":"bath_volume","desc":"Полезный объём важно знать, чтобы оценить внутреннее пространство ванны. В мелкой ванне крупному человеку будет неудобно.","hide":0,"sort":"6","vars":"","varsTop":"","unit":"л","ratio":"1"},{"type":"boolean_checkbox","title":"Массажная система","alias":"bath_massage","desc":"Некоторые ванны штатно оснащаются функцией массажа. Это может быть только гидромассаж или гидромассаж с аэромассажем.","hide":0,"sort":"7","vars":"","varsTop":"","unit":"","ratio":"0"},{"type":"number_range","title":"Длина","alias":"bath_length","desc":"Габаритная длина ванны должна согласовываться с установочными размерами в ванной комнаты.","hide":0,"sort":"8","vars":"","varsTop":"","unit":"см","ratio":"1"},{"type":"number_range","title":"Ширина","alias":"bath_width","desc":"Габаритная ширина ванны должна согласовываться с установочными размерами в ванной комнаты.","hide":0,"sort":"9","vars":"","varsTop":"","unit":"см","ratio":"1"},{"type":"number_range","title":"Высота","alias":"bath_height","desc":"Габаритная высота ванны не учитывает высоту ножек и крепежа.","hide":0,"sort":"10","vars":"","varsTop":"","unit":"см","ratio":"1"},{"type":"number_range","title":"Вес","alias":"bath_weight","desc":"Вес ванны. Чем больше вес, тем более устойчивой она будет, но тем сложнее ее подъем и монтаж.","hide":0,"sort":"11","vars":"","varsTop":"","unit":"кг","ratio":"1"},{"type":"dictionary","title":"Страна производства","alias":"sanit_origin","desc":"","hide":0,"sort":"12","vars":"Беларусь, belarus, Польша, poland, Латвия, latvia, Чехия, czech, Испания, spain, Италия, italy, Великобритания, uk, Германия, germany, Китай, china, Эстония, estonia, Россия, russia, Франция, france, Швеция, sweden, Швейцария, switzerland, Португалия, portugal, Словения, slovenia, Словакия, slovak, Казахстан, kazakhstan, Украина, ukraine, Финляндия, finland, Сербия, serbia","varsTop":"","unit":"","ratio":"0"},{"type":"number_range","title":"Толщина стенок","alias":"bath_thickness","desc":"Чем толще стенки ванны, тем более прочной и энергоемкой она будет.","hide":1,"sort":"13","vars":"","varsTop":"","unit":"мм","ratio":"1"},{"type":"boolean_checkbox","title":"Смеситель","alias":"san_mixer","desc":"Встроенный смеситель обеспечивает регулирование интенсивности и температуры подачи воды в душевые и гидромассажные форсунки. Термостатический смеситель способен самостоятельно поддерживать постоянную температуру воды.","hide":1,"sort":"14","vars":"","varsTop":"","unit":"","ratio":"0"},{"type":"boolean_checkbox","title":"Ручной душ (душевая лейка)","alias":"hand_shower","desc":"Классическая ручная лейка позволяет направлять душевую струю в любое место.","hide":1,"sort":"15","vars":"","varsTop":"","unit":"","ratio":"0"},{"type":"boolean_checkbox","title":"Подсветка","alias":"shower_illum","desc":"Декоративная подсветка различных элементов часто реализуется в многофункциональных душевых боксах и ваннах.","hide":1,"sort":"16","vars":"","varsTop":"","unit":"","ratio":"0"},{"type":"boolean_checkbox","title":"Радио","alias":"radio","desc":"Встроенный FM-приёмник позволяет проигрывать радиопередачи.","hide":1,"sort":"17","vars":"","varsTop":"","unit":"","ratio":"0"},{"type":"dictionary","title":"Управление","alias":"msg_control","desc":"","hide":1,"sort":"18","vars":"пневмокнопка, button, электронное управление, electronic","varsTop":"","unit":"","ratio":"0"},{"type":"number_range","title":"Мощность помпы","alias":"pump_power","desc":"Помпа, нагнетающая воду, может иметь различную мощность. Грубо говоря, чем мощнее помпа, тем выше будет сила гидромассажа.","hide":1,"sort":"19","vars":"","varsTop":"","unit":"кВт","ratio":"1000"},{"type":"boolean_checkbox","title":"Массаж поясничного отдела","alias":"msg_lumbar","desc":"Форсунки могут располагаться в области поясницы, обеспечивая массаж поясничного отдела тела.","hide":1,"sort":"20","vars":"","varsTop":"","unit":"","ratio":"0"},{"type":"boolean_checkbox","title":"Массаж стоп","alias":"msg_feet","desc":"Форсунки могут располагаться в области стоп, обеспечивая их массаж.","hide":1,"sort":"21","vars":"","varsTop":"","unit":"","ratio":"0"},{"type":"boolean_checkbox","title":"Аэромассаж","alias":"aeromassage","desc":"Аэромассаж дополняет гидромассаж: компрессор подает воздух в форсунки, и, помимо воды, в массаже участвуют пузырьки воздуха.","hide":1,"sort":"22","vars":"","varsTop":"","unit":"","ratio":"0"},{"type":"boolean_checkbox","title":"Подогрев воздуха","alias":"msg_airheating","desc":"Аэромассаж выполняется подогретым воздухом.","hide":1,"sort":"23","vars":"","varsTop":"","unit":"","ratio":"0"},{"type":"boolean_checkbox","title":"Таймер","alias":"msg_timer","desc":"Управление гидромассажем может предусматривать установку таймера, отключающего массаж автоматически.","hide":1,"sort":"24","vars":"","varsTop":"","unit":"","ratio":"0"},{"type":"boolean_checkbox","title":"Датчик уровня воды","alias":"msg_waterlevel","desc":"Датчик уровня воды блокирует включение гидромассажа при недостаточном уровне воды в ванне или душевом поддоне.","hide":1,"sort":"25","vars":"","varsTop":"","unit":"","ratio":"0"}]', true);

        // Отсортировать
        array_multisort(array_column($fields, 'sort'), SORT_ASC, $fields);

        //Отделить доп поля
        $fieldsMain = [];
        $fieldsMore = [];

        foreach($fields as $field) {
            if ($field['hide']) {
                $fieldsMore[] = $field;
            } else {
                $fieldsMain[] = $field;
            }
        }

        $obj = (object)[
            'facets' => (object)[
                'general' => (object)[
                    'items' => []
                ],
                'additional' => (object)[
                    'items' => []
                ]
            ],
            'placeholders' => [],
            'dictionaries' => []
        ];

        $obj = self::tool7GetItem($obj,$fieldsMain,1);
        $obj = self::tool7GetItem($obj,$fieldsMore,0);

        Category::updateAll(['facets'=> json_encode($obj)], 'id = :id', [':id' => $id_category]);


    }

    public static function tool7GetItem($obj, $fields, $type) {

        foreach($fields as $field) {
            $item = [];

            // Формируем элемент
            if($field['type'] == 'dictionary') {
                if ($field['varsTop'] != '') {

                    $item = (object)[
                        'type' => 'dictionary',
                        'parameter_id' => $field['alias'],
                        'dictionary_id' => $field['alias'],
                        'name' => $field['title'],
                        'description' => Html::decode($field['desc']),
                        'operation' => 'union',
                        'max_count' => '10',
                        'popular_dictionary_values' => explode('** ', $field['varsTop']),
                    ];
                } else {
                    $item = (object)[
                        'type' => 'dictionary',
                        'parameter_id' => $field['alias'],
                        'dictionary_id' => $field['alias'],
                        'name' => $field['title'],
                        'description' => Html::decode($field['desc']),
                        'operation' => 'union',
                        'max_count' => '10',
                        'popular_dictionary_values' => [],
                    ];
                }

                $dict = [];
                $dictionaries = explode('** ', $field['vars']);
                $dictionaries = array_chunk($dictionaries, 2);

                foreach($dictionaries as $dictionary) {
                    $dict[] = (object)[
                        'id' => $dictionary[1],
                        'name' => $dictionary[0],
                    ];
                }
                $obj->dictionaries[$field['alias']] = $dict;
            } else if($field['type'] == 'number_range') {
                if (!is_null($field['ratio']) && $field['alias'] != 'price') {
                    $item = (object)[
                        'type' => 'number_range',
                        'parameter_id' => $field['alias'],
                        'name' => $field['title'],
                        'description' => Html::decode($field['desc']),
                        'unit' => $field['unit'],
                        'ratio' => ($field['ratio'] != '') ? $field['ratio'] : 1,
                    ];
                } else {
                    $item = (object)[
                        'type' => 'number_range',
                        'parameter_id' => $field['alias'],
                        'name' => $field['title'],
                        'description' => Html::decode($field['desc']),
                        'unit' => $field['unit'],
                    ];
                }
            } else if($field['type'] == 'boolean_checkbox') {
                $item = (object)[
                    'type' => 'boolean',
                    'parameter_id' => $field['alias'],
                    'name' => $field['title'],
                    'description' => Html::decode($field['desc']),
                    'bool_type' => 'checkbox',
                ];
            } else {

            }
            // Добавляем в объект
            if ($type) {
                $obj->facets->general->items[] = $item;
            } else {
                $obj->facets->additional->items[] = $item;
            }
        }

        return $obj;
    }

}





