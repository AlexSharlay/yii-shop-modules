<?php

namespace common\modules\users\controllers\frontend;

use common\components\fileapi\actions\UploadAction as FileAPIUpload;

use common\modules\users\models\frontend\ActivationForm;
use common\modules\users\models\frontend\RecoveryConfirmationForm;
use common\modules\users\models\frontend\RecoveryForm;
use common\modules\users\models\frontend\ResendForm;
use common\modules\users\models\frontend\User;
use common\modules\users\models\LoginForm;
use common\modules\users\models\Profile;
use common\modules\users\Module;
use yii\filters\AccessControl;
use yii\helpers\Url;
use frontend\components\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;
use Yii;
use common\modules\users\models\frontend\UserProfile;
use \common\components\Mail;

/**
 * Frontend controller for guest users.
 */
class GuestController extends Controller
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
                        'roles' => ['?']
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
     * Sign Up page.
     * If record will be successful created, user will be redirected to home page.
     */
    public function actionSignup()
    {
        $user = new User(['scenario' => 'signup']);
        $profile = new Profile(['scenario' => 'signup']);

        if ($user->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post())) {
            if ($user->validate() && $profile->validate()) {

                // Проверка капчи
                if(isset($_POST['g-recaptcha-response'])) {
                    $captcha = $_POST['g-recaptcha-response'];
                }
                // todo Включить капчу
                if(!$captcha){
                    //Yii::$app->session->setFlash('danger', 'Ошибка капчи #1');
                    //return $this->refresh();
                }
                $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LeyER4TAAAAAB1v3yWzM_tUM88P1hE7tYg03BE3&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
                if($response['success'] == false) {
                    //Yii::$app->session->setFlash('danger', 'Ошибка капчи #2');
                    //return $this->refresh();
                }

                $user->username = $profile->ynp;
                $user->populateRelation('profile', $profile);
                if ($user->save()) {
                    if ($this->module->requireEmailConfirmation) {
                        Yii::$app->session->setFlash(
                            'success',
                            Module::t(
                                'users',
                                'Учётная запись была успешно создана. Через несколько секунд вам на почту будет отправлен код для активации аккаунта. В случае если письмо не пришло в течении 15 минут, вы можете заново запросить отправку ключа по данной <a href="{url}">ссылке</a>.',
                                [
                                    'url' => Url::toRoute('resend')
                                ]
                            )
                        );
                    } else {
                        $profile = new UserProfile();
                        $profile->fill($user->username);
                        Yii::$app->user->login($user);
                        Yii::$app->session->setFlash(
                            'success',
                            'Учётная запись была успешно создана.'
                        );
                        Mail::mail(
                            Yii::$app->params['adminEmail'],
                            $user->email,
                            'shop.BY: Пароль к личному кабинету.',
                            '<p>Добрый день!</p>
                            <p>Поздравляем Вас с созданием личного кабинета на shop.by!</p>
                            <p>Теперь Вы сможете заказывать товар прямо с сайта и получать счет-фактуры для оплаты.<br/>
                            <p>Ваша учетная запись будет активирована после проверки Ваших данных администратором сайта.<br/>
                            <p>Пожалуйста, используйте для этого следующие данные:<br/>
                            <p><b>Логин:</b> '.$user->username.'<br/>
                            <p><b>Пароль:</b> '.$user->password.'<br/>
                            <p>Изменить свои регистрационные данные Вы можете в личном кабинете по адресу: <a href="/recovery/">https://shop.by/recovery/</a></p>'
                        );
                        Mail::mailNewClient('Зарегистрирован новый клиент', $user);
                    }
                    //return $this->goHome();
                    return $this->redirect('/my/settings/update/');
                } else {
                    Yii::$app->session->setFlash('danger', 'В момент регистрации возникла ошибка. Попробуйте ещё раз пожалуйста!');
                    return $this->refresh();
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($profile);
            }

            if (count($user->errors) > 0) {
                $profile->validate();
            }
        }

        return $this->render(
            'signup',
            [
                'user' => $user,
                'profile' => $profile
            ]
        );
    }

    /**
     * Resend email confirmation token page.
     */
    public function actionResend()
    {
        $model = new ResendForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->resend()) {
                    Yii::$app->session->setFlash('success', 'На указанный электронный адрес было отправлено письмо с кодом активации новой учётной записи.');
                    //return $this->goHome();
                    return $this->redirect('/');
                } else {
                    Yii::$app->session->setFlash('danger', 'В момент отправки письма возникла ошибка. Попробуйте ещё раз пожалуйста!');
                    return $this->refresh();
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render(
            'resend',
            [
                'model' => $model
            ]
        );
    }

    /**
     * Sign In page.
     */
    public function actionLogin()
    {

        if (!Yii::$app->user->isGuest) {
            //$this->goHome();
            $this->redirect('/');
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->login()) {
                    //return $this->redirect('/my/settings/update/');
                    return $this->redirect('/');
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render(
            'login',
            [
                'model' => $model
            ]
        );
    }

    /**
     * Activate a new user page.
     *
     * @param string $token Activation token.
     *
     * @return mixed View
     */
    public function actionActivation($token)
    {
        $model = new ActivationForm(['token' => $token]);

        if ($model->validate() && $model->activation()) {
            Yii::$app->session->setFlash('success', 'Ваша учётная запись была успешно активирована.');
        } else {
            Yii::$app->session->setFlash('danger', 'Неверный код активации или возмоможно аккаунт был уже ранее активирован.');
        }

        //return $this->goHome();
        return $this->redirect('/');
    }

    /**
     * Request password recovery page.
     */
    public function actionRecovery()
    {
        $model = new RecoveryForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->recovery()) {
                    Yii::$app->session->setFlash('success', 'Готово. Проверьте пожалуйста свой контактный e-mail.');
                    //return $this->goHome();
                    return $this->redirect('/');
                } else {
                    Yii::$app->session->setFlash('danger', 'Ошибка');
                    return $this->refresh();
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render(
            'recovery',
            [
                'model' => $model
            ]
        );
    }

    /**
     * Confirm password recovery request page.
     *
     * @param string $token Confirmation token
     *
     * @return mixed View
     */
    public function actionRecoveryConfirmation($token)
    {
        $model = new RecoveryConfirmationForm(['token' => $token]);

        if (!$model->isValidToken()) {
            Yii::$app->session->setFlash(
                'danger',
                'Неверный код подтверждения.'
            );
            //return $this->goHome();
            return $this->redirect('/');
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->recovery()) {
                    Yii::$app->session->setFlash(
                        'success',
                        'Готово'
                    );
                    //return $this->goHome();
                    return $this->redirect('/');
                } else {
                    Yii::$app->session->setFlash(
                        'danger',
                        'Ошибка'
                    );
                    return $this->refresh();
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render(
            'recovery-confirmation',
            [
                'model' => $model
            ]
        );

    }

    public function actionFillProfile() {
        $profile = new UserProfile();
        return $profile->getFirmNameAndEmail(Yii::$app->request->get('id'));
    }
}
