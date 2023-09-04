<?php

namespace common\modules\shop\controllers\backend;

use Yii;
use common\modules\shop\models\backend\Delivery;
use common\modules\shop\models\backend\DeliverySearch;
use common\modules\shop\models\backend\Payment;
use backend\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\components\imperavi\actions\GetAction as ImperaviGet;
use common\components\imperavi\actions\UploadAction as ImperaviUpload;

/**
 * DeliveryController implements the CRUD actions for Delivery model.
 */
class DeliveryController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index'],
                'roles' => ['BViewShopDelivery']
            ]
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['view'],
            'roles' => ['BViewShopDelivery']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['create',],
            'roles' => ['BCreateShopDelivery']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['update', 'search-payment', 'add-payment', 'delete-payment'],
            'roles' => ['BUpdateShopDelivery']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['delete'],
            'roles' => ['BDeleteShopDelivery']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['imperavi-get', 'imperavi-image-upload', 'imperavi-file-upload', 'fileapi-upload'],
            'roles' => ['BCreateShopDelivery', 'BUpdateShopDelivery']
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

    public function actions()
    {
        return [
            'imperavi-get' => [
                'class' => ImperaviGet::className(),
                'url' => $this->module->contentDeliveryUrl,
                'path' => $this->module->contentDeliveryPath
            ],
            'imperavi-image-upload' => [
                'class' => ImperaviUpload::className(),
                'url' => $this->module->contentDeliveryUrl,
                'path' => $this->module->contentDeliveryPath
            ],
            'imperavi-file-upload' => [
                'class' => ImperaviUpload::className(),
                'url' => $this->module->fileDeliveryUrl,
                'path' => $this->module->fileDeliveryPath,
                'uploadOnlyImage' => false
            ],
        ];
    }

    // Оплаты

    public function actionSearchPayment($str) {
        return Delivery::SearchPayment($str);
    }

    public function actionAddPayment($id_delivery, $id_payment) {
        Delivery::AddPayment($id_delivery, $id_payment);
        return $this->redirect(['/shop/delivery/update', 'id' => $id_delivery]);
    }

    public function actionDeletePayment($id_delivery, $id_payment) {
        Delivery::DeletePayment($id_delivery, $id_payment);
        return $this->redirect(['/shop/delivery/update', 'id' => $id_delivery]);
    }

    /**
     * Lists all Delivery models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new DeliverySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Delivery model.
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
     * Creates a new Delivery model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Delivery();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Delivery model.
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
            $paymentArray =  Payment::getPaymentsList($id);
            return $this->render('update', [
                'model' => $model,
                'paymentArray' => $paymentArray,
                'id_delivery' => $id,
            ]);
        }
    }

    /**
     * Deletes an existing Delivery model.
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
     * Finds the Delivery model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Delivery the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Delivery::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
