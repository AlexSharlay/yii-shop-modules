<?php

namespace common\modules\mods\mods_check\commands;

use Yii;
use yii\console\Controller;

/**
 * ModsWorker RBAC controller.
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
        'name' => 'administrateModsWorker',
        'description' => 'Администрирование модуля "Сотрудники"'
    ];


    public $contentManagerPermission = [
        'name' => 'contentManagerModsWorker',
        'description' => 'Доступ к записям модуля "Сотрудники"'
    ];

    /**
     * @var array Permission
     */
    public $permissions = [
        'BViewModsWorker' => [
            'description' => 'Просмотр модуля "Сотрудники"'
        ],
        'BCreateModsWorker' => [
            'description' => 'Создание записей модуля "Сотрудники"'
        ],
        'BUpdateModsWorker' => [
            'description' => 'Изменение записей модуля "Сотрудники"'
        ],
        'BDeleteModsWorker' => [
            'description' => 'Удаление записей модуля "Сотрудники"'
        ],
    ];

    public $permissionsContentManager = [
        'BViewModsWorker' => [
            'description' => 'Просмотр модуля "Сотрудники"'
        ],
        'BCreateModsWorker' => [
            'description' => 'Создание записей модуля "Сотрудники"'
        ],
        'BUpdateModsWorker' => [
            'description' => 'Изменение записей модуля "Сотрудники"'
        ],
        'BDeleteModsWorker' => [
            'description' => 'Удаление записей модуля "Сотрудники"'
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

        /* Контент менеджер */
        $auth = Yii::$app->authManager;
        $contentManager = $auth->getRole('contentManager');
        $contentManagerPermission = $auth->createPermission($this->contentManagerPermission['name']);
        if (isset($this->contentManagerPermission['description'])) {
            $contentManagerPermission->description = $this->contentManagerPermission['description'];
        }
        if (isset($this->contentManagerPermission['rule'])) {
            $contentManagerPermission->ruleName = $this->contentManagerPermission['rule'];
        }
        $auth->add($contentManagerPermission);

        foreach ($this->permissionsContentManager as $name => $option) {
            $permission = $auth->createPermission($name);
            if (isset($option['description'])) {
                $permission->description = $option['description'];
            }
            if (isset($option['rule'])) {
                $permission->ruleName = $option['rule'];
            }
            $auth->add($permission);
            $auth->addChild($contentManagerPermission, $permission);
        }

        $auth->addChild($contentManager, $contentManagerPermission);

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