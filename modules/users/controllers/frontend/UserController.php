<?php

namespace common\modules\users\controllers\frontend;

use common\components\fileapi\actions\UploadAction as FileAPIUpload;
use common\components\Mail;
use common\modules\users\models\frontend\Email;
use common\modules\users\models\frontend\PasswordForm;
use common\modules\users\models\frontend\UserProfile;
use common\modules\users\models\Profile;
use common\modules\users\models\User;
use yii\filters\AccessControl;
use frontend\components\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;
use Yii;

/**
 * Frontend controller for authenticated users.
 */
class UserController extends Controller
{
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
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'fileapi-upload' => [
                'class' => FileAPIUpload::className(),
                'path' => $this->module->avatarsTempPath
            ]
        ];
    }

    /**
     * Log Out page.
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect('/');
    }

    /**
     * Change password page.
     */
    public function actionPassword()
    {
        $model = new PasswordForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->password()) {
                    Yii::$app->session->setFlash(
                        'success',
                        'FRONTEND_FLASH_SUCCESS_PASSWORD_CHANGE'
                    );
                    return $this->goHome();
                } else {
                    Yii::$app->session->setFlash('danger', 'FRONTEND_FLASH_FAIL_PASSWORD_CHANGE');
                    return $this->refresh();
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render(
            'password',
            [
                'model' => $model
            ]
        );
    }

    /**
     * Request email change page.
     */
    public function actionEmail()
    {
        $model = new Email();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', 'На указанный новый электронный адрес было отправлено письмо с кодом подтверждения.');
                    return $this->goHome();
                } else {
                    Yii::$app->session->setFlash('danger', 'В момент изменения электронного адреса возникла ошибка. Попробуйте ещё раз пожалуйста!');
                    return $this->refresh();
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render(
            'email',
            [
                'model' => $model
            ]
        );
    }

    /**
     * Profile updating page.
     */
    public function actionUpdate()
    {
        $model = Profile::findByUserId(Yii::$app->user->id);
        $status = User::find()->where('id = :id', [':id'=>Yii::$app->user->id])->asArray()->one()['status_id'];
        $model->scenario = 'update';

        $showMessageUpdate = 0;
        $post = Yii::$app->request->post();
        // Если есть что-то из новых полей, то отправить на мыло сообщение об изменениях
        if ($post['Profile']['ynp'] || $post['Profile']['firmName'] || $post['Profile']['legal_address'] || $post['Profile']['settlement_account'] || $post['User']['email'])
        {
            // Послать письмо
            Mail::mailNewClientHtml(
                'Запрос клиента на изменение данных',
                '<b>id клиента</b>: '.Yii::$app->user->id.'<br/><br/>'.
                '<b>УНП</b>: '.$post['Profile']['ynp'].'<br/>'.
                '<b>Название компании</b>: '.$post['Profile']['firmName'].'<br/>'.
                '<b>Адрес</b>: '.$post['Profile']['legal_address'].'<br/>'.
                '<b>р/с</b>: '.$post['Profile']['settlement_account'].'<br/>'.
                '<b>e-mail</b>: '.$post['User']['email'].'<br/>'
            );
            // Удаляем что бы не обновилось
            unset($post['Profile']['ynp']);
            unset($post['Profile']['firmName']);
            unset($post['Profile']['legal_address']);
            unset($post['Profile']['settlement_account']);
            unset($post['User']['email']);
            // Сообщение
            $showMessageUpdate = 1;
        }

        if ($model->load($post)) {
            if ($model->validate()) {
                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', 'Профиль был успешно обновлён.');
                    if ($showMessageUpdate) Yii::$app->session->setFlash('info', 'Новые данные появятся в Вашем профиле после проверки администратором.');
                } else {
                    Yii::$app->session->setFlash('danger', 'В момент обновления профиля возникла ошибка. Попробуйте ещё раз пожалуйста!');
                }
                return $this->refresh();
            } else {
                foreach($model->errors as $error) {
                    Yii::$app->session->setFlash('danger', $error);
                }
            }

            /*elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }*/
        }

        return $this->render(
            'update',
            [
                'model' => $model,
                'status' => $status,
            ]
        );
    }

    public function actionDiscounts()
    {
        $discounts = \common\modules\shop\models\UserDiscount1c::discounts(Yii::$app->user->id);
        if ($discounts['count'] == 0) Yii::$app->session->setFlash('warning', ' На данный момент у Вас нет скидок.');
        return $this->render('discounts', [
            'discounts' => $discounts,
        ]);
    }

}
