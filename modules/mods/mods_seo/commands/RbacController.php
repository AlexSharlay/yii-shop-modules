<?php

namespace common\modules\mods\mods_seo\commands;

use Yii;
use yii\console\Controller;

/**
 * ModsSeo RBAC controller.
 */
class RbacController extends Controller
{
    /**
     * @inheritdoc
     */
    public $defaultAction = 'add';

    /**
     * @var array Main module permission array
     */
    public $mainPermission = [
        'name' => 'administrateModsSeo',
        'description' => 'Администрирование модуля "SEO"'
    ];


    public $hrPermission = [
        'name' => 'hrModsSeo',
        'description' => 'Администрирование модуля "SEO"'
    ];

    /**
     * @var array Permission
     */
    public $permissions = [
        'BViewModsSeo' => [
            'description' => 'Просмотр модуля "SEO"'
        ],
        'BCreateModsSeo' => [
            'description' => 'Создание записей модуля "SEO"'
        ],
        'BUpdateModsSeo' => [
            'description' => 'Изменение записей модуля "SEO"'
        ],
        'BDeleteModsSeo' => [
            'description' => 'Удаление записей модуля "SEO"'
        ],
    ];

    public $hrPermissions = [
        'BViewModsSeo' => [
            'description' => 'Просмотр модуля "SEO"'
        ],
        'BCreateModsSeo' => [
            'description' => 'Создание записей модуля "SEO"'
        ],
        'BUpdateModsSeo' => [
            'description' => 'Изменение записей модуля "SEO"'
        ],
        'BDeleteModsSeo' => [
            'description' => 'Удаление записей модуля "SEO"'
        ],
    ];

    /**
     * Add comments RBAC.
     */
    public function actionAdd()
    {

        /* Админ */
        $auth = Yii::$app->authManager;
        $admin = $auth->getRole('admin');
        $mainPermission = $auth->createPermission($this->mainPermission['name']);
        if (isset($this->mainPermission['description'])) {
            $mainPermission->description = $this->mainPermission['description'];
        }
        if (isset($this->mainPermission['rule'])) {
            $mainPermission->ruleName = $this->mainPermission['rule'];
        }
        $auth->add($mainPermission);

        foreach ($this->permissions as $name => $option) {
            $permission = $auth->createPermission($name);
            if (isset($option['description'])) {
                $permission->description = $option['description'];
            }
            if (isset($option['rule'])) {
                $permission->ruleName = $option['rule'];
            }
            $auth->add($permission);
            $auth->addChild($mainPermission, $permission);
        }

        $auth->addChild($admin, $mainPermission);

        /* Кадровик */
        $auth = Yii::$app->authManager;
        $hr = $auth->getRole('hr');
        $hrPermission = $auth->createPermission($this->hrPermission['name']);
        if (isset($this->hrPermission['description'])) {
            $hrPermission->description = $this->hrPermission['description'];
        }
        if (isset($this->hrPermission['rule'])) {
            $hrPermission->ruleName = $this->hrPermission['rule'];
        }
        $auth->add($hrPermission);

        foreach ($this->hrPermissions as $name => $option) {
            $permission = $auth->createPermission($name);
            if (isset($option['description'])) {
                $permission->description = $option['description'];
            }
            if (isset($option['rule'])) {
                $permission->ruleName = $option['rule'];
            }
            $auth->add($permission);
            $auth->addChild($hrPermission, $permission);
        }

        $auth->addChild($hr, $hrPermission);

        return static::EXIT_CODE_NORMAL;
    }

    /**
     * Remove comments RBAC.
     */
    public function actionRemove()
    {
        $auth = Yii::$app->authManager;
        $permissions = array_keys($this->permissions);

        foreach ($permissions as $name => $option) {
            $permission = $auth->getPermission($name);
            $auth->remove($permission);
        }

        $mainPermission = $auth->getPermission($this->mainPermission['name']);
        $auth->remove($mainPermission);

        return static::EXIT_CODE_NORMAL;
    }
}