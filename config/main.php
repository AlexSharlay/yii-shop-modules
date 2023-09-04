<?php

return [
    'id' => 'basic',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'timeZone' => 'Europe/Moscow',
    'basePath' => dirname(__DIR__),
    'modules' => [
        'users' => [
            'class' => 'common\modules\users\Module',
            'robotEmail' => '',
            'robotName' => 'Robot'
        ],
        'blogs' => [
            'class' => 'common\modules\blogs\Module'
        ],
        'catalog' => [
            'class' => 'common\modules\catalog\Module',
        ],
        'shop' => [
            'class' => 'common\modules\shop\Module',
        ],
        'comments' => [
            'class' => 'common\modules\comments\Module'
        ],
        'logger' => [
            'class' => 'common\modules\logger\Module'
        ],
        'mods_manufacturer' => [
            'class' => 'common\modules\mods\mods_manufacturer\Module'
        ],
        'mods_job' => [
            'class' => 'common\modules\mods\mods_job\Module'
        ],
        'mods_worker' => [
            'class' => 'common\modules\mods\mods_worker\Module'
        ],
        'mods_news' => [
            'class' => 'common\modules\mods\mods_news\Module'
        ],
        'mods_slides' => [
            'class' => 'common\modules\mods\mods_slides\Module'
        ],
        'mods_review' => [
            'class' => 'common\modules\mods\mods_review\Module'
        ],
        'mods_seo' => [
            'class' => 'common\modules\mods\mods_seo\Module'
        ],
        'mods_check' => [
            'class' => 'common\modules\mods\mods_check\Module'
        ],
        'mods_reviews' => [
            'class' => 'common\modules\mods\mods_reviews\Module'
        ],
    ],
    'bootstrap' => [
        'common\modules\rbac\Bootstrap',
        'common\modules\users\Bootstrap',
        'common\modules\blogs\Bootstrap',
        'common\modules\comments\Bootstrap',
        'common\modules\catalog\Bootstrap',
        'common\modules\shop\Bootstrap',
        'common\modules\logger\Bootstrap',
        'common\modules\mods\mods_manufacturer\Bootstrap',
        'common\modules\mods\mods_job\Bootstrap',
        'common\modules\mods\mods_worker\Bootstrap',
        'common\modules\mods\mods_news\Bootstrap',
        'common\modules\mods\mods_slides\Bootstrap',
        'common\modules\mods\mods_review\Bootstrap',
        'common\modules\mods\mods_seo\Bootstrap',
        'common\modules\mods\mods_check\Bootstrap',
        'common\modules\mods\mods_reviews\Bootstrap',
    ],
    'components' => [
        'logger' => [
            'class' => 'common\modules\logger\components\Logger',
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'common\modules\users\models\User',
            'loginUrl' => ['users/guest/login']
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@root/cache',
            'keyPrefix' => 'yii2'
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'suffix' => '/'
        ],
        'assetManager' => [
            'linkAssets' => true
        ],
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
            'defaultRoles' => [
                'user'
            ],
            'itemFile' => '@common/modules/rbac/data/items.php',
            'assignmentFile' => '@common/modules/rbac/data/assignments.php',
            'ruleFile' => '@common/modules/rbac/data/rules.php',
        ],
        'formatter' => [
            'dateFormat' => 'dd.MM.y',
            'datetimeFormat' => 'HH:mm:ss dd.MM.y'
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@frontend/views' => '@frontend/themes/shop/views',
                    '@backend/views' => '@backend/themes/shop/views',
                    '@mobile/views' => '@mobile/themes/shop/views',
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'extmail.com',
                'username' => '',
                'password' => 'Qq159753',
                'port' => '587',
                'encryption' => 'tls',
            ],
            'useFileTransport' => false,
        ],
    ],
    'params' => require(__DIR__ . '/params.php')
];
