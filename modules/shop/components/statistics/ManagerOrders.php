<?php
namespace common\modules\shop\components\statistics;

use common\modules\shop\models\backend\Order;
use DateTime;

Class ManagerOrders {

    private $result = [];

    public function get()
    {
        return $this->result;
    }

    public function __construct()
    {
        $orders = Order::find()
            ->select(['o.created', 'o.cost', new \yii\db\Expression("CONCAT(p.user_id, ' - ', p.surname, ' ',p.name, ' ',p.patronymic) as name")])
            ->alias('o')
            ->leftJoin('{{%profiles}} p', 'o.id_manager = p.user_id')
            ->orderBy('o.created DESC')
            ->asArray()->all();

        $this->result = $this->format($orders);
    }

    private function format($orders) {
        $arr = [];

        foreach($orders as $orders) {
            $date = new DateTime();
            $date->setTimestamp($orders['created']);
            $y = $date->format('Y');
            $m = $date->format('m');
            $arr[$y][$m][$orders['name']] += $orders['cost'];
        }

        foreach($arr as $y_key=>$y) {

            // Поиск всех работников за год
            $managers = [];
            foreach ($y as $m) {
                foreach ($m as $client => $cost) {
                    if (!in_array($client, $managers)) {
                        $managers[] = $client;
                    }
                }
            }
            sort($managers);

            // Переделать $arr, чтобы были все фио
            $new_managers = [];
            foreach ($y as $m_key=>$m) {
                foreach ($managers as $manager) {
                    if (array_key_exists($manager, $m)) {
                        $new_managers[$manager] = $m[$manager];
                    } else {
                        $new_managers[$manager] = 0;
                    }
                }
                $arr[$y_key][$m_key] = $new_managers;
            }

        }

        $res = [];
        foreach($arr as $y_key => $y) { // Конкретный год
            // Заполняю фио менеджеров
            $row = 0;
            $col = 0;
            $res[$y_key][$row][$col] = '"Месяц"';
            foreach($y as $m_key=>$m) { // Конкретный месяц.
                foreach($m as $p_key=>$p) {
                    $res[$y_key][$row][$col+1] = '"'.$p_key.'"';
                    $col++;
                }
                break;
            }
            // Заполняю: месяц, сумма, сумма, сумма, ...
            $row = 0;
            $col = 0;
            foreach($y as $m_key=>$m) { // Конкретный месяц
                $sum = 0;
                foreach($m as $p_key=>$p) {
                    $res[$y_key][$row+1]['0'] = '"'.$y_key.'-'.$m_key.'"';
                    $res[$y_key][$row+1][$col+1] = $p;
                    $col++;
                    $sum += $p;
                }
                // Среднее значение
                $res[$y_key][$row+1][$col+1] = $sum/2;
                $row++;
                $col = 0;
            }
            // Колонка: Среднее
            $temp = $y;
            $res[$y_key][0][count(array_shift($temp))+1] = '"Среднее"';
        }

        return $res;

    }


}