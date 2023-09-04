<?php
namespace common\modules\catalog\components\statistics;

use common\modules\catalog\models\Element;
use common\modules\catalog\models\StatisticFilled;

class Filled
{

    public function get($date_from,$date_to)
    {
        $items = StatisticFilled::find()->where('date >= :date_from AND date <= :date_to',[':date_from'=>$date_from,':date_to'=>$date_to])->orderBy('date')->asArray()->all();

        $points = [];
        foreach($items as $item) {
            $points[] = [
                $item['date'],
                $item['all'],
                $item['with_photo'],
                $item['fill_mini'],
                $item['fill_full'],
            ];
        }
        $result = '';
        foreach($points as $point) {
            $result .= "['".array_shift($point)."',".implode(',',$point)."],";
        }


        return $result;
    }

    public function add()
    {
        $all = Element::find()->select()->count();

        $with_photo = \Yii::$app->db->createCommand('
            SELECT COUNT(*) as count FROM (
                SELECT id
                FROM tbl_catalog_photo p
                GROUP BY p.id_element
            ) as a;')->queryOne()['count'];

        $a = \Yii::$app->db->createCommand('
            # Сколько в категории полей, id категории
            # Возвращает только заполненные категории
            SELECT c.id as id_category, COUNT(*) as count
            FROM tbl_catalog_category c
            LEFT JOIN tbl_catalog_field_group fg ON c.id = fg.id_category
            LEFT JOIN tbl_catalog_field f ON fg.id = f.id_group
            WHERE f.id IS NOT NULL
            GROUP BY c.id;')->queryAll();

        $b = \Yii::$app->db->createCommand('
            # id товара, id категории, сколько полей заполнено
            # Возвращает товары хотя бы с 1 заполненным полем
            SELECT e.id as id_product, id_category, COUNT(fev.id) AS count
            FROM tbl_catalog_element e
            LEFT JOIN tbl_catalog_field_element_value_rel fev ON e.id = fev.id_element
            WHERE fev.id IS NOT NULL
            GROUP BY e.id;
            ')->queryAll();

        $fill_mini = 0;
        $fill_full = 0;
        foreach($a as $aa) {
            foreach($b as $bb) {
                if ($aa['id_category'] == $bb['id_category']) {
                    $fill_mini++;
                    if ($aa['count'] == $bb['count']) {
                        $fill_full++;
                    }
                }
            }
        }

        $model = new StatisticFilled();
        $model->date = date('Y-m-d');
        $model->all = $all;
        $model->with_photo = $with_photo;
        $model->fill_mini = $fill_mini;
        $model->fill_full = $fill_full;
        $model->save();

    }

}