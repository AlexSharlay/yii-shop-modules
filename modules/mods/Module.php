<?php

namespace common\modules\mods;

use Yii;
use yii\base\InvalidConfigException;

/**
 * Base module.
 */
class Module extends \yii\base\Module
{
    /**
     * @var boolean Whether module is used for backend or not
     */
    public $isBackend = false;

    /**
     * @var string|null Module name
     */
    public static $name;

    /**
     * @var string Module author
     */
    public static $author;

    /**
     * @inheritdoc
     */
    public function init()
    {

        if (static::$name === null) {
            throw new InvalidConfigException('The "name" property must be set.');
        }

        if ($this->isBackend === true) {
            $this->setViewPath('@common/modules/mods/' . static::$name . '/views/backend');
            if ($this->controllerNamespace === null) {
                $this->controllerNamespace = 'common\modules\mods\\' . static::$name . '\controllers\backend';
            }
        } else {
            $this->setViewPath('@common/modules/mods/' . static::$name . '/views/frontend');
            if ($this->controllerNamespace === null) {
                $this->controllerNamespace = 'common\modules\mods\\' . static::$name . '\controllers\frontend';
            }
        }

        parent::init();
    }

}
