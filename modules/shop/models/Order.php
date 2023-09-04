<?php

namespace common\modules\shop\models;

use Yii;
use common\modules\blogs\traits\ModuleTrait;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%shop_order}}".
 *
 * @property integer $id
 * @property integer $id_user
 * @property integer $created
 * @property string $data
 * @property integer $status
 * @property integer $cost
 * @property integer $id_manager
 * @property integer $one_data
 * @property integer $one_status
 * @property string $invoice_xlsx
 * @property string $invoice_pdf
 * @property string $delivery
 */
class Order extends ActiveRecord
{
    use ModuleTrait;

    public static function status($id)
    {
        switch ($id) {
            case 0:
                $class = 'label-warning';
                $title = 'Не оплачен. Не отгружен.';
                break;
            case 1:
                $class = 'label-warning';
                $title = 'Не оплачен. Отгружен.';
                break;
            case 2:
                $class = 'label-success';
                $title = 'Оплачен. Не отгружен.';
                break;
            case 3:
                $class = 'label-success';
                $title = 'Оплачен. Отгружен.';
                break;
            case 4:
                $class = 'label-danger';
                $title = 'Заявка отменена.';
                break;
            case 5:
                $class = 'label-danger';
                $title = 'Оплата просрочена. Счет-фактура анулирована.';
                break;
            case 6:
                $class = 'label-danger';
                $title = 'Внимание! Не весь товар на складе.';
                break;
            case 7:
                $class = 'label-warning';
                $title = 'Не оплачен. Не отгружен.';
                break;
            case 8:
                $class = 'label-warning';
                $title = 'Ждёт согласования с менеджером.';
                break;	
            case 9:
                $class = 'label-warning';
                $title = 'Ждёт согласования с менеджером.';
                break;
            case 10:
                $class = 'label-success';
                $title = 'Согласован с менеджером.';
                break;
        }
        return [
            'class' => $class,
            'title' => $title,
        ];
    }


    public static function statusFilter() {
        return [
            '0' => 'Не оплачен. Не отгружен.',
            '1' => 'Не оплачен. Отгружен.',
            '2' => 'Оплачен. Не отгружен.',
            '3' => 'Оплачен. Отгружен.',
            '4' => 'Заявка отменена.',
            '5' => 'Оплата просрочена. Счет-фактура анулирована.',
            '6' => 'Внимание! Не весь товар на складе.',
            '7' => 'Не оплачен. Не отгружен.', //Выставлен счёт
            '8' => 'Ждёт согласования с менеджером.', //Обработан 1С
            '9' => 'Ждёт согласования с менеджером.',
            '10' => 'Согласован с менеджером.',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop_order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created', 'data', 'status', 'one_data', 'cost'], 'required'],
            [['created', 'status', 'cost', 'one_status'], 'integer'],
            [['data', 'one_data', 'invoice_xlsx', 'invoice_pdf', 'delivery'], 'string'],
            [['invoice_xlsx', 'invoice_pdf'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '№',
            'created' => 'Дата заказа',
            'data' => 'Информация о заказе',
            'status' => 'Статус',
            'cost' => 'Сумма',
            'id_manager' => 'Менеджер',
            'one_data' => 'Для 1С',
            'one_status' => 'Статус обработки 1С',
            'invoice_xlsx' => 'Счёт xlsx',
            'invoice_pdf' => 'Счёт pdf',
            'delivery' => 'Доставка',
        ];
    }
}
