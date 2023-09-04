<?php

namespace common\modules\catalog;

use yii\base\BootstrapInterface;

/**
 * Catalog module bootstrap class.
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules(
            [
            ]
        );
    }
}
