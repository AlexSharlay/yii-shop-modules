<?php

namespace common\modules\catalog\controllers\backend;

use backend\components\Controller;

use common\modules\catalog\models\backend\Manufacturer;
use common\modules\catalog\models\backend\ManufacturerSearch;
use common\modules\catalog\models\backend\Country;

use common\components\fileapi\actions\UploadAction as FileAPIUpload;
use common\components\imperavi\actions\GetAction as ImperaviGet;
use common\components\imperavi\actions\UploadAction as ImperaviUpload;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ManufacturerController implements the CRUD actions for Manufacturer model.
 */
class ManufacturerController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index'],
                'roles' => ['BViewCatalogManufacturer']
            ]
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['view'],
            'roles' => ['BViewCatalogManufacturer']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['create'],
            'roles' => ['BCreateCatalogManufacturer']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['update', 'search-country', 'add-country', 'delete-country'],
            'roles' => ['BUpdateCatalogManufacturer']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['delete', 'batch-delete'],
            'roles' => ['BDeleteCatalogManufacturer']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['imperavi-get', 'imperavi-image-upload', 'imperavi-file-upload', 'fileapi-upload'],
            'roles' => ['BCreateCatalogManufacturer', 'BUpdateCatalogManufacturer']
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
                'url' => $this->module->contentManufacturerUrl,
                'path' => $this->module->contentManufacturerPath
            ],
            'imperavi-image-upload' => [
                'class' => ImperaviUpload::className(),
                'url' => $this->module->contentManufacturerUrl,
                'path' => $this->module->contentManufacturerPath
            ],
            'imperavi-file-upload' => [
                'class' => ImperaviUpload::className(),
                'url' => $this->module->fileManufacturerUrl,
                'path' => $this->module->fileManufacturerPath,
                'uploadOnlyImage' => false
            ],
            'fileapi-upload' => [
                'class' => FileAPIUpload::className(),
                'path' => $this->module->manufacturerTempPath
            ]
        ];
    }


    // Страны

    public function actionSearchCountry($str) {
        return Manufacturer::SearchCountry($str);
    }

    public function actionAddCountry($id_manufacturer, $id_country) {
        Manufacturer::AddCountry($id_manufacturer, $id_country);
        return $this->redirect(['/catalog/manufacturer/update', 'id' => $id_manufacturer]);
    }

    public function actionDeleteCountry($id_manufacturer, $id_country) {
        Manufacturer::DeleteCountry($id_manufacturer, $id_country);
        return $this->redirect(['/catalog/manufacturer/update', 'id' => $id_manufacturer]);
    }

    /**
     * Lists all Manufacturer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ManufacturerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $publishedArray = Manufacturer::getPublishedArray();
        $perekupArray = Manufacturer::getPerekupArray();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'publishedArray' => $publishedArray,
            'perekupArray' => $perekupArray,
        ]);
    }

    /**
     * Displays a single Manufacturer model.
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
     * Creates a new Manufacturer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Manufacturer();
        $countryArray =  Country::getCountriesList();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $publishedArray = Manufacturer::getPublishedArray();
            $perekupArray = Manufacturer::getPerekupdArray();
            return $this->render('create', [
                'model' => $model,
                'countryArray' => $countryArray,
                'publishedArray' => $publishedArray,
                'perekupArray' => $perekupArray,
            ]);
        }
    }

    /**
     * Updates an existing Manufacturer model.
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
            $publishedArray = Manufacturer::getPublishedArray();
            $perekupArray = Manufacturer::getPerekupArray();
            $countryArray =  Country::getCountriesListByManufacturerId($id);
            return $this->render('update', [
                'publishedArray' => $publishedArray,
                'perekupArray' => $perekupArray,
                'model' => $model,
                'countryArray' => $countryArray,
                'id_manufacturer' => $id,
            ]);
        }
    }

    /**
     * Deletes an existing Manufacturer model.
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
     * Finds the Manufacturer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Manufacturer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Manufacturer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
