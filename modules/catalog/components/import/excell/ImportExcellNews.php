<?php
/**
 * Created by PhpStorm.
 * User: vitalbu
 * Date: 17.08.2017
 * Time: 23:01
 */

namespace common\modules\catalog\components\import\excell;

use Yii;
use common\modules\blogs\models\Blog;

class ImportExcellNews
{

    public function run($file)
    {
        // Данные xlsx файла
        $objPHPExcel = \PHPExcel_IOFactory::load($file);
        $rows = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
        unset($rows[1]);
        foreach ($rows as $key => $row) {
            self::newsCreate($row);
        }
    }

    // Добавить строку в бд
    public static function newsCreate($row)
    {
        $p = new Blog();
        $p->id = $row['A'];
        $p->category_id = 7;
        $p->title = $row['B'];
        $p->alias = $row['G'];
        if (isset($row['C'])) $p->snippet = $row['C'];
        if (isset($row['D'])) $p->content = $row['D'];
        $p->preview_url = $row['E'];
        if (isset($row['F'])) $p->status_id = $row['F'];

        $p->save();

        if (count($p->errors) > 0) {
            Log::instance()->add(['msg' => ['function' => 'newsCreate', 'errors' => $p->errors, 'data' => $row], 'type' => 'error']);
            return false;
        } else {
            $msg = 'Добавлено: ' . $row['B'];
            Log::instance()->add(['msg' => $msg, 'type' => 'success']);
            return Yii::$app->db->getLastInsertID();
        }


    }

}