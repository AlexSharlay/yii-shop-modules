<?php

namespace common\modules\rbac\commands;

use common\modules\rbac\rules\AuthorRule;
use Yii;
use yii\console\Controller;

/**
 * RBAC console controller.
 */
class RbacController extends Controller
{
    /**
     * @inheritdoc
     */
    public $defaultAction = 'init';

    /**
     * Initial RBAC action.
     */
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        // Rules
        $authorRule = new AuthorRule();
        $auth->add($authorRule);

        // Permissions
        $accessBackend = $auth->createPermission('accessBackend');
        $accessBackend->description = 'Can access backend';
        $auth->add($accessBackend);

        $administrateRbac = $auth->createPermission('administrateRbac');
        $administrateRbac->description = 'Can administrate all "RBAC" module';
        $auth->add($administrateRbac);

        $BViewRoles = $auth->createPermission('BViewRoles');
        $BViewRoles->description = 'Can view roles list';
        $auth->add($BViewRoles);

        $BCreateRoles = $auth->createPermission('BCreateRoles');
        $BCreateRoles->description = 'Can create roles';
        $auth->add($BCreateRoles);

        $BUpdateRoles = $auth->createPermission('BUpdateRoles');
        $BUpdateRoles->description = 'Can update roles';
        $auth->add($BUpdateRoles);

        $BDeleteRoles = $auth->createPermission('BDeleteRoles');
        $BDeleteRoles->description = 'Can delete roles';
        $auth->add($BDeleteRoles);

        $BViewPermissions = $auth->createPermission('BViewPermissions');
        $BViewPermissions->description = 'Can view permissions list';
        $auth->add($BViewPermissions);

        $BCreatePermissions = $auth->createPermission('BCreatePermissions');
        $BCreatePermissions->description = 'Can create permissions';
        $auth->add($BCreatePermissions);

        $BUpdatePermissions = $auth->createPermission('BUpdatePermissions');
        $BUpdatePermissions->description = 'Can update permissions';
        $auth->add($BUpdatePermissions);

        $BDeletePermissions = $auth->createPermission('BDeletePermissions');
        $BDeletePermissions->description = 'Can delete permissions';
        $auth->add($BDeletePermissions);

        $BViewRules = $auth->createPermission('BViewRules');
        $BViewRules->description = 'Can view rules list';
        $auth->add($BViewRules);

        $BCreateRules = $auth->createPermission('BCreateRules');
        $BCreateRules->description = 'Can create rules';
        $auth->add($BCreateRules);

        $BUpdateRules = $auth->createPermission('BUpdateRules');
        $BUpdateRules->description = 'Can update rules';
        $auth->add($BUpdateRules);

        $BDeleteRules = $auth->createPermission('BDeleteRules');
        $BDeleteRules->description = 'Can delete rules';
        $auth->add($BDeleteRules);

        // Assignments
        // Роли
        $auth->addChild($administrateRbac, $BViewRoles);
        $auth->addChild($administrateRbac, $BCreateRoles);
        $auth->addChild($administrateRbac, $BUpdateRoles);
        $auth->addChild($administrateRbac, $BDeleteRoles);
        // Доступ
        $auth->addChild($administrateRbac, $BViewPermissions);
        $auth->addChild($administrateRbac, $BCreatePermissions);
        $auth->addChild($administrateRbac, $BUpdatePermissions);
        $auth->addChild($administrateRbac, $BDeletePermissions);
        // Правила
        $auth->addChild($administrateRbac, $BViewRules);
        $auth->addChild($administrateRbac, $BCreateRules);
        $auth->addChild($administrateRbac, $BUpdateRules);
        $auth->addChild($administrateRbac, $BDeleteRules);

        // Roles
        $user = $auth->createRole('user');
        $user->description = 'Посетитель';
        $auth->add($user);
        $auth->assign($user, 10);

        $hr = $auth->createRole('hr');
        $hr->description = 'Кадровик';
        $auth->add($hr);
        $auth->addChild($hr, $user);
        $auth->addChild($hr, $accessBackend);
        $auth->assign($hr, 4);

        $superManager = $auth->createRole('superManager');
        $superManager->description = 'Менеджер по продажам';
        $auth->add($superManager);
        $auth->addChild($superManager, $user);
        $auth->addChild($superManager, $accessBackend);
        $auth->assign($superManager, 5);

        $manager = $auth->createRole('manager');
        $manager->description = 'Менеджер по продажам';
        $auth->add($manager);
        $auth->addChild($manager, $user);
        $auth->addChild($manager, $accessBackend);
        $auth->assign($manager, 3);

        $contentManager = $auth->createRole('contentManager');
        $contentManager->description = 'Контент Менеджер';
        $auth->add($contentManager);
        $auth->addChild($contentManager, $user);
        $auth->addChild($contentManager, $accessBackend);
        $auth->assign($contentManager, 2);

        $admin = $auth->createRole('admin');
        $admin->description = 'Администратор';
        $auth->add($admin);
        $auth->addChild($admin, $manager);
        $auth->addChild($admin, $contentManager);
        $auth->addChild($admin, $administrateRbac);

        $auth->assign($admin, 1);
    }
}