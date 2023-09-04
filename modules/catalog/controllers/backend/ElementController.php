<?php

namespace common\modules\catalog\controllers\backend;

use backend\components\Controller;

use common\modules\catalog\components\Helper;
use common\modules\catalog\models\backend\Category;
use common\modules\catalog\models\backend\Manufacturer;
use common\modules\catalog\models\backend\Photo;
use common\modules\catalog\models\backend\PhotoInsert;
use common\modules\catalog\models\backend\ComplectRel;
use common\modules\catalog\models\backend\ModelRel;
use common\modules\catalog\models\backend\KitRel;

use Yii;
use common\modules\catalog\models\backend\Element;
use common\modules\catalog\models\backend\ElementSearch;
use yii\db\Query;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\components\fileapi\actions\UploadAction as FileAPIUpload;
use common\components\imperavi\actions\GetAction as ImperaviGet;
use common\components\imperavi\actions\UploadAction as ImperaviUpload;


/**
 * ElementController implements the CRUD actions for Element model.
 */
class ElementController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index'],
                'roles' => ['BViewCatalogElement']
            ]
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['view', 'search-elements-for-complect', 'search-elements-for-model', 'search-elements-for-kit', 'get-kits', 'parse', 'parse-update', 'fields-get', 'fields-set'],
            'roles' => ['BViewCatalogElement']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['create', 'add-to-complect', 'add-to-model', 'add-to-kit'],
            'roles' => ['BCreateCatalogElement']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['foto'],
            'roles' => ['BCreateCatalogElement']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['update'],
            'roles' => ['BUpdateCatalogElement']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['cover'],
            'roles' => ['BUpdateCatalogElement']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['sort'],
            'roles' => ['BUpdateCatalogElement']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['delete-photo'],
            'roles' => ['BUpdateCatalogElement']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['delete', 'batch-delete', 'delete-from-complect', 'delete-from-model', 'delete-from-kit'],
            'roles' => ['BDeleteCatalogElement']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['imperavi-get', 'imperavi-image-upload', 'imperavi-file-upload', 'fileapi-upload'],
            'roles' => ['BCreateCatalogElement', 'BUpdateCatalogElement']
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'index' => ['get'],
                'create' => ['get', 'post'],
                'update' => ['get', 'put', 'post'],
                'delete' => ['post', 'delete'],
                'batch-delete' => ['post', 'delete']
            ]
        ];

        return $behaviors;
    }

    public function rules()
    {
        return [
            [
                'published',
                'in',
                'range' => array_keys(self::getPublishedArray())
            ]
        ];
    }

    public function actions()
    {
        return [
            'imperavi-get' => [
                'class' => ImperaviGet::className(),
                'url' => $this->module->contentElementUrl,
                'path' => $this->module->contentElementPath
            ],
            'imperavi-image-upload' => [
                'class' => ImperaviUpload::className(),
                'url' => $this->module->contentElementUrl,
                'path' => $this->module->contentElementPath
            ],
            'imperavi-file-upload' => [
                'class' => ImperaviUpload::className(),
                'url' => $this->module->fileElementUrl,
                'path' => $this->module->fileElementPath,
                'uploadOnlyImage' => false
            ],
            'fileapi-upload' => [
                'class' => FileAPIUpload::className(),
                'path' => $this->module->elementTempPath
            ]
        ];
    }

    // Комплекты

    public function actionSearchElementsForComplect($str)
    {
        return ComplectRel::SearchElementsForComplect($str);
    }

    public function actionAddToComplect($id_complect, $id_element)
    {
        ComplectRel::AddToComplect($id_complect, $id_element);
        return $this->redirect(['/catalog/element/update', 'id' => $id_complect, 'tab' => 'complect']);
    }

    public function actionDeleteFromComplect($id_complect, $id_element)
    {
        ComplectRel::DeleteFromComplect($id_complect, $id_element);
        return $this->redirect(['/catalog/element/update', 'id' => $id_complect, 'tab' => 'complect']);
    }

    // Модели

    public function actionSearchElementsForModel($str)
    {
        return ModelRel::SearchElementsForModel($str);
    }

    public function actionAddToModel($id_model, $id_element)
    {
        ModelRel::AddToModel($id_model, $id_element);
        return $this->redirect(['/catalog/element/update', 'id' => $id_model, 'tab' => 'model']);
    }

    public function actionDeleteFromModel($id_model, $id_element)
    {
        ModelRel::DeleteFromModel($id_model, $id_element);
        return $this->redirect(['/catalog/element/update', 'id' => $id_model, 'tab' => 'model']);
    }

    // Наборы

    public function actionSearchElementsForKit($str)
    {
        return KitRel::SearchElementsForKit($str);
    }

    public function actionAddToKit($id_element_parent, $id_element_children, $id_kit)
    {
        return KitRel::AddToKit($id_element_parent, $id_element_children, $id_kit);
    }

    public function actionDeleteFromKit($id_kit)
    {
        return KitRel::DeleteFromKit($id_kit);
    }

    public function actionGetKits($id)
    {
        return KitRel::getKits($id);
    }


    /**
     * Lists all Element models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ElementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $publishedArray = Element::getPublishedArray();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'publishedArray' => $publishedArray,
        ]);
    }

    /**
     * Displays a single Element model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Element model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $modelElement = new Element();
        $modelPhoto = new Photo();

        if ($modelElement->load(Yii::$app->request->post()) && $modelElement->save()) {
            $id = Element::createElement($modelElement, $modelPhoto);
            return $this->redirect(['update', 'id' => $id]);
        } else {
            $publishedArray = Element::getPublishedArray();
            $categoryArray = Element::getCategoriesList();
            $manufacturerArray = Element::getManufacturersList();
            $measurementArray = Element::getMeasurementsList();
            return $this->render('create', [
                'model' => $modelElement,
                'modelPhoto' => $modelPhoto,
                'publishedArray' => $publishedArray,
                'categoryArray' => $categoryArray,
                'manufacturerArray' => $manufacturerArray,
                'measurementArray' => $measurementArray,
            ]);
        }

    }

    /**
     * Updates an existing Element model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {

        $modelElement = $this->findModel($id);
        $modelPhoto = new Photo();

        if ($modelElement->load(Yii::$app->request->post())) { // Нужно отредактировать код (первая проверка)
//--begin
            if (array_keys(Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId()))[0] == 'contentTileViewUpdate') {
                if (!in_array($modelElement->toArray()['id_category'], [77, 81])) {
                    Yii::$app->session->setFlash('danger', 'Вы не имеете права редактировать этот продукт.');
                    return $this->refresh();
                }
            }
//--end
            if ($modelElement->save()) {
                $id_element = $modelElement->id;
                $id = Element::updateElement($id_element);
                return $this->redirect(['update', 'id' => $id]);
            } else {
                print_r($modelElement->getErrors());
            }
        } else {
            $publishedArray = Element::getPublishedArray();
            $categoryArray = Element::getCategoriesList();
            $manufacturerArray = Element::getManufacturersList();
            $measurementArray = Element::getMeasurementsList();
            $photos = PhotoInsert::find()->where(['id_element' => $id])->orderBy(['sort' => SORT_ASC])->all();
            $tab[Yii::$app->getRequest()->getQueryParam('tab')] = true;
            $complects = ComplectRel::ElementInComplect($id);
            $models = ModelRel::ElementInModel($id);

            $parent =  ModelRel::find()
                ->select('mr.id_element_parent')
                ->from('{{%catalog_model_rel}} mr')
//                ->leftJoin('{{%catalog_element}} e',' e.id = mr.id_element_children')
                ->where('mr.id_element_children = :id', [':id' => $id])
                ->asArray()->one();
            if (!empty($parent)) {
                $parent = $parent['id_element_parent'];
            } else {
                $parent = '';
            }

            return $this->render('update', [
                'model' => $modelElement,
                'modelPhoto' => $modelPhoto,
                'publishedArray' => $publishedArray,
                'categoryArray' => $categoryArray,
                'manufacturerArray' => $manufacturerArray,
                'measurementArray' => $measurementArray,
                'photos' => $photos,
                'tab' => $tab,
                'complects' => $complects,
                'models' => $models,
                'parent' => $parent,
            ]);

        }
    }


    /**
     * Картинки. Назначение обложки
     */
    public function actionCover($id)
    {
        //@todo: проверки
        Element::coverSet($id);
    }

    /**
     * Картинки. Сортировка
     */
    public function actionSort($json)
    {
        //@todo: проверки
        Element::coverSort($json);
    }

    /**
     * Картинки. Удаление
     */
    public function actionDeletePhoto($id)
    {
        //@todo: проверки
        //@todo: есть баг.
        // 3 картинки. средняя обложка. удаляем её, показывается что обложка первая, обновляем, последняя. Возможно для воспроизведения нужно загрузить картинки за 2 раза
        Element::deletePhoto($id);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionParse()
    {
        return \common\modules\catalog\components\Tools::parse([
            'url' => Yii::$app->request->post('url'),
            'create' => 1,
            'id_category' => Yii::$app->request->post('id_category'),
            'id_measurement' => Yii::$app->request->post('id_measurement'),
        ]);
    }

    public function actionParseUpdate()
    {
        $id_category =  Yii::$app->request->post('id_category');
//////////////
        $id_cat = (new Query())
            ->select('c1.id as id1, c1.parent_filter, c2.id as id2')
            ->from('{{%catalog_category}} c1')
            ->leftJoin('{{%catalog_category}} c2', 'c1.id_parent = c2.id')
            ->where(['c1.id' => $id_category])
            ->one();

        if (isset($id_cat['id2']) AND $id_cat['parent_filter'] == 1) {
            $id_category = $id_cat['id2'];
        }

//        if (in_array($id_category, array(93,94,95,96,97,98))) {//////////////////////////временно, парсинг
//            $id_category = 5;////////////////////////
//        }
//        if (in_array($id_category, array(99,100,101,102,103))) {//////////////////////////временно, парсинг
//            $id_category = 26;////////////////////////
//        }
//        if (in_array($id_category, array(120,121))) {//////////////////////////временно, парсинг
//            $id_category = 29;////////////////////////
//        }
//        if (in_array($id_category, array(122,123,124,125,126,127))) {//////////////////////////временно, парсинг
//            $id_category = 18;////////////////////////
//        }
//        if (in_array($id_category, array(180,181,182,183))) {//////////////////////////временно, парсинг
//            $id_category = 23;////////////////////////
//        }
//        if (in_array($id_category, array(184,185,186,187))) {//////////////////////////временно, парсинг
//            $id_category = 14;////////////////////////
//        }
//        if (in_array($id_category, array(188,189,190,191,192))) {//////////////////////////временно, парсинг
//            $id_category = 32;////////////////////////
//        }
//        if (in_array($id_category, array(228,229))) {//////////////////////////временно, парсинг
//            $id_category = 20;////////////////////////
//        }


        return \common\modules\catalog\components\Tools::parse([
            'url' => Yii::$app->request->post('url'),
            'create' => 0,
            'id_element' => Yii::$app->request->post('id_element'),
//            'id_category' => Yii::$app->request->post('id_category'),
            'id_category' => $id_category,
            'id_measurement' => (Yii::$app->request->post('id_measurement') > 0) ? Yii::$app->request->post('id_measurement') : 0,
        ]);
    }

    /**
     * Получить поля товара
     */
    public function actionFieldsGet($id)
    {
        \common\modules\shop\components\Helper::jsonHeader();
        echo json_encode(Element::FieldsToJson($id));
        //echo '[{"name":"Основные","fields":[{"name":"Объём","field":{"type":1,"type_dop":"default","sign":"л.","text":"(корпус серый)","value":"10","check":[{}]}},{"name":"Время работы","field":{"type":1,"type_dop":"time","sign":"?","text":"(ххх)","value":"1 неделя, 3 суток - 2 часа","check":[{}]}},{"name":"Вес","field":{"type":1,"type_dop":"mass","text":"(корпус серый)","value":"10","check":[{"id":"g","val":"g","active":0},{"id":"kg","val":"kg","active":1},{"id":"t","val":"t","active":0}]}},{"name":"Оперативной памяти","field":{"type":1,"type_dop":"kb","text":"(корпус серый)","value":"4","check":[{"id":"kb","val":"kb","active":0},{"id":"gb","val":"gb","active":0},{"id":"tb","val":"tb","active":1}]}},{"name":"Шнур","field":{"type":1,"type_dop":"with_one","sign":"","text":"(корпус серый)","check":[{"val":"none","active":0},{"val":"yes","active":0},{"val":"no","active":1}]}},{"name":"Цвет","field":{"type":3,"sign":"px","text":"(корпус серый)","check":[{"id":0,"name":"белый","active":true},{"id":1,"name":"красный","active":true},{"id":2,"name":"черный","active":false}]}},{"name":"Описание","field":{"type":5,"sign":"px","text":"(корпус серый)","value":"text"}},{"name":"Разрешение","field":{"type":6,"delemiter":"X","sign":"px","text":"(корпус серый)","value":{"one":"100","two":"200"}}}]}]';
    }

    /**
     * Изменить значение полей товару
     */
    public function actionFieldsSet()
    {
        /*
        $id = 1947;
        $json = '[{"name":"Общая информация","fields":[{"name":"Описание","field":{"type":"5","sign":"","text":"вапвапвапвап","value":"qw3erfweg"}},{"name":"Страна производства","field":{"type":"3","sign":null,"check":[{"id":"10","name":"Беларусь","active":0},{"id":"20","name":"Германия","active":0},{"id":"30","name":"Испания","active":0},{"id":"40","name":"Китай","active":0},{"id":"50","name":"Польша","active":0},{"id":"60","name":"Россия","active":0},{"id":"70","name":"Чехия","active":0},{"id":"80","name":"Украина","active":0}],"text":null}}]},{"name":"Основные","fields":[{"name":"Материал","field":{"type":"3","sign":null,"text":"","check":[{"id":"1","name":"акрил","active":0},{"id":"2","name":"искусственный камень","active":0},{"id":"3","name":"сталь","active":0},{"id":"4","name":"чугун","active":1}]}},{"name":"Форма","field":{"type":"3","sign":null,"check":[{"id":"1","name":"нестандартная","active":0},{"id":"2","name":"прямая","active":0},{"id":"3","name":"угловая","active":0}],"text":null}},{"name":"Отдельностоящая","field":{"type":"1","type_dop":"with_one","sign":"","check":[{"val":"none","active":0},{"val":"Есть","active":1},{"val":"Нет","active":0}],"text":"","value":""}},{"name":"Толщина стенок","field":{"type":"1","sign":"мм","type_dop":"default","text":null,"value":null}},{"name":"Полезный объём","field":{"type":"1","sign":"л","type_dop":"default","text":null,"value":null}},{"name":"Длина","field":{"type":"1","sign":"см","type_dop":"default","text":"","value":170}},{"name":"Ширина","field":{"type":"1","sign":"см","type_dop":"default","text":"","value":76}},{"name":"Высота","field":{"type":"1","sign":"см","type_dop":"default","text":"","value":55}},{"name":"Глубина ванны","field":{"type":"1","sign":"см","type_dop":"default","text":null,"value":null}},{"name":"Вес","field":{"type":"1","sign":"кг","type_dop":"mass","text":null,"value":null}},{"name":"Массажная система","field":{"type":"1","type_dop":"with_one","sign":"","check":[{"val":"none","active":0},{"val":"Есть","active":0},{"val":"Нет","active":1}],"text":"","value":""}},{"name":"Хромотерапия","field":{"type":"1","type_dop":"with_one","sign":"","check":[{"val":"none","active":0},{"val":"Есть","active":0},{"val":"Нет","active":1}],"text":"","value":""}},{"name":"Количество мест","field":{"type":"1","sign":"","type_dop":"default","text":"","value":1}},{"name":"Дезинфекция (озонирование)","field":{"type":"1","type_dop":"with_one","sign":"","check":[{"val":"none","active":0},{"val":"Есть","active":0},{"val":"Нет","active":1}],"text":"","value":""}},{"name":"Противоскользящее покрытие дна","field":{"type":"1","type_dop":"with_one","sign":"","check":[{"val":"none","active":0},{"val":"Есть","active":0},{"val":"Нет","active":1}],"text":"","value":""}},{"name":"Цвет","field":{"type":"3","sign":null,"check":[{"id":"1","name":"белый","active":0}],"text":null}}]},{"name":"Функциональные особенности","fields":[{"name":"Смеситель","field":{"type":"1","type_dop":"with_one","sign":"","check":[{"val":"none","active":0},{"val":"Есть","active":1},{"val":"Нет","active":0}],"text":"","value":""}},{"name":"Верхний (тропический) душ","field":{"type":"1","type_dop":"with_one","sign":"","check":[{"val":"none","active":0},{"val":"Есть","active":0},{"val":"Нет","active":1}],"text":"","value":""}},{"name":"Ручной душ (душевая лейка)","field":{"type":"1","type_dop":"with_one","sign":"","check":[{"val":"none","active":0},{"val":"Есть","active":0},{"val":"Нет","active":1}],"text":"","value":""}},{"name":"Полки","field":{"type":"1","type_dop":"with_one","sign":"","check":[{"val":"none","active":0},{"val":"Есть","active":0},{"val":"Нет","active":1}],"text":"","value":""}},{"name":"Подсветка","field":{"type":"1","type_dop":"with_one","sign":"","check":[{"val":"none","active":0},{"val":"Есть","active":0},{"val":"Нет","active":1}],"text":"","value":""}},{"name":"Радио","field":{"type":"1","type_dop":"with_one","sign":"","check":[{"val":"none","active":0},{"val":"Есть","active":0},{"val":"Нет","active":1}],"text":"","value":""}},{"name":"Телевизор","field":{"type":"1","type_dop":"with_one","sign":"","check":[{"val":"none","active":0},{"val":"Есть","active":0},{"val":"Нет","active":1}],"text":"","value":""}}]},{"name":"Гидромассаж","fields":[{"name":"Турбо-форсунки","field":{"type":"1","type_dop":"with_one","sign":"","check":[{"val":"none","active":0},{"val":"Есть","active":0},{"val":"Нет","active":1}],"text":"","value":""}},{"name":"Микро-форсунки","field":{"type":"1","type_dop":"with_one","sign":"","check":[{"val":"none","active":0},{"val":"Есть","active":0},{"val":"Нет","active":1}],"text":"","value":""}},{"name":"Массаж поясничного отдела","field":{"type":"1","type_dop":"with_one","sign":"","check":[{"val":"none","active":0},{"val":"Есть","active":0},{"val":"Нет","active":1}],"text":"","value":""}},{"name":"Массаж стоп","field":{"type":"1","type_dop":"with_one","sign":"","check":[{"val":"none","active":0},{"val":"Есть","active":0},{"val":"Нет","active":1}],"text":"","value":""}},{"name":"Аэромассаж","field":{"type":"1","type_dop":"with_one","sign":"","check":[{"val":"none","active":0},{"val":"Есть","active":0},{"val":"Нет","active":1}],"text":"","value":""}},{"name":"Подогрев воздуха","field":{"type":"1","type_dop":"with_one","sign":"","check":[{"val":"none","active":1},{"val":"Есть","active":0},{"val":"Нет","active":0}]}},{"name":"Регулятор подачи воздуха","field":{"type":"1","type_dop":"with_one","sign":"","check":[{"val":"none","active":1},{"val":"Есть","active":0},{"val":"Нет","active":0}]}},{"name":"Таймер","field":{"type":"1","type_dop":"with_one","sign":"","check":[{"val":"none","active":1},{"val":"Есть","active":0},{"val":"Нет","active":0}]}},{"name":"Датчик уровня воды","field":{"type":"1","type_dop":"with_one","sign":"","check":[{"val":"none","active":1},{"val":"Есть","active":0},{"val":"Нет","active":0}]}}]},{"name":"Комплектация","fields":[{"name":"Душевая шторка","field":{"type":"1","type_dop":"with_one","sign":"","check":[{"val":"none","active":0},{"val":"Есть","active":0},{"val":"Нет","active":1}],"text":"","value":""}},{"name":"Декоративный экран","field":{"type":"1","type_dop":"with_one","sign":"","check":[{"val":"none","active":0},{"val":"Есть","active":0},{"val":"Нет","active":1}],"text":"","value":""}},{"name":"Ножки","field":{"type":"1","type_dop":"with_one","sign":"","check":[{"val":"none","active":0},{"val":"Есть","active":1},{"val":"Нет","active":0}],"text":"","value":""}},{"name":"Опора","field":{"type":"1","type_dop":"with_one","sign":"","check":[{"val":"none","active":0},{"val":"Есть","active":0},{"val":"Нет","active":1}],"text":"","value":""}},{"name":"Рама (каркас)","field":{"type":"1","type_dop":"with_one","sign":"","check":[{"val":"none","active":0},{"val":"Есть","active":0},{"val":"Нет","active":1}],"text":"","value":""}},{"name":"Подголовник","field":{"type":"1","type_dop":"with_one","sign":"","check":[{"val":"none","active":0},{"val":"Есть","active":0},{"val":"Нет","active":1}],"text":"","value":""}},{"name":"Ручки","field":{"type":"1","type_dop":"with_one","sign":"","check":[{"val":"none","active":0},{"val":"Есть","active":0},{"val":"Нет","active":1}],"text":"","value":""}},{"name":"Сифон (слив-перелив)","field":{"type":"1","type_dop":"with_one","sign":"","check":[{"val":"none","active":0},{"val":"Есть","active":1},{"val":"Нет","active":0}],"text":"","value":""}}]}]';
        $json = json_decode($json);
        */
        $id = Yii::$app->request->post('id');
        $json = json_decode(Yii::$app->request->post('json'));

        Element::FieldsUpdate($id, $json);
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (in_array($action->id, ['parse-update', 'fields-set'])) {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    protected function findModel($id)
    {
        if (($model = Element::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}