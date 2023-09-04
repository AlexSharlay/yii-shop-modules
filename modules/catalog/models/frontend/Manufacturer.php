<?php

namespace common\modules\catalog\models\frontend;

use Yii;
use yii\db\Query;
use yii\web\HttpException;

/**
 * Class Manufacturer
 * @package common\modules\catalog\models\frontend
 */
class Manufacturer extends \common\modules\catalog\models\Manufacturer
{

    /**
     * @param $alias
     * @return bool
     * @throws HttpException
     */
    public static function issetManufacturer($alias)
    {
        $manufacturer = Manufacturer::find()->select('id')->where('alias=:alias', [':alias' => $alias])->limit(1)->asArray()->one();
        if ($manufacturer !== null) {
            return true;
        } else {
            throw new HttpException(404, 'Производитель не найден.');
        }
    }

    /**
     * @param string $manufacturer
     * @return array
     */
    public static function getCategories($manufacturer = '')
    {
        $manufacturers = [];
        $manufacturers = $manufacturer ? (new Query())
            ->select('c.title, c.alias, c.ico')
            ->from('{{%catalog_manufacturer}} m')
            ->leftJoin('{{%catalog_element}} e', 'm.id = e.id_manufacturer')
            ->leftJoin('{{%catalog_category}} c', 'e.id_category = c.id')
            ->where('m.alias = :alias', [':alias' => $manufacturer])
            ->andWhere('c.id IS NOT NULL')
            //->andWhere('m.published = 1')
            ->andWhere('e.in_stock > 0')
            ->andWhere('e.price > 0')
            ->andWhere('e.published = 1')
            ->andWhere('c.published = 1')
            ->groupBy('c.id')
            ->orderBy('c.title')
            ->all() : (new Query())
            ->select('c.title, c.alias, c.ico')
            ->from('{{%catalog_manufacturer}} m')
            ->leftJoin('{{%catalog_element}} e', 'm.id = e.id_manufacturer')
            ->leftJoin('{{%catalog_category}} c', 'e.id_category = c.id')
            ->where('c.id IS NOT NULL')
            //->andWhere('m.published = 1')
            ->andWhere('e.in_stock > 0')
            ->andWhere('e.price > 0')
            ->andWhere('e.published = 1')
            ->andWhere('c.published = 1')
            ->groupBy('c.id')
            ->orderBy('c.title')
            ->all();


        foreach ($manufacturers as $key => $manufacturer) {
            $category_parent = '';
            $cat = $manufacturers[$key]['alias'];
            do {
                $sql = "
            SELECT `c1`.`alias`, `c1`.`id_parent`
            FROM `tbl_catalog_category` `c1`
            LEFT JOIN `tbl_catalog_category` `c2` ON `c1`.`id` = `c2`.`id_parent` 
            WHERE c2.alias ='" . $cat . "'";

                $command = \Yii::$app->db->createCommand($sql);
                $cat_parent = $command->queryAll();

                if (isset($cat_parent[0]['alias'])) {
                    $category_parent = $cat_parent[0]['alias'] . '/' . $category_parent;
                    $cat = $cat_parent[0]['alias'];
                }
            } while ($cat_parent[0]['id_parent'] > 0);

            $manufacturers[$key]['alias'] = '/' . $category_parent . $manufacturers[$key]['alias'];
        }


        foreach ($manufacturers as $key => $manufacturer) {
            if (mb_strpos($manufacturer['alias'], '_collection')) {
                unset($manufacturers[$key]);
            }
        }

        return $manufacturers;
    }

    /**
     * @return array
     */
    public static function getManufacturers()
    {
        return (new Query())
            ->select('m.title, m.alias, m.ico')
            ->from('{{%catalog_manufacturer}} m')
            ->leftJoin('{{%catalog_element}} e', 'e.id_manufacturer = m.id')
            ->leftJoin('{{%catalog_category}} c', 'c.id = e.id_category')
            ->groupBy('m.id')
            ->where('e.id IS NOT NULL')
            ->andWhere('e.in_stock > 0')
            ->andWhere('e.price > 0')
            ->andWhere('e.published = 1')
            ->andWhere('c.published = 1')
            ->andWhere('m.alias <> "h2o"')
            //->andWhere('m.published = 1')
            //->andWhere('m.ico IS NOT NULL')->andWhere('m.ico <> ""')
            ->orderBy('m.title ASC')
            ->all();
    }

    /**
     * @param $manufacturer
     * @return array
     */
    public static function getManufacturersPage($manufacturer)
    {
        return (new Query())
            ->select('m.title, m.ico, m.desc, m.seo_title, m.seo_keyword, m.seo_desc, m.alias, c.title as country_title, c.ico as country_ico')
            ->from('{{%catalog_country}} c')
            ->leftJoin('{{%catalog_manufacturer_country}} mc', 'mc.id_country = c.id')
            ->leftJoin('{{%catalog_manufacturer}} m', 'm.id = mc.id_manufacturer')
            ->where('m.alias = :alias', [':alias' => $manufacturer])
            ->all();
    }

}
