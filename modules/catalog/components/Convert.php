<?php

namespace common\modules\catalog\components;

class Convert
{

    public static function objectToArray($object) {

        if(!is_object($object) && !is_array($object)) {
            return $object;
        }
        if(is_object($object)) {
            $object = get_object_vars($object);
        }
        return array_map([self,'objectToArray'], $object);
    }

}





