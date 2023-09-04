<?php

namespace common\modules\mods\mods_news;

use Yii;

class Module extends \common\modules\mods\Module
{

    public static $name = 'mods_news';
    
    public $newsTempPath = '@statics/temp/mods/news/images';
    public $newsPath = '@statics/web/mods/news/images';
    public $newsUrl = '/statics/mods/news/images';

}
