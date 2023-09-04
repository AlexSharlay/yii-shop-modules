<?php

namespace common\modules\users\controllers\backend;

use common\modules\users\models\LoginForm;
use yii\filters\AccessControl;
use frontend\components\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;
use Yii;

/**
 * Backend controller for guest users.
 */
class GuestController extends Controller
{
    /**
     * @inheritdoc
     */
    public $layout = '//guest';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?']
                    ]
                ]
            ]
        ];
    }

    /**
     * Login user.
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->login()) {
                    return $this->goHome();
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render(
            'login',
            [
                'model' => $model,
            ]
        );
    }
}
