<?php

namespace common\modules\mods\mods_slides\traits;

use Yii;

/**
 * Class ModuleTrait
 * @package common\module\mods\mods_slides\traits
 * Implements `getModule` method, to receive current module instance.
 */
trait ModuleTrait
{
    /**
     * @var \common\modules\mods\mods_slides\Module|null Module instance
     */
    private $_module;

    /**
     * @return \common\modules\mods\mods_slides\Module|null Module instance
     */
    public function getModule()
    {
        if ($this->_module === null) {
            $this->_module = Yii::$app->getModule('mods_slides');
        }
        return $this->_module;
    }
}
