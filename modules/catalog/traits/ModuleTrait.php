<?php

namespace common\modules\catalog\traits;

use Yii;

/**
 * Class ModuleTrait
 * @package common\module\category\traits
 * Implements `getModule` method, to receive current module instance.
 */
trait ModuleTrait
{
    /**
     * @var \common\modules\catalog\Module|null Module instance
     */
    private $_module;

    /**
     * @return \common\modules\catalog\Module|null Module instance
     */
    public function getModule()
    {
        if ($this->_module === null) {
            $this->_module = Yii::$app->getModule('catalog');
        }
        return $this->_module;
    }
}
