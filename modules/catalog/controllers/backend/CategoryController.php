<?php

namespace common\modules\catalog\controllers\backend;

use backend\components\Controller;

use common\modules\catalog\models\backend\Category;
use common\modules\catalog\models\backend\CategorySearch;

use common\components\fileapi\actions\UploadAction as FileAPIUpload;
use common\components\imperavi\actions\GetAction as ImperaviGet;
use common\components\imperavi\actions\UploadAction as ImperaviUpload;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index'],
                'roles' => ['BViewCatalogCategory']
            ]
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['view'],
            'roles' => ['BViewCatalogCategory']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['create'],
            'roles' => ['BCreateCatalogCategory']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['update'],
            'roles' => ['BUpdateCatalogCategory']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['delete', 'batch-delete'],
            'roles' => ['BDeleteCatalogCategory']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['imperavi-get', 'imperavi-image-upload', 'imperavi-file-upload', 'fileapi-upload'],
            'roles' => ['BCreateCatalogCategory', 'BUpdateCatalogCategory']
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'index' => ['get'],
                'create' => ['get', 'post'],
                'update' => ['get', 'put', 'post'],
                'delete' => ['get', 'post', 'delete'],
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
                'url' => $this->module->contentUrl,
                'path' => $this->module->contentPath
            ],
            'imperavi-image-upload' => [
                'class' => ImperaviUpload::className(),
                'url' => $this->module->contentUrl,
                'path' => $this->module->contentPath
            ],
            'imperavi-file-upload' => [
                'class' => ImperaviUpload::className(),
                'url' => $this->module->fileUrl,
                'path' => $this->module->filePath,
                'uploadOnlyImage' => false
            ],
            'fileapi-upload' => [
                'class' => FileAPIUpload::className(),
                'path' => $this->module->categoryTempPath
            ]
        ];
    }

    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $publishedArray = Category::getPublishedArray();
        $showInMenuArray = Category::getShowInMenuArray();
        $parentFilterArray = Category::getParentFilterArray();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'publishedArray' => $publishedArray,
            'showInMenuArray' => $showInMenuArray,
            'parentFilterArray' => $parentFilterArray,
        ]);
    }

    /**
     * Displays a single Category model.
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
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Category();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $publishedArray = Category::getPublishedArray();
            $showInMenuArray = Category::getShowInMenuArray();
            $parentArray =  Category::getCategoriesList();
            $parentFilterArray = Category::getParentFilterArray();
            return $this->render('create', [
                'model' => $model,
                'publishedArray' => $publishedArray,
                'showInMenuArray' => $showInMenuArray,
                'parentArray' => $parentArray,
                'parentFilterArray' => $parentFilterArray,
            ]);
        }
    }

    /**
     * Updates an existing Category model.
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
            $publishedArray = Category::getPublishedArray();
            $showInMenuArray = Category::getShowInMenuArray();
            $parentArray =  Category::getCategoriesList();
            $parentFilterArray =  Category::getParentFilterArray();
            return $this->render('update', [
                'model' => $model,
                'publishedArray' => $publishedArray,
                'showInMenuArray' => $showInMenuArray,
                'parentArray' => $parentArray,
                'parentFilterArray' => $parentFilterArray,
            ]);
        }
    }

    /**
     * Deletes an existing Category model.
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
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
