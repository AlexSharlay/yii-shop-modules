<?php
/**
 * Created by PhpStorm.
 * User: vitalbu
 * Date: 07.12.2017
 * Time: 17:21
 */

namespace common\modules\catalog\components\import\excell;

use Yii;
use backend\themes\shop\pageAssets\catalog\import\one;
use common\modules\users\models\backend\User;
use common\modules\users\models\Profile;

class ImportExcellCard
{
    public function run($file)
    {
        // Данные xlsx файла
        $objPHPExcel = \PHPExcel_IOFactory::load($file);
        $rows = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
        foreach ($rows as $key => $row) {
            self::cardCreate($row);
        }
    }

    // Добавить строку в бд
    public static function cardCreate($row)
    {
        $u = new User();
        $u->scenario = 'card-create';
        $u->username = $row['A'];
        $u->status_id = 0;

        $p = new Profile();
        $p->scenario = 'card-create';
        $p->card = $row['A'];

        $u->populateRelation('profile', $p);

        $user = User::find()
            ->where('username=:username', [':username' => $u->username])
            ->limit(1)->one();

        if (!$user) {
            if ($u->save(false)) {
                echo 'Добавлена карта: ' . $u->username . '<br/>';

//                $msg = 'Добавлено: ' . $row['A'];
//                Log::instance()->add(['msg' => $msg, 'type' => 'success']);
//                return Yii::$app->db->getLastInsertID();
            } else {
                echo 'Ошибка добавления карты: ' . $u->username . '<br/>';

//                Log::instance()->add(['msg' => ['function' => 'newsCreate', 'errors' => $u->errors, 'data' => $row], 'type' => 'error']);
//                return false;
            }
        } else {

            echo 'ERROR Дубль карты: ' . $u->username . '<br/>';
//            Log::instance()->add(['msg' => ['function' => 'newsCreate', 'errors' => 'Дубль ', 'data' => $row], 'type' => 'error']);
//            return false;
        }


    }
}