<?php

namespace common\modules\users\controllers\frontend;

use common\modules\users\models\frontend\Email;
use frontend\components\Controller;
use Yii;

/**
 * Default frontend controller.
 */
class DefaultController extends Controller
{
    /**
     * Confirm new e-mail address.
     *
     * @param string $key Confirmation token
     *
     * @return mixed View
     */
    public function actionEmail($key)
    {
        $model = new Email(['token' => $key]);

        if ($model->isValidToken() === false) {
            Yii::$app->session->setFlash(
                'danger',
                'Неверный код подтверждения.'
            );
        } else {
            if ($model->confirm()) {
                Yii::$app->session->setFlash(
                    'success',
                    'E-mail адрес был успешно обновлён!'
                );
            } else {
                Yii::$app->session->setFlash(
                    'danger',
                   'В момент подтверждения нового электронного адреса возникла ошибка. Попробуйте ещё раз пожалуйста!'
                );
            }
        }

        return $this->goHome();
    }
}
