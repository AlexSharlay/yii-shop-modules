<?php

namespace common\modules\mods\mods_worker\models\backend;

use Yii;
use common\components\fileapi\behaviors\UploadBehavior;

class Worker extends \common\modules\mods\mods_worker\models\Worker {

    public function behaviors()
    {
        return [
            'uploadBehavior' => [
                'class' => UploadBehavior::className(),
                'attributes' => [
                    'photo' => [
                        'path' => $this->module->workerPath,
                        'tempPath' => $this->module->workerTempPath,
                        'url' => $this->module->workerUrl,
                    ],
                ]
            ],
        ];
    }

    public static function workers() {
        $workers = Worker::find()->orderBy('department, sort')->asArray()->all();
        foreach($workers as $key=>$worker) {
            $workers[$key]['flags'] = [];
            for($i=1;$i<=5;$i++) {
                if ($worker['flag'.$i]) $workers[$key]['flags'][] = $worker['flag'.$i];
                if ($worker['flag'.$i] == '') unset($workers[$key]['flag'.$i]);
            }

        }
        return $workers;
    }
}
