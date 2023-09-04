<?php

namespace common\modules\mods\mods_manufacturer;

use Yii;

class Module extends \common\modules\mods\Module
{
    /**
     * @inheritdoc
     */
    public static $name = 'mods_manufacturer';

    // Fileapi - картинки
    public $manufacturerTempPath = '@statics/temp/mods/manufacturer/images';
    public $manufacturerPath = '@statics/web/mods/manufacturer/images';
    public $manufacturerUrl = '/statics/mods/manufacturer/images';

}
