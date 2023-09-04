<?php

namespace common\modules\shop\models\backend;

use Yii;
use yii\db\Query;
use \common\components\Mail;
use yii\helpers\ArrayHelper;

class ClientManager extends \common\modules\shop\models\ClientManager
{

    public static function sendManagerEmail($id) {
        $clientManager = (new \yii\db\Query())
            ->select('pc.name client_name ,pc.patronymic client_patronymic ,pc.surname client_surname ,pc.firmName, pc.ynp, uc.email client_email, pc.phone_director, pc.phone_company, um.email')
            ->from('{{%shop_client_manager}} cm')
            ->leftJoin('{{%profiles}} pc','cm.id_client = pc.user_id')
            ->leftJoin('{{%users}} um','um.id = cm.id_manager')
            ->leftJoin('{{%users}} uc','uc.id = cm.id_client')
            ->where('cm.id = :id', [':id' => $id])
            ->one();

        Yii::$app->mailer->compose()
            ->setTo($clientManager['email'])
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setSubject('Закреплен новый клиент.')
            ->setHtmlBody('<p>
                Данное уведомление пришло Вам, т.к. на сайте shop.by за Вами был закреплен новый зарегестрировавшийся клиент.
                Убедитесь, что он зарегестрирован в базе контрагентов 1с с  действующими для него скидками.<br/><br/>

                Информация о клиенте:<br/>
                ~~~~~~~~~~~~~~~~~~~~~~~~~<br/>
                Наименование компании: '.$clientManager['firmName'].'<br/>
                УНП '.$clientManager['ynp'].'<br/>
                Контактный e-mail '.$clientManager['client_email'].'<br/>
                Номер телефона '.$clientManager['phone_director'].' '.$clientManager['phone_company'].'<br/>
                ~~~~~~~~~~~~~~~~~~~~~~~~~
            </p>')
            ->send();


    }
}
