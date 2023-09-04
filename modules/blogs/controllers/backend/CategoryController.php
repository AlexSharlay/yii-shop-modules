<?php

namespace common\modules\blogs\controllers\backend;

use backend\components\Controller;

use common\modules\blogs\models\backend\Category;
use common\modules\blogs\models\backend\CategorySearch;
use common\components\fileapi\actions\UploadAction as FileAPIUpload;
use common\components\imperavi\actions\GetAction as ImperaviGet;
use common\components\imperavi\actions\UploadAction as ImperaviUpload;

use Yii;
use yii\filters\VerbFilter;
use yii\web\HttpException;

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
                'roles' => ['BViewBlogsCategory']
            ]
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['create'],
            'roles' => ['BCreateBlogsCategory']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['update'],
            'roles' => ['BUpdateBlogsCategory']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['delete', 'batch-delete'],
            'roles' => ['BDeleteBlogsCategory']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['imperavi-get', 'imperavi-image-upload', 'imperavi-file-upload', 'fileapi-upload'],
            'roles' => ['BCreateBlogsCategory', 'BUpdateBlogsCategory']
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
            // Required
            [['title', 'alias', 'content'], 'required'],
            // Trim
            [['title', 'alias', 'content'], 'trim'],
            // Status
            [
                'status_id',
                'in',
                'range' => array_keys(self::getStatusArray())
            ]
        ];
    }

    public function actions()
    {
        return [
            'imperavi-get' => [
                'class' => ImperaviGet::className(),
                'url' => $this->module->contentUrlCategory,
                'path' => $this->module->contentPathCategory
            ],
            'imperavi-image-upload' => [
                'class' => ImperaviUpload::className(),
                'url' => $this->module->contentUrlCategory,
                'path' => $this->module->contentPathCategory
            ],
            'imperavi-file-upload' => [
                'class' => ImperaviUpload::className(),
                'url' => $this->module->fileUrlCategory,
                'path' => $this->module->filePathCategory,
                'uploadOnlyImage' => false
            ],
            'fileapi-upload' => [
                'class' => FileAPIUpload::className(),
                'path' => $this->module->imagesTempPathCategory
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
        $statusArray = Category::getStatusArray();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'statusArray' => $statusArray
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
            $searchModel = new CategorySearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $statusArray = Category::getStatusArray();
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'statusArray' => $statusArray
            ]);
        } else {
            $statusArray = Category::getStatusArray();
            return $this->render('create', [
                'model' => $model,
                'statusArray' => $statusArray
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
            return $this->refresh();
        } else {
            $statusArray = Category::getStatusArray();
            return $this->render('update', [
                'model' => $model,
                'statusArray' => $statusArray
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
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404);
        }
    }
}
