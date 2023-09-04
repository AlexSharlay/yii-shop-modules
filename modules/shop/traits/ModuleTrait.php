<?php

namespace common\modules\shop\traits;

use Yii;

/**
 * Class ModuleTrait
 * @package common\module\shop\traits
 * Implements `getModule` method, to receive current module instance.
 */
trait ModuleTrait
{
    /**
     * @var \common\modules\shop\Module|null Module instance
     */
    private $_module;

    /**
     * @return \common\modules\shop\Module|null Module instance
     */
    public function getModule()
    {
        if ($this->_module === null) {
            $this->_module = Yii::$app->getModule('shop');
        }
        return $this->_module;
    }
}
