<?php

namespace common\modules\mods\mods_worker;

use Yii;

class Module extends \common\modules\mods\Module
{

    public static $name = 'mods_check';
    
    public $checkTempPath = '@statics/temp/mods/check/images';
    public $checkPath = '@statics/web/mods/check/images';
    public $checkUrl = '/statics/mods/check/images';

}
