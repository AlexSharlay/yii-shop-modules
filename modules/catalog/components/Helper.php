<?php

namespace common\modules\catalog\components;

class Helper
{

    public static function formatPrice($price) {
        $price = str_replace(' ', '', trim($price));
        $price = str_replace(',', '', trim($price));
        $price = str_replace('.', '', trim($price));
        $price = str_split($price);
        $kop = array_pop($price);
        $kop = array_pop($price).$kop;
        $rub = (int)implode('',$price);
        $rub = number_format($rub, 0, '.', ' ');
        return $rub.' р. '.$kop.' к.';
    }


    public static function formatPriceMin($price) {
        if ($price > 0) {
            $price = str_replace(' ', '', trim($price));
            $price = str_replace(',', '', trim($price));
            $price = str_replace('.', '', trim($price));
            $price = str_split($price);
            $kop = array_pop($price);
            $kop = array_pop($price).$kop;
            $rub = (int)implode('',$price);
            $rub = number_format($rub, 0, '.', ' ');
            return $rub.'.'.$kop;
        } else {
            return false;
        }
    }

}





