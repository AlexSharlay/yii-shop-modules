<?php

namespace common\modules\mods\mods_check\traits;

use Yii;

/**
 * Class ModuleTrait
 * @package common\module\mods\mods_check\traits
 * Implements `getModule` method, to receive current module instance.
 */
trait ModuleTrait
{
    /**
     * @var \common\modules\mods\mods_check\Module|null Module instance
     */
    private $_module;

    /**
     * @return \common\modules\mods\mods_check\Module|null Module instance
     */
    public function getModule()
    {
        if ($this->_module === null) {
            $this->_module = Yii::$app->getModule('mods_check');
        }
        return $this->_module;
    }
}
