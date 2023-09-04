<?php

namespace common\modules\rbac;

use yii\base\BootstrapInterface;

/**
 * Blogs module bootstrap class.
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        // Add module I18N category.
        if (!isset($app->i18n->translations['common/modules/rbac']) && !isset($app->i18n->translations['common/modules/*'])) {
            $app->i18n->translations['common/modules/rbac'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@common/modules/rbac/messages',
                'forceTranslation' => true,
                'fileMap' => [
                    'common/modules/rbac' => 'rbac.php',
                ]
            ];
        }
    }
}
