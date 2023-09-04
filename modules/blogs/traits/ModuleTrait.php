<?php

namespace common\modules\blogs\traits;

use Yii;

/**
 * Class ModuleTrait
 * @package common\module\blogs\traits
 * Implements `getModule` method, to receive current module instance.
 */
trait ModuleTrait
{
    /**
     * @var \common\modules\blogs\Module|null Module instance
     */
    private $_module;

    /**
     * @return \common\modules\blogs\Module|null Module instance
     */
    public function getModule()
    {
        if ($this->_module === null) {
            $this->_module = Yii::$app->getModule('blogs');
        }
        return $this->_module;
    }
}
