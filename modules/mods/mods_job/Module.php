<?php

namespace common\modules\mods\mods_job;

use Yii;

class Module extends \common\modules\mods\Module
{
    /**
     * @inheritdoc
     */
    public static $name = 'mods_job';

    // Imperavi - картинки
    public $jobUrl = '/statics/mods/job/content';
    public $jobPath = '@statics/web/mods/job/content';

    // Imperavi - файлы
    public $fileUrl = '/statics/mods/job/files';
    public $filePath = '@statics/web/mods/job/files';

}
