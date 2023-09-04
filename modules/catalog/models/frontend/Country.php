<?php

namespace common\modules\catalog\models\frontend;

use Yii;
use yii\db\Query;

class Country extends  \common\modules\catalog\models\Country
{

    public function getCountriesPage()
    {
        $vars = (new Query())
            ->select('DISTINCT(m.id), c.title as title_country, c.ico as ico_country, m.title, m.alias, m.ico')
            ->from('{{%catalog_country}} c')
            ->leftJoin('{{%catalog_manufacturer_country}} mc', 'mc.id_country = c.id')
            ->leftJoin('{{%catalog_manufacturer}} m', 'm.id = mc.id_manufacturer')
            ->leftJoin('{{%catalog_element}} e', 'e.id_manufacturer = m.id')
            ->where('e.published = 1')
            ->andWhere('e.id IS NOT NULL')
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
        return [
            'countries' => $countries,
            'flats' => $flats,
        ];
    }

}
