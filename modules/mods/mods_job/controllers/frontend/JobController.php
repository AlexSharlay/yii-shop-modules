<?php

namespace common\modules\mods\mods_job\controllers\frontend;

use Yii;
use frontend\components\Controller;
use common\modules\mods\mods_job\models\frontend\Job;

class JobController extends Controller
{

    public function actionJobs()
    {
        return $this->render('jobs', [
            'jobs' => Job::getJobs(),
        ]);
    }

    public function actionJob($id)
    {
        return $this->render('job', [
            'job' => Job::findOne($id),
        ]);
    }

}
