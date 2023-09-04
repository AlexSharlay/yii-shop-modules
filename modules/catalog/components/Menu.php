<?php

namespace common\modules\catalog\components;

use Yii;
use yii\db\Query;

class Menu
{

    public static function getCountriesMenu($id)
    {

        // todo: по id собрать все дочерние категории
        //$ids = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25];

        $ids = (new Query())
            ->select('c2.id as id1, c3.id as id2')
            ->from('{{%catalog_category}} c1')
            ->leftJoin('{{%catalog_category}} c2', 'c1.id = c2.id_parent')
            ->leftJoin('{{%catalog_category}} c3', 'c2.id = c3.id_parent')
            ->where('c1.id = :id', [':id' => $id])
            //->createCommand()->sql;
            ->all();

        $ids = array_unique(array_filter(array_merge(array_column($ids, 'id1'), array_column($ids, 'id2'))));
        $ids[] = $id;

        $vars = (new Query())
            ->select('DISTINCT(m.id), c.title as title_country, c.ico as ico_country, m.title, m.alias, m.ico')
            ->from('{{%catalog_country}} c')
            ->leftJoin('{{%catalog_manufacturer_country}} mc', 'mc.id_country = c.id')
            ->leftJoin('{{%catalog_manufacturer}} m', 'm.id = mc.id_manufacturer')
            ->leftJoin('{{%catalog_element}} e', 'e.id_manufacturer = m.id')
            ->leftJoin('{{%catalog_category}} ca', 'ca.id = e.id_category')
            ->where('e.published = 1')
            ->andWhere('ca.published = 1')
            ->andWhere('e.id IS NOT NULL')
            ->andWhere('ca.id IN ('.implode(',',$ids).')')
            ->orderBy('c.title')
            ->all();
        //->createCommand()->sql;

        $countries = array_flip(array_column($vars, 'title_country'));
        foreach($countries as $key => $country) {
            $countries[$key] = [];
        }

        $flats = [];
        foreach($vars as $var) {
            if (!in_array($var['title_country'], $flats)) {
                $flats[$var['title_country']] = $var['ico_country'];
            }
            $countries[$var['title_country']][] = [
                'title' => $var['title'],
                'alias' => $var['alias'],
                'ico' => $var['ico'],
            ];
        }

        uasort($countries, function($arr1,$arr2) {
            if(count($arr1) > count($arr2)) return -1;
            elseif(count($arr1) < count($arr2)) return 1;
            else return 0;
        });

        return [
            'countries' => $countries,
            'flats' => $flats,
        ];
    }

}