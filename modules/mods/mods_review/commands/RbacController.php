<?php

namespace common\modules\mods\mods_review\commands;

use Yii;
use yii\console\Controller;

/**
 * ModsReview RBAC controller.
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
        'name' => 'administrateModsReview',
        'description' => 'Администрирование модуля "Отзывы о компании"'
    ];


    public $contentManagerPermission = [
        'name' => 'contentManagerModsReview',
        'description' => 'Доступ к записям модуля "Отзывы о компании"'
    ];

    /**
     * @var array Permission
     */
    public $permissions = [
        'BViewModsReview' => [
            'description' => 'Просмотр модуля "Отзывы о компании"'
        ],
        'BCreateModsReview' => [
            'description' => 'Создание записей модуля "Отзывы о компании"'
        ],
        'BUpdateModsReview' => [
            'description' => 'Изменение записей модуля "Отзывы о компании"'
        ],
        'BDeleteModsReview' => [
            'description' => 'Удаление записей модуля "Отзывы о компании"'
        ],
    ];

    public $permissionsContentManager = [
        'BViewModsReview' => [
            'description' => 'Просмотр модуля "Отзывы о компании"'
        ],
        'BCreateModsReview' => [
            'description' => 'Создание записей модуля "Отзывы о компании"'
        ],
        'BUpdateModsReview' => [
            'description' => 'Изменение записей модуля "Отзывы о компании"'
        ],
        'BDeleteModsReview' => [
            'description' => 'Удаление записей модуля "Отзывы о компании"'
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