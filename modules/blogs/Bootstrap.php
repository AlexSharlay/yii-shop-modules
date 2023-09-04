<?php

namespace common\modules\blogs;

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
        // Add module URL rules.
        $app->getUrlManager()->addRules(
            [

                /*
                 * Ставиться в конец поэтому не срабатывают все правила
                '<module:blogs>' => '<module>/default/index',
                '<module:blogs>/<category:\w+>/<alias:\w+>' => '<module>/default/view',
                '<module:blogs>/<category:\w+>' => '<module>/default/category',
                */

                //'POST <module:blogs>' => '<module>/user/create',
                //'<module:blogs>/<id:\d+>-<alias:[a-zA-Z0-9_-]{1,100}+>' => '<module>/default/view',
                /*
                /blogs/1-qwe
                /blogs/info/1-qwe
                Сработает и так и так, выбор идёт по id.
                */
            ]
        );

        // Add module I18N category.
        if (!isset($app->i18n->translations['common/modules/blogs']) && !isset($app->i18n->translations['common/modules/*'])) {
            $app->i18n->translations['common/modules/blogs'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@common/modules/blogs/messages',
                'forceTranslation' => true,
                'fileMap' => [
                    'common/modules/blogs' => 'blogs.php',
                ]
            ];
        }
    }
}
