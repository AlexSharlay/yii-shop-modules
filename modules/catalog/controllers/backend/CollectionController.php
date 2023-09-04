<?php

namespace common\modules\catalog\controllers\backend;

use backend\components\Controller;

use common\modules\catalog\models\backend\Collection;
use common\modules\catalog\models\backend\CollectionRel;
use common\modules\catalog\models\backend\CollectionSearch;

use common\components\imperavi\actions\GetAction as ImperaviGet;
use common\components\imperavi\actions\UploadAction as ImperaviUpload;

use common\modules\catalog\models\backend\Element;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\data\ActiveDataProvider;

/**
 * CollectionController implements the CRUD actions for Collection model.
 */
class CollectionController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index'],
                'roles' => ['BViewCatalogCollection']
            ]
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['view', 'view-elements', 'search-elements'],
            'roles' => ['BViewCatalogCollection']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['create', 'add-element'],
            'roles' => ['BCreateCatalogCollection']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['update'],
            'roles' => ['BUpdateCatalogCollection']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['delete', 'batch-delete', 'delete-element'],
            'roles' => ['BDeleteCatalogCollection']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['imperavi-get', 'imperavi-image-upload', 'imperavi-file-upload', 'fileapi-upload'],
            'roles' => ['BCreateCatalogCollection', 'BUpdateCatalogCollection']
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
                'url' => $this->module->contentCollectionUrl,
                'path' => $this->module->contentCollectionPath
            ],
            'imperavi-image-upload' => [
                'class' => ImperaviUpload::className(),
                'url' => $this->module->contentCollectionUrl,
                'path' => $this->module->contentCollectionPath
            ],
            'imperavi-file-upload' => [
                'class' => ImperaviUpload::className(),
                'url' => $this->module->fileCollectionUrl,
                'path' => $this->module->fileCollectionPath,
                'uploadOnlyImage' => false
            ],
        ];
    }

    public function actionSearchElements($str) {
        return Collection::SearchElements($str);
    }

    public function actionAddElement($id_collection, $id_element) {
        Collection::AddElementToCollection($id_collection, $id_element);
        return $this->redirect(['/catalog/collection/view-elements', 'id' => $id_collection]);
    }

    public function actionDeleteElement($id_collection, $id_element) {
        Collection::DeleteElementFromCollection($id_collection, $id_element);
        return $this->redirect(['/catalog/collection/view-elements', 'id' => $id_collection]);
    }

    public function actionViewElements($id) {
        $model = Collection::ElementInCollection($id);
        return $this->render('view-collection', [
            'model' => $model,
            'collection_id' => $id
        ]);
    }

    /**
     * Lists all Collection models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CollectionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Collection model.
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
     * Creates a new Collection model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Collection();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Collection model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Collection model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Collection model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Collection the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Collection::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
