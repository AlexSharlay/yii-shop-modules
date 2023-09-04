<?php

namespace common\modules\mods\mods_slides;

use Yii;

class Module extends \common\modules\mods\Module
{
    /**
     * @inheritdoc
     */
    public static $name = 'mods_slides';

    // Fileapi - картинки
    public $slidesTempPath = '@statics/temp/mods/slides/images';
    public $slidesPath = '@statics/web/mods/slides/images';
    public $slidesUrl = '/statics/mods/slides/images';

}
