<?php

namespace common\modules\catalog\commands;

use Yii;
use yii\console\Controller;

/**
 * Blogs RBAC controller.
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
        'name' => 'administrateCategory',
        'description' => 'Администрирование модулем "Каталог"'
    ];

    /**
     * @var array Permission
     */
    public $permissions = [
        'BViewCatalogCategory' => [
            'description' => 'Просмотр в модуле "Каталог" списка категорий'
        ],
        'BCreateCatalogCategory' => [
            'description' => 'Создание в модуле "Каталог" категорий'
        ],
        'BUpdateCatalogCategory' => [
            'description' => 'Изменение в модуле "Каталог" категорий'
        ],
        'BDeleteCatalogCategory' => [
            'description' => 'Удаление в модуле "Каталог" категорий'
        ],


        'BViewCatalogCountry' => [
            'description' => 'Просмотр в модуле "Каталог" списка стран'
        ],
        'BCreateCatalogCountry' => [
            'description' => 'Создание в модуле "Каталог" стран'
        ],
        'BUpdateCatalogCountry' => [
            'description' => 'Изменение в модуле "Каталог" стран'
        ],
        'BDeleteCatalogCountry' => [
            'description' => 'Удаление в модуле "Каталог" стран'
        ],


        'BViewCatalogManufacturer' => [
            'description' => 'Просмотр в модуле "Каталог" списка производителей'
        ],
        'BCreateCatalogManufacturer' => [
            'description' => 'Создание в модуле "Каталог" производителей'
        ],
        'BUpdateCatalogManufacturer' => [
            'description' => 'Изменение в модуле "Каталог" производителей'
        ],
        'BDeleteCatalogManufacturer' => [
            'description' => 'Удаление в модуле "Каталог" производителей'
        ],


        'BViewCatalogMeasurement' => [
            'description' => 'Просмотр в модуле "Каталог" списка измерений'
        ],
        'BCreateCatalogMeasurement' => [
            'description' => 'Создание в модуле "Каталог" измерений'
        ],
        'BUpdateCatalogMeasurement' => [
            'description' => 'Изменение в модуле "Каталог" измерений'
        ],
        'BDeleteCatalogMeasurement' => [
            'description' => 'Удаление в модуле "Каталог" измерений'
        ],


        'BViewCatalogElement' => [
            'description' => 'Просмотр в модуле "Каталог" списка товаров'
        ],
        'BCreateCatalogElement' => [
            'description' => 'Создание в модуле "Каталог" товаров'
        ],
        'BUpdateCatalogElement' => [
            'description' => 'Изменение в модуле "Каталог" товаров'
        ],
        'BDeleteCatalogElement' => [
            'description' => 'Удаление в модуле "Каталог" товаров'
        ],


        'BViewCatalogCollection' => [
            'description' => 'Просмотр в модуле "Коллекции" коллекций и отношений с товарами'
        ],
        'BCreateCatalogCollection' => [
            'description' => 'Создание в модуле "Коллекции" коллекций и отношений с товарами'
        ],
        'BUpdateCatalogCollection' => [
            'description' => 'Изменение в модуле "Коллекции" коллекций и отношений с товарами'
        ],
        'BDeleteCatalogCollection' => [
            'description' => 'Удаление в модуле "Коллекции" коллекций и отношений с товарами'
        ],


        'BAllCatalogTools' => [
            'description' => 'Все действия в разделе "Инструменты" модуля "Коллекции"'
        ],

        'BAllCatalogStatics' => [
            'description' => 'Все действия в разделе "Статистика" модуля "Каталог"'
        ],

        /*
        'BViewCatalogComplect' => [
            'description' => 'Просмотр в модуле "Комплеты" списка комплектов'
        ],
        'BCreateCatalogComplect' => [
            'description' => 'Создание в модуле "Комплеты" комплектов'
        ],
        'BUpdateCatalogComplect' => [
            'description' => 'Изменение в модуле "Комплеты" комплектов'
        ],
        'BDeleteCatalogComplect' => [
            'description' => 'Удаление в модуле "Комплеты" комплектов'
        ],


        'BViewCatalogModel' => [
            'description' => 'Просмотр в модуле "Модели" списка моделей'
        ],
        'BCreateCatalogModel' => [
            'description' => 'Создание в модуле "Модели" моделей'
        ],
        'BUpdateCatalogModel' => [
            'description' => 'Изменение в модуле "Модели" моделей'
        ],
        'BDeleteCatalogModel' => [
            'description' => 'Удаление в модуле "Модели" моделей'
        ],
        */
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
