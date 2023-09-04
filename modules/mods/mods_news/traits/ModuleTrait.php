<?php

namespace common\modules\mods\mods_news\traits;

use Yii;

/**
 * Class ModuleTrait
 * @package common\module\mods\mods_news\traits
 * Implements `getModule` method, to receive current module instance.
 */
trait ModuleTrait
{
    /**
     * @var \common\modules\mods\mods_news\Module|null Module instance
     */
    private $_module;

    /**
     * @return \common\modules\mods\mods_news\Module|null Module instance
     */
    public function getModule()
    {
        if ($this->_module === null) {
            $this->_module = Yii::$app->getModule('mods_news');
        }
        return $this->_module;
    }
}
