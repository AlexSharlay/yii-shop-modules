<?php

namespace common\modules\mods\mods_job\models\frontend;

use Yii;

class Job extends \common\modules\mods\mods_job\models\Job
{

    public static function getJobs() {
        $result = [];
        $jobs = Job::find()->orderBy('department, sort')->asArray()->all();
        foreach($jobs as $job) {
            $result[$job['department']][] = [
                'id' => $job['id'],
                'vacancy' => $job['vacancy'],
                'salary' => $job['salary'],
                //'content' => $job['content'],
            ];
        }
        return $result;
    }

}
