<?php

namespace common\components;

use Yii;
use yii\db\Expression;
use yii\db\Query;

class Mail
{

    public static function mail($from, $to, $subj, $html) {
        Yii::$app->mailer->compose()
            ->setTo($to)
            ->setFrom($from)
            ->setSubject($subj)
            ->setHtmlBody($html)
            ->send();
    }

    public static function mailInfo($subj, $html = '') {
        foreach(Yii::$app->params['infoEmail'] as $mail) {
            Yii::$app->mailer->compose()
                ->setTo($mail)
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setSubject($subj)
                ->setHtmlBody($html)
                ->send();
        }
    }


    public static function mailNewClient($subj,$user) {

        $html = 'На сайте https://kranik.by зарегистрировался новый пользователь.<br/>
                Карта: '.$user->username.'<br/>
                E-mail: '.$user->email.'<br/><br/>';

        foreach(Yii::$app->params['newClientEmail'] as $mail) {
            Yii::$app->mailer->compose()
                ->setTo($mail)
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setSubject($subj)
                ->setHtmlBody($html)
                ->send();
        }
    }

    public static function mailNewClientHtml($subj,$html) {
        foreach(Yii::$app->params['newClientEmail'] as $mail) {
            Yii::$app->mailer->compose()
                ->setTo($mail)
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setSubject($subj)
                ->setHtmlBody($html)
                ->send();
        }
    }

    public static function mailError($subj, $html) {
        foreach(Yii::$app->params['errorEmail'] as $mail) {
            Yii::$app->mailer->compose()
                ->setTo($mail)
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setSubject($subj)
                ->setHtmlBody($html)
                ->send();
        }
    }

    public static function managerOrderError($order_id) {
          // Письмо манагеру чей клиент сделал заказ
        $expression = new Expression('m.email, CONCAT_WS(" ", p.surname, p.name, p.patronymic) fio, CONCAT_WS(" ", c.surname, c.name, c.patronymic) fio_client');
        $email = (new Query())
            ->select($expression)
            ->from('{{%shop_client_manager}} cm')
            ->leftJoin('{{%users}} m', 'm.id = cm.id_manager')
            ->leftJoin('{{%profiles}} p', 'p.user_id = cm.id_manager')
            ->leftJoin('{{%profiles}} c', 'c.user_id = cm.id_client')
            ->where('cm.id_client = :id_client', [':id_client' => Yii::$app->user->id])
            ->one();
        $title = 'Неудача заказа. Столько товара нет на складе.';
        $html = 'Заказ: '.$order_id.'<br/>Менеджер: '.$email['fio'].'<br/>Клиент: '.$email['fio_client'];
        Yii::$app->mailer->compose()
            ->setTo($email['email'])
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setSubject($title)
            ->setHtmlBody($html)
            ->send();

        // Письма начальникам манагера
        foreach(Yii::$app->params['emailCopyOrder'] as $mailManager=>$mails) {
            if ($mailManager == $email['email']) {
                foreach ($mails as $mail) {
                    Yii::$app->mailer->compose()
                        ->setTo($mail)
                        ->setFrom(Yii::$app->params['adminEmail'])
                        ->setSubject($title)
                        ->setHtmlBody($html)
                        ->send();
                }
            }
        }
    }

}





