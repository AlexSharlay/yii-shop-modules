<?php

namespace common\modules\shop\components;

use Yii;

class Helper
{

    public static function jsonHeader() {
        // Заголовки рас
        header ("Cache-Control: no-cache");
        header ("Keep-Alive: timeout=15");
        header ("Content-type: application/json; charset=utf-8");
        // Заголовки два
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Cache-Control','no-cache');
        $headers->add('Keep-Alive','timeout=15');
        $headers->add('Content-type','application/json; charset=utf-8');
    }

    public static function time_autoformat(/*секунд*/ $ts, $full = false){

        if($full){
            $d = ''; $t = $ts;
            $d1 = (floor($t/31622400)>0) ? floor($t/31622400) : '';
            $d2 = (floor($t/2635200)>0) ? floor($t/2635200) % 12 : '';
            $d3 = (floor($t/86400)>0) ? floor($t/86400) % 30 : '';
            $d4 = (floor($t/3600)>0) ? floor($t/3600) % 24 : '';
            $d5 = floor($t/60) % 60;
            if($d1) $d .= self::sklonen($d1,'год ','года ','лет ');
            if($d2) $d .= self::sklonen($d2,'месяц ','месяца ','месяцев ');
            if($d3) $d .= self::sklonen($d3,'день ','дня ','дней ');
            if($d4) $d .= self::sklonen($d4,'час ','часа ','часов ');
            if($d5) $d .= self::sklonen($d5,'минута  ','минуты ','минут ');
            return $d;
        } else {
            $sec = $ts;
            $min = round($ts/60);
            $hour = round($ts/3600);
            $days = round($ts/86400);
            $month = round($ts/2635200);
            $years = round($ts/31622400);
            if($sec<60) return $sec.' сек.';
            if($min<60) return $min.' мин.';
            if($hour<24) return self::sklonen($hour,'час','часа','часов',false);
            if($days<31) return self::sklonen($days,'день','дня','дней',false);
            if($month<12) return self::sklonen($month,'месяц','месяца','месяцев',false);
            else return self::sklonen($years,'год','года','лет',false);
        }
    }

    public static function sklonen($n,$s1,$s2,$s3, $b = false){
        $m = $n % 10; $j = $n % 100;
        if($m==1) $s = $s1;
        if($m>=2 && $m<=4) $s = $s2;
        if($m==0 || $m>=5 || ($j>=10 && $j<=20)) $s = $s3;
        if($b) $n = '<b>'.$n.'</b>';
        return $n.' '.$s;
    }


}





