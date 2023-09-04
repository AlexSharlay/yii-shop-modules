<?php


namespace common\modules\users\controllers\backend;

use backend\components\Controller;
use common\components\fileapi\actions\UploadAction as FileAPIUpload;
use common\modules\shop\models\backend\UserDiscount;
use common\modules\users\models\backend\User;
use common\modules\users\models\backend\UserSearch;
use common\modules\users\models\Profile;
use common\modules\users\Module;

use Yii;
use yii\filters\VerbFilter;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Default backend controller.
 */
class DefaultController extends Controller
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
                'actions' => ['index'],
                'roles' => ['BViewUsers']
            ]
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['create'],
            'roles' => ['BCreateUsers']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['update', 'update-discount'],
            'roles' => ['BUpdateUsers']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['delete', 'batch-delete'],
            'roles' => ['BDeleteUsers']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['fileapi-upload'],
            'roles' => ['BCreateUsers', 'BUpdateUsers']
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

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'fileapi-upload' => [
                'class' => FileAPIUpload::className(),
                'path' => $this->module->avatarsTempPath,
            ]
        ];
    }

    /**
     * Users list page.
     */
    function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        $statusArray = User::getStatusArray();
        $roleArray = User::getRoleArray();

        return $this->render('index', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'roleArray' => $roleArray,
                'statusArray' => $statusArray
            ]);
    }

    /**
     * Create user page.
     */
    public function actionCreate()
    {
        $user = new User(['scenario' => 'admin-create']);
        $profile = new Profile();
        $profile->setScenario('update_manager');
        $statusArray = User::getStatusArray();
        $roleArray = User::getRoleArray();

        if ($user->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post())) {
            if ($user->validate() && $profile->validate()) {
                $user->populateRelation('profile', $profile);
                if ($user->save(false)) {
                    return $this->redirect(['update', 'id' => $user->id]);
                } else {
                    Yii::$app->session->setFlash('danger', 'BACKEND_FLASH_FAIL_ADMIN_CREATE');
                    return $this->refresh();
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return array_merge(ActiveForm::validate($user), ActiveForm::validate($profile));
            }
        }

        return $this->render('create', [
                'user' => $user,
                'profile' => $profile,
                'roleArray' => $roleArray,
                'statusArray' => $statusArray
            ]);
    }

    /**
     * Update user page.
     *
     * @param integer $id User ID
     *
     * @return mixed View
     */
    public function actionUpdate($id)
    {
        $user = $this->findModel($id);
        $user->setScenario('admin-update');
        $profile = $user->profile;
        $profile->setScenario('update_manager');
        $statusArray = User::getStatusArray();
        $roleArray = User::getRoleArray();

        if ($user->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post())) {
            if ($user->validate() && $profile->validate()) {
                $user->populateRelation('profile', $profile);
                if (!$user->save(false)) {
                    Yii::$app->session->setFlash('danger', 'BACKEND_FLASH_FAIL_ADMIN_CREATE');
                }
                return $this->refresh();
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return array_merge(ActiveForm::validate($user), ActiveForm::validate($profile));
            }
        }

        $file = fopen($_SERVER['DOCUMENT_ROOT'] . "/counter.txt", "a");//////////////////////////////
        fwrite($file, date('d.m.Y H:i:s') . ' userID => ' . $user->id . ',  adminID => ' . Yii::$app->user->id . PHP_EOL);///////////////////////
        fclose($file);/////////////////

        return $this->render('update', [
                'user' => $user,
                'profile' => $profile,
                'roleArray' => $roleArray,
                'statusArray' => $statusArray
            ]);
    }

    public function actionUpdateDiscount($id)
    {
        $user = $this->findModel($id);
        $profile = $user->profile;

        if (count(Yii::$app->request->post('discount')) > 0) {
            UserDiscount::saveDiscounts($id, Yii::$app->request->post('discount'));
        }

        return $this->render('update-discount', [
            'user' => $user,
            'profile' => $profile,
            'discounts' => \common\modules\shop\models\UserDiscount::discounts($user->id)['discounts'],
        ]);
    }

    /**
     * Delete user page.
     *
     * @param integer $id User ID
     *
     * @return mixed View
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Delete multiple users page.
     */
    public function actionBatchDelete()
    {
        if (($ids = Yii::$app->request->post('ids')) !== null) {
            $models = $this->findModel($ids);
            foreach ($models as $model) {
                $model->delete();
            }
            return $this->redirect(['index']);
        } else {
            throw new HttpException(400);
        }
    }

    /**
     * Find model by ID
     *
     * @param integer|array $id User ID
     *
     * @return \common\modules\users\models\backend\User User
     * @throws HttpException 404 error if user was not found
     */
    protected function findModel($id)
    {
        if (is_array($id)) {
            /** @var User $user */
            $model = User::findIdentities($id);
        } else {
            /** @var User $user */
            $model = User::findIdentity($id);
        }
        if ($model !== null) {
            return $model;
        } else {
            throw new HttpException(404);
        }
    }
}
