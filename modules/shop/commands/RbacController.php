<?php

namespace common\modules\shop\commands;

use Yii;
use yii\console\Controller;

/**
 * Shop RBAC controller.
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
        'name' => 'administrateShop',
        'description' => 'Администрирование модулем "Блог"'
    ];

    public $managerPermission = [
        'name' => 'managerOrders',
        'description' => 'Менеджер'
    ];

    /**
     * @var array Permission
     */
    public $permissions = [
        'BViewShopDelivery' => [
            'description' => 'Просмотр доставок'
        ],
        'BCreateShopDelivery' => [
            'description' => 'Создание доставок'
        ],
        'BUpdateShopDelivery' => [
            'description' => 'Изменение доставок'
        ],
        'BDeleteShopDelivery' => [
            'description' => 'Удаление доставок'
        ],
        'BViewShopPayment' => [
            'description' => 'Просмотр вариантов оплат'
        ],
        'BCreateShopPayment' => [
            'description' => 'Создание вариантов оплат'
        ],
        'BUpdateShopPayment' => [
            'description' => 'Изменение вариантов оплат'
        ],
        'BDeleteShopPayment' => [
            'description' => 'Удаление вариантов оплат'
        ],
        'BViewShopOrder' => [
            'description' => 'Просмотр заказов'
        ],
        'BDeleteShopOrder' => [
            'description' => 'Удаление заказов'
        ],
        'BViewClientManager' => [
            'description' => 'Просмотр связей Клиент - Менеджер'
        ],
        'BCreateClientManager' => [
            'description' => 'Создание связей Клиент - Менеджер'
        ],
        'BUpdateClientManager' => [
            'description' => 'Изменение связей Клиент - Менеджер'
        ],
        'BDeleteClientManager' => [
            'description' => 'Удаление связей Клиент - Менеджер'
        ],
    ];

    public $permissionsManager = [
        'BViewShopOrder' => [
            'description' => 'Просмотр заказов'
        ],
        'BDeleteShopOrder' => [
            'description' => 'Удаление заказов'
        ],
    ];

    public $permissionsSuperManager = [
        'BViewClientManager' => [
            'description' => 'Просмотр связей Клиент - Менеджер'
        ],
        'BCreateClientManager' => [
            'description' => 'Создание связей Клиент - Менеджер'
        ],
        'BUpdateClientManager' => [
            'description' => 'Изменение связей Клиент - Менеджер'
        ],
        'BDeleteClientManager' => [
            'description' => 'Удаление связей Клиент - Менеджер'
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

        /* Менеджер */

        $auth = Yii::$app->authManager;
        $manager = $auth->getRole('manager');

        $managerPermission = $auth->createPermission($this->managerPermission['name']);
        if (isset($this->managerPermission['description'])) {
            $managerPermission->description = $this->managerPermission['description'];
        }
        if (isset($this->managerPermission['rule'])) {
            $managerPermission->ruleName = $this->managerPermission['rule'];
        }
       $auth->add($managerPermission);

        foreach ($this->permissionsManager as $name => $option) {
            $permission = $auth->createPermission($name);
            if (isset($option['description'])) {
                $permission->description = $option['description'];
            }
            if (isset($option['rule'])) {
                $permission->ruleName = $option['rule'];
            }
            $auth->add($permission);
            $auth->addChild($managerPermission, $permission);
        }

        $auth->addChild($manager, $managerPermission);

        /* СуперМенеджер */

        $auth = Yii::$app->authManager;
        $manager = $auth->getRole('superManager');

        $managerPermission = $auth->createPermission($this->managerPermission['name']);
        if (isset($this->managerPermission['description'])) {
            $managerPermission->description = $this->managerPermission['description'];
        }
        if (isset($this->managerPermission['rule'])) {
            $managerPermission->ruleName = $this->managerPermission['rule'];
        }
       $auth->add($managerPermission);

        foreach ($this->permissionsSuperManager as $name => $option) {
            $permission = $auth->createPermission($name);
            if (isset($option['description'])) {
                $permission->description = $option['description'];
            }
            if (isset($option['rule'])) {
                $permission->ruleName = $option['rule'];
            }
            $auth->add($permission);
            $auth->addChild($managerPermission, $permission);
        }

        $auth->addChild($manager, $managerPermission);

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