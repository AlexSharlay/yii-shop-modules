<?php

namespace common\modules\shop;

use yii\base\BootstrapInterface;

/**
 * Users module bootstrap class.
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {

        // Add module I18N category.
        if (!isset($app->i18n->translations['common/modules/shop']) && !isset($app->i18n->translations['common/modules/*'])) {
            $app->i18n->translations['common/modules/shop'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@common/modules/shop/messages',
                'forceTranslation' => true,
                'fileMap' => [
                    'common/modules/shop' => 'shop.php',
                ]
            ];
        }
    }
}
