<?php

namespace common\modules\mods\mods_worker;

use Yii;

class Module extends \common\modules\mods\Module
{

    public static $name = 'mods_worker';
    
    public $workerTempPath = '@statics/temp/mods/worker/images';
    public $workerPath = '@statics/web/mods/worker/images';
    public $workerUrl = '/statics/mods/worker/images';

}
