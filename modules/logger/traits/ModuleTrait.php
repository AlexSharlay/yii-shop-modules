<?php

namespace common\modules\logger\traits;

use Yii;

/**
 * Class ModuleTrait
 * @package common\module\logger\traits
 * Implements `getModule` method, to receive current module instance.
 */
trait ModuleTrait
{
    /**
     * @var \common\modules\logger\Module|null Module instance
     */
    private $_module;

    /**
     * @return \common\modules\logger\Module|null Module instance
     */
    public function getModule()
    {
        if ($this->_module === null) {
            $this->_module = Yii::$app->getModule('logger');
        }
        return $this->_module;
    }
}
