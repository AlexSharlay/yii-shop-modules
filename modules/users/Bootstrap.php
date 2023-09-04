<?php

namespace common\modules\users;

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
        if (!isset($app->i18n->translations['common/modules/users']) && !isset($app->i18n->translations['common/modules/*'])) {
            $app->i18n->translations['common/modules/users'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@common/modules/users/messages',
                'forceTranslation' => true,
                'fileMap' => [
                    'common/modules/users' => 'users.php',
                ]
            ];
        }
    }
}
