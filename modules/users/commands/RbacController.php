<?php

namespace common\modules\users\commands;

use Yii;
use yii\console\Controller;

/**
 * Users RBAC controller.
 */
class RbacController extends Controller
{
    /**
     * @inheritdoc
     */
    public $defaultAction = 'add';

    /**
     * @var array Main modules permission array
     */
    public $mainPermission = [
        'name' => 'administrateUsers',
        'description' => 'Доступ ко всему модулю "Пользователи"'
    ];

    public $contentManagerPermission = [
        'name' => 'contentManagerUsers',
        'description' => 'Доступ к модулю "Пользователи"'
    ];

    public $superManagerPermission = [
        'name' => 'superManagerUsers',
        'description' => 'Доступ к модулю "Пользователи"'
    ];

    /**
     * @var array Permission
     */
    public $permissions = [
        'BViewUsers' => [
            'description' => 'Can view backend users list'
        ],
        'BCreateUsers' => [
            'description' => 'Can create backend users'
        ],
        'BUpdateUsers' => [
            'description' => 'Can update backend users'
        ],
        'BDeleteUsers' => [
            'description' => 'Can delete backend users'
        ],
        'viewUsers' => [
            'description' => 'Can view users list'
        ],
        'createUsers' => [
            'description' => 'Can create users'
        ],
        'updateUsers' => [
            'description' => 'Can update users'
        ],
        'updateOwnUsers' => [
            'description' => 'Can update own user profile',
            'rule' => 'author'
        ],
        'deleteUsers' => [
            'description' => 'Can delete users'
        ],
        'deleteOwnUsers' => [
            'description' => 'Can delete own user profile',
            'rule' => 'author'
        ]
    ];

    public $permissionsContentManager = [
        'updateOwnUsers' => [
            'description' => 'Can update own user profile',
            'rule' => 'author'
        ],
        'deleteOwnUsers' => [
            'description' => 'Can delete own user profile',
            'rule' => 'author'
        ]
    ];

    public $permissionsSuperManager = [
        'BViewUsers' => [
            'description' => 'Can view backend users list'
        ],
        'BUpdateUsers' => [
            'description' => 'Can update backend users'
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

        $updateUsers = $auth->getPermission('updateUsers');
        $updateOwnUsers = $auth->getPermission('updateOwnUsers');
        $deleteUsers = $auth->getPermission('deleteUsers');
        $deleteOwnUsers = $auth->getPermission('deleteOwnUsers');

        $auth->addChild($updateUsers, $updateOwnUsers);
        $auth->addChild($deleteUsers, $deleteOwnUsers);

        /* Менеджер */

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

        /* СуперМенеджер */

        $auth = Yii::$app->authManager;
        $superManager = $auth->getRole('superManager');
        $superManagerPermission = $auth->createPermission($this->superManagerPermission['name']);
        if (isset($this->superManagerPermission['description'])) {
            $superManagerPermission->description = $this->superManagerPermission['description'];
        }
        if (isset($this->superManagerPermission['rule'])) {
            $superManagerPermission->ruleName = $this->superManagerPermission['rule'];
        }
        $auth->add($superManagerPermission);

        foreach ($this->permissionsSuperManager as $name => $option) {
            $permission = $auth->createPermission($name);
            if (isset($option['description'])) {
                $permission->description = $option['description'];
            }
            if (isset($option['rule'])) {
                $permission->ruleName = $option['rule'];
            }
            $auth->add($permission);
            $auth->addChild($superManagerPermission, $permission);
        }

        $auth->addChild($superManager, $superManagerPermission);

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
