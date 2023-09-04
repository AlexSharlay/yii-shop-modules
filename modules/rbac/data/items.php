<?php
return [
    'accessBackend' => [
        'type' => 2,
        'description' => 'Can access backend',
    ],
    'administrateRbac' => [
        'type' => 2,
        'description' => 'Can administrate all "RBAC" module',
        'children' => [
            'BViewRoles',
            'BCreateRoles',
            'BUpdateRoles',
            'BDeleteRoles',
            'BViewPermissions',
            'BCreatePermissions',
            'BUpdatePermissions',
            'BDeletePermissions',
            'BViewRules',
            'BCreateRules',
            'BUpdateRules',
            'BDeleteRules',
        ],
    ],
    'BViewRoles' => [
        'type' => 2,
        'description' => 'Can view roles list',
    ],
    'BCreateRoles' => [
        'type' => 2,
        'description' => 'Can create roles',
    ],
    'BUpdateRoles' => [
        'type' => 2,
        'description' => 'Can update roles',
    ],
    'BDeleteRoles' => [
        'type' => 2,
        'description' => 'Can delete roles',
    ],
    'BViewPermissions' => [
        'type' => 2,
        'description' => 'Can view permissions list',
    ],
    'BCreatePermissions' => [
        'type' => 2,
        'description' => 'Can create permissions',
    ],
    'BUpdatePermissions' => [
        'type' => 2,
        'description' => 'Can update permissions',
    ],
    'BDeletePermissions' => [
        'type' => 2,
        'description' => 'Can delete permissions',
    ],
    'BViewRules' => [
        'type' => 2,
        'description' => 'Can view rules list',
    ],
    'BCreateRules' => [
        'type' => 2,
        'description' => 'Can create rules',
    ],
    'BUpdateRules' => [
        'type' => 2,
        'description' => 'Can update rules',
    ],
    'BDeleteRules' => [
        'type' => 2,
        'description' => 'Can delete rules',
    ],
    'user' => [
        'type' => 1,
        'description' => 'Посетитель',
    ],
    'hr' => [
        'type' => 1,
        'description' => 'Кадровик',
        'children' => [
            'user',
            'accessBackend',
            'hrModsJob',
            'hrModsSeo',
        ],
    ],
    'superManager' => [
        'type' => 1,
        'description' => 'Супер менеджер',
        'children' => [
            'user',
            'accessBackend',
            'superManagerUsers',
            'BViewClientManager',
            'BCreateClientManager',
            'BUpdateClientManager',
            'BDeleteClientManager',
            'managerOrders',
        ],
    ],
    'manager' => [
        'type' => 1,
        'description' => 'Менеджер по продажам',
        'children' => [
            'user',
            'accessBackend',
            'managerOrders',
        ],
    ],
    'contentManager' => [
        'type' => 1,
        'description' => 'Контент Менеджер',
        'children' => [
            'user',
            'accessBackend',
            'contentManagerUsers',
            'contentManagerBlogs',
            'contentManagerModsManufacturer',
            'contentManagerModsWorker',
            'contentManagerModsNews',
            'contentManagerModsSlides',
            'contentManagerModsReview',
        ],
    ],
    'admin' => [
        'type' => 1,
        'description' => 'Администратор',
        'children' => [
            'manager',
            'contentManager',
            'administrateRbac',
            'administrateUsers',
            'administrateBlogs',
            'administrateCategory',
            'administrateShop',
            'administrateLogger',
            'administrateModsManufacturer',
            'administrateModsJob',
            'administrateModsWorker',
            'administrateModsNews',
            'administrateModsSlides',
            'administrateModsReview',
            'administrateModsSeo',
            'BViewModsReviews',
        ],
    ],
    'administrateUsers' => [
        'type' => 2,
        'description' => 'Доступ ко всему модулю "Пользователи"',
        'children' => [
            'BViewUsers',
            'BCreateUsers',
            'BUpdateUsers',
            'BDeleteUsers',
            'viewUsers',
            'createUsers',
            'updateUsers',
            'updateOwnUsers',
            'deleteUsers',
            'deleteOwnUsers',
        ],
    ],
    'BViewUsers' => [
        'type' => 2,
        'description' => 'Can view backend users list',
    ],
    'BCreateUsers' => [
        'type' => 2,
        'description' => 'Can create backend users',
    ],
    'BUpdateUsers' => [
        'type' => 2,
        'description' => 'Can update backend users',
    ],
    'BDeleteUsers' => [
        'type' => 2,
        'description' => 'Can delete backend users',
    ],
    'viewUsers' => [
        'type' => 2,
        'description' => 'Can view users list',
    ],
    'createUsers' => [
        'type' => 2,
        'description' => 'Can create users',
    ],
    'updateUsers' => [
        'type' => 2,
        'description' => 'Can update users',
        'children' => [
            'updateOwnUsers',
        ],
    ],
    'updateOwnUsers' => [
        'type' => 2,
        'description' => 'Can update own user profile',
        'ruleName' => 'author',
    ],
    'deleteUsers' => [
        'type' => 2,
        'description' => 'Can delete users',
        'children' => [
            'deleteOwnUsers',
        ],
    ],
    'deleteOwnUsers' => [
        'type' => 2,
        'description' => 'Can delete own user profile',
        'ruleName' => 'author',
    ],
    'contentManagerUsers' => [
        'type' => 2,
        'description' => 'Доступ к модулю "Пользователи"',
        'children' => [
            'updateOwnUsers',
            'deleteOwnUsers',
        ],
    ],
    'superManagerUsers' => [
        'type' => 2,
        'description' => 'Доступ к модулю "Пользователи"',
        'children' => [
            'BViewUsers',
            'BUpdateUsers',
        ],
    ],
    'administrateBlogs' => [
        'type' => 2,
        'description' => 'Администрирование модулем "Блог"',
        'children' => [
            'BViewBlogs',
            'BCreateBlogs',
            'BUpdateBlogs',
            'BDeleteBlogs',
            'BViewBlogsCategory',
            'BCreateBlogsCategory',
            'BUpdateBlogsCategory',
            'BDeleteBlogsCategory',
        ],
    ],
    'BViewBlogs' => [
        'type' => 2,
        'description' => 'Can view backend blogs list',
    ],
    'BCreateBlogs' => [
        'type' => 2,
        'description' => 'Can create backend blogs',
    ],
    'BUpdateBlogs' => [
        'type' => 2,
        'description' => 'Can update backend blogs',
    ],
    'BDeleteBlogs' => [
        'type' => 2,
        'description' => 'Can delete backend blogs',
    ],
    'BViewBlogsCategory' => [
        'type' => 2,
        'description' => 'Просмотр списка категорий статей',
    ],
    'BCreateBlogsCategory' => [
        'type' => 2,
        'description' => 'Создать категорию для статей',
    ],
    'BUpdateBlogsCategory' => [
        'type' => 2,
        'description' => 'Изменить категорию для статей',
    ],
    'BDeleteBlogsCategory' => [
        'type' => 2,
        'description' => 'Удалить категорию для статей',
    ],
    'contentManagerBlogs' => [
        'type' => 2,
        'description' => 'Доступ к статьям модуля "Блог"',
        'children' => [
            'BViewBlogs',
            'BCreateBlogs',
            'BUpdateBlogs',
            'BDeleteBlogs',
        ],
    ],
    'administrateCategory' => [
        'type' => 2,
        'description' => 'Администрирование модулем "Каталог"',
        'children' => [
            'BViewCatalogCategory',
            'BCreateCatalogCategory',
            'BUpdateCatalogCategory',
            'BDeleteCatalogCategory',
            'BViewCatalogCountry',
            'BCreateCatalogCountry',
            'BUpdateCatalogCountry',
            'BDeleteCatalogCountry',
            'BViewCatalogManufacturer',
            'BCreateCatalogManufacturer',
            'BUpdateCatalogManufacturer',
            'BDeleteCatalogManufacturer',
            'BViewCatalogMeasurement',
            'BCreateCatalogMeasurement',
            'BUpdateCatalogMeasurement',
            'BDeleteCatalogMeasurement',
            'BViewCatalogElement',
            'BCreateCatalogElement',
            'BUpdateCatalogElement',
            'BDeleteCatalogElement',
            'BViewCatalogCollection',
            'BCreateCatalogCollection',
            'BUpdateCatalogCollection',
            'BDeleteCatalogCollection',
            'BAllCatalogTools',
            'BAllCatalogStatics',
        ],
    ],
    'BViewCatalogCategory' => [
        'type' => 2,
        'description' => 'Просмотр в модуле "Каталог" списка категорий',
    ],
    'BCreateCatalogCategory' => [
        'type' => 2,
        'description' => 'Создание в модуле "Каталог" категорий',
    ],
    'BUpdateCatalogCategory' => [
        'type' => 2,
        'description' => 'Изменение в модуле "Каталог" категорий',
    ],
    'BDeleteCatalogCategory' => [
        'type' => 2,
        'description' => 'Удаление в модуле "Каталог" категорий',
    ],
    'BViewCatalogCountry' => [
        'type' => 2,
        'description' => 'Просмотр в модуле "Каталог" списка стран',
    ],
    'BCreateCatalogCountry' => [
        'type' => 2,
        'description' => 'Создание в модуле "Каталог" стран',
    ],
    'BUpdateCatalogCountry' => [
        'type' => 2,
        'description' => 'Изменение в модуле "Каталог" стран',
    ],
    'BDeleteCatalogCountry' => [
        'type' => 2,
        'description' => 'Удаление в модуле "Каталог" стран',
    ],
    'BViewCatalogManufacturer' => [
        'type' => 2,
        'description' => 'Просмотр в модуле "Каталог" списка производителей',
    ],
    'BCreateCatalogManufacturer' => [
        'type' => 2,
        'description' => 'Создание в модуле "Каталог" производителей',
    ],
    'BUpdateCatalogManufacturer' => [
        'type' => 2,
        'description' => 'Изменение в модуле "Каталог" производителей',
    ],
    'BDeleteCatalogManufacturer' => [
        'type' => 2,
        'description' => 'Удаление в модуле "Каталог" производителей',
    ],
    'BViewCatalogMeasurement' => [
        'type' => 2,
        'description' => 'Просмотр в модуле "Каталог" списка измерений',
    ],
    'BCreateCatalogMeasurement' => [
        'type' => 2,
        'description' => 'Создание в модуле "Каталог" измерений',
    ],
    'BUpdateCatalogMeasurement' => [
        'type' => 2,
        'description' => 'Изменение в модуле "Каталог" измерений',
    ],
    'BDeleteCatalogMeasurement' => [
        'type' => 2,
        'description' => 'Удаление в модуле "Каталог" измерений',
    ],
    'BViewCatalogElement' => [
        'type' => 2,
        'description' => 'Просмотр в модуле "Каталог" списка товаров',
    ],
    'BCreateCatalogElement' => [
        'type' => 2,
        'description' => 'Создание в модуле "Каталог" товаров',
    ],
    'BUpdateCatalogElement' => [
        'type' => 2,
        'description' => 'Изменение в модуле "Каталог" товаров',
    ],
    'BDeleteCatalogElement' => [
        'type' => 2,
        'description' => 'Удаление в модуле "Каталог" товаров',
    ],
    'BViewCatalogCollection' => [
        'type' => 2,
        'description' => 'Просмотр в модуле "Коллекции" коллекций и отношений с товарами',
    ],
    'BCreateCatalogCollection' => [
        'type' => 2,
        'description' => 'Создание в модуле "Коллекции" коллекций и отношений с товарами',
    ],
    'BUpdateCatalogCollection' => [
        'type' => 2,
        'description' => 'Изменение в модуле "Коллекции" коллекций и отношений с товарами',
    ],
    'BDeleteCatalogCollection' => [
        'type' => 2,
        'description' => 'Удаление в модуле "Коллекции" коллекций и отношений с товарами',
    ],
    'BAllCatalogTools' => [
        'type' => 2,
        'description' => 'Все действия в разделе "Инструменты" модуля "Коллекции"',
    ],
    'BAllCatalogStatics' => [
        'type' => 2,
        'description' => 'Все действия в разделе "Статистика" модуля "Каталог"',
    ],
    'administrateShop' => [
        'type' => 2,
        'description' => 'Администрирование модулем "Блог"',
        'children' => [
            'BViewShopDelivery',
            'BCreateShopDelivery',
            'BUpdateShopDelivery',
            'BDeleteShopDelivery',
            'BViewShopPayment',
            'BCreateShopPayment',
            'BUpdateShopPayment',
            'BDeleteShopPayment',
            'BViewShopOrder',
            'BDeleteShopOrder',
            'BViewClientManager',
            'BCreateClientManager',
            'BUpdateClientManager',
            'BDeleteClientManager',
        ],
    ],
    'BViewShopDelivery' => [
        'type' => 2,
        'description' => 'Просмотр доставок',
    ],
    'BCreateShopDelivery' => [
        'type' => 2,
        'description' => 'Создание доставок',
    ],
    'BUpdateShopDelivery' => [
        'type' => 2,
        'description' => 'Изменение доставок',
    ],
    'BDeleteShopDelivery' => [
        'type' => 2,
        'description' => 'Удаление доставок',
    ],
    'BViewShopPayment' => [
        'type' => 2,
        'description' => 'Просмотр вариантов оплат',
    ],
    'BCreateShopPayment' => [
        'type' => 2,
        'description' => 'Создание вариантов оплат',
    ],
    'BUpdateShopPayment' => [
        'type' => 2,
        'description' => 'Изменение вариантов оплат',
    ],
    'BDeleteShopPayment' => [
        'type' => 2,
        'description' => 'Удаление вариантов оплат',
    ],
    'BViewShopOrder' => [
        'type' => 2,
        'description' => 'Просмотр заказов',
    ],
    'BDeleteShopOrder' => [
        'type' => 2,
        'description' => 'Удаление заказов',
    ],
    'BViewClientManager' => [
        'type' => 2,
        'description' => 'Просмотр связей Клиент - Менеджер',
    ],
    'BCreateClientManager' => [
        'type' => 2,
        'description' => 'Создание связей Клиент - Менеджер',
    ],
    'BUpdateClientManager' => [
        'type' => 2,
        'description' => 'Изменение связей Клиент - Менеджер',
    ],
    'BDeleteClientManager' => [
        'type' => 2,
        'description' => 'Удаление связей Клиент - Менеджер',
    ],
    'managerOrders' => [
        'type' => 2,
        'description' => 'Менеджер',
        'children' => [
            'BViewShopOrder',
            'BDeleteShopOrder',
            'BViewClientManager',
            'BCreateClientManager',
            'BUpdateClientManager',
            'BDeleteClientManager',
        ],
    ],
    'administrateLogger' => [
        'type' => 2,
        'description' => 'Администрирование модулем "Логгирование"',
        'children' => [
            'BViewLoggerAction',
            'BDeleteLoggerAction',
        ],
    ],
    'BViewLoggerAction' => [
        'type' => 2,
        'description' => 'Просмотр логов actions',
    ],
    'BDeleteLoggerAction' => [
        'type' => 2,
        'description' => 'Удаление логов actions',
    ],
    'administrateModsManufacturer' => [
        'type' => 2,
        'description' => 'Администрирование модуля "Бренды на главной"',
        'children' => [
            'BViewModsManufacturer',
            'BCreateModsManufacturer',
            'BUpdateModsManufacturer',
            'BDeleteModsManufacturer',
        ],
    ],
    'BViewModsManufacturer' => [
        'type' => 2,
        'description' => 'Просмотр модуля "Бренды на главной"',
    ],
    'BCreateModsManufacturer' => [
        'type' => 2,
        'description' => 'Создание записей модуля "Бренды на главной"',
    ],
    'BUpdateModsManufacturer' => [
        'type' => 2,
        'description' => 'Изменение записей модуля "Бренды на главной"',
    ],
    'BDeleteModsManufacturer' => [
        'type' => 2,
        'description' => 'Удаление записей модуля "Бренды на главной"',
    ],
    'contentManagerModsManufacturer' => [
        'type' => 2,
        'description' => 'Доступ к записям модуля "Бренды на главной"',
        'children' => [
            'BViewModsManufacturer',
            'BCreateModsManufacturer',
            'BUpdateModsManufacturer',
            'BDeleteModsManufacturer',
        ],
    ],
    'administrateModsJob' => [
        'type' => 2,
        'description' => 'Администрирование модуля "Вакансии"',
        'children' => [
            'BViewModsJob',
            'BCreateModsJob',
            'BUpdateModsJob',
            'BDeleteModsJob',
        ],
    ],
    'BViewModsJob' => [
        'type' => 2,
        'description' => 'Просмотр модуля "Вакансии"',
    ],
    'BCreateModsJob' => [
        'type' => 2,
        'description' => 'Создание записей модуля "Вакансии"',
    ],
    'BUpdateModsJob' => [
        'type' => 2,
        'description' => 'Изменение записей модуля "Вакансии"',
    ],
    'BDeleteModsJob' => [
        'type' => 2,
        'description' => 'Удаление записей модуля "Вакансии"',
    ],
    'hrModsJob' => [
        'type' => 2,
        'description' => 'Администрирование модуля "Вакансии"',
        'children' => [
            'BViewModsJob',
            'BCreateModsJob',
            'BUpdateModsJob',
            'BDeleteModsJob',
        ],
    ],
    'administrateModsWorker' => [
        'type' => 2,
        'description' => 'Администрирование модуля "Сотрудники"',
        'children' => [
            'BViewModsWorker',
            'BCreateModsWorker',
            'BUpdateModsWorker',
            'BDeleteModsWorker',
        ],
    ],
    'BViewModsWorker' => [
        'type' => 2,
        'description' => 'Просмотр модуля "Сотрудники"',
    ],
    'BCreateModsWorker' => [
        'type' => 2,
        'description' => 'Создание записей модуля "Сотрудники"',
    ],
    'BUpdateModsWorker' => [
        'type' => 2,
        'description' => 'Изменение записей модуля "Сотрудники"',
    ],
    'BDeleteModsWorker' => [
        'type' => 2,
        'description' => 'Удаление записей модуля "Сотрудники"',
    ],
    'contentManagerModsWorker' => [
        'type' => 2,
        'description' => 'Доступ к записям модуля "Сотрудники"',
        'children' => [
            'BViewModsWorker',
            'BCreateModsWorker',
            'BUpdateModsWorker',
            'BDeleteModsWorker',
        ],
    ],
    'administrateModsNews' => [
        'type' => 2,
        'description' => 'Администрирование модуля "Новости на главной"',
        'children' => [
            'BViewModsNews',
            'BCreateModsNews',
            'BUpdateModsNews',
            'BDeleteModsNews',
        ],
    ],
    'BViewModsNews' => [
        'type' => 2,
        'description' => 'Просмотр модуля "Новости на главной"',
    ],
    'BCreateModsNews' => [
        'type' => 2,
        'description' => 'Создание записей модуля "Новости на главной"',
    ],
    'BUpdateModsNews' => [
        'type' => 2,
        'description' => 'Изменение записей модуля "Новости на главной"',
    ],
    'BDeleteModsNews' => [
        'type' => 2,
        'description' => 'Удаление записей модуля "Новости на главной"',
    ],
    'contentManagerModsNews' => [
        'type' => 2,
        'description' => 'Доступ к записям модуля "Новости на главной"',
        'children' => [
            'BViewModsNews',
            'BCreateModsNews',
            'BUpdateModsNews',
            'BDeleteModsNews',
        ],
    ],
    'administrateModsSlides' => [
        'type' => 2,
        'description' => 'Администрирование модуля "Слайдер на главной"',
        'children' => [
            'BViewModsSlides',
            'BCreateModsSlides',
            'BUpdateModsSlides',
            'BDeleteModsSlides',
        ],
    ],
    'BViewModsSlides' => [
        'type' => 2,
        'description' => 'Просмотр модуля "Слайдер на главной"',
    ],
    'BCreateModsSlides' => [
        'type' => 2,
        'description' => 'Создание записей модуля "Слайдер на главной"',
    ],
    'BUpdateModsSlides' => [
        'type' => 2,
        'description' => 'Изменение записей модуля "Слайдер на главной"',
    ],
    'BDeleteModsSlides' => [
        'type' => 2,
        'description' => 'Удаление записей модуля "Слайдер на главной"',
    ],
    'contentManagerModsSlides' => [
        'type' => 2,
        'description' => 'Доступ к записям модуля "Слайдер на главной"',
        'children' => [
            'BViewModsSlides',
            'BCreateModsSlides',
            'BUpdateModsSlides',
            'BDeleteModsSlides',
        ],
    ],
    'administrateModsReview' => [
        'type' => 2,
        'description' => 'Администрирование модуля "Отзывы о компании"',
        'children' => [
            'BViewModsReview',
            'BCreateModsReview',
            'BUpdateModsReview',
            'BDeleteModsReview',
        ],
    ],
    'BViewModsReview' => [
        'type' => 2,
        'description' => 'Просмотр модуля "Отзывы о компании"',
    ],
    'BCreateModsReview' => [
        'type' => 2,
        'description' => 'Создание записей модуля "Отзывы о компании"',
    ],
    'BUpdateModsReview' => [
        'type' => 2,
        'description' => 'Изменение записей модуля "Отзывы о компании"',
    ],
    'BDeleteModsReview' => [
        'type' => 2,
        'description' => 'Удаление записей модуля "Отзывы о компании"',
    ],
    'contentManagerModsReview' => [
        'type' => 2,
        'description' => 'Доступ к записям модуля "Отзывы о компании"',
        'children' => [
            'BViewModsReview',
            'BCreateModsReview',
            'BUpdateModsReview',
            'BDeleteModsReview',
        ],
    ],
    'administrateModsSeo' => [
        'type' => 2,
        'description' => 'Администрирование модуля "SEO"',
        'children' => [
            'BViewModsSeo',
            'BCreateModsSeo',
            'BUpdateModsSeo',
            'BDeleteModsSeo',
        ],
    ],
    'BViewModsSeo' => [
        'type' => 2,
        'description' => 'Просмотр модуля "SEO"',
    ],
    'BCreateModsSeo' => [
        'type' => 2,
        'description' => 'Создание записей модуля "SEO"',
    ],
    'BUpdateModsSeo' => [
        'type' => 2,
        'description' => 'Изменение записей модуля "SEO"',
    ],
    'BDeleteModsSeo' => [
        'type' => 2,
        'description' => 'Удаление записей модуля "SEO"',
    ],
    'hrModsSeo' => [
        'type' => 2,
        'description' => 'Администрирование модуля "SEO"',
        'children' => [
            'BViewModsSeo',
            'BCreateModsSeo',
            'BUpdateModsSeo',
            'BDeleteModsSeo',
        ],
    ],
    'BViewReviews' => [
        'type' => 2,
        'description' => 'can view reviews list',
    ],
    'BUpdateReviews' => [
        'type' => 2,
        'description' => 'can update reviews',
    ],
    'BDeleteReviews' => [
        'type' => 2,
        'description' => 'can delete reviews',
    ],
    'BCreateReviews' => [
        'type' => 2,
        'description' => 'can create reviews',
    ],
    'BViewModsReviews' => [
        'type' => 2,
        'description' => 'Доступ к модулю "Отзывы о товарах"',
    ],
];
