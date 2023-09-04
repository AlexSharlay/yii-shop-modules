<?php

namespace common\modules\mods\mods_manufacturer\controllers\backend;

use Yii;
use common\modules\mods\mods_manufacturer\models\backend\Manufacturer;
use common\modules\mods\mods_manufacturer\models\backend\ManufacturerSearch;
use backend\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\fileapi\actions\UploadAction as FileAPIUpload;

class ManufacturerController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index', 'view'],
                'roles' => ['BViewModsManufacturer']
            ]
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['create'],
            'roles' => ['BCreateModsManufacturer']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['update'],
            'roles' => ['BUpdateModsManufacturer']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['delete'],
            'roles' => ['BDeleteModsManufacturer']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['fileapi-upload'],
            'roles' => ['BCreateModsManufacturer', 'BUpdateModsManufacturer']
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'index' => ['get'],
                'delete' => ['post'],
            ]
        ];

        return $behaviors;
    }

    public function actions()
    {
        return [
            'fileapi-upload' => [
                'class' => FileAPIUpload::className(),
                'path' => $this->module->manufacturerTempPath
            ]
        ];
    }

    public function actionIndex()
    {
        $searchModel = new ManufacturerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Manufacturer();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

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

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Manufacturer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
