<?php

namespace common\modules\shop\models;

use common\modules\users\models\Profile;
use Yii;
use yii\db\ActiveRecord;
use common\modules\blogs\traits\ModuleTrait;
use common\modules\users\models\backend\User;

/**
 * This is the model class for table "{{%shop_client_manager}}".
 *
 * @property integer $id
 * @property integer $id_manager
 * @property integer $id_client
 * @property integer $active
 *
 * @property Users $idClient
 * @property Users $idManager
 */
class ClientManager extends ActiveRecord
{

    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop_client_manager}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_manager', 'id_client', 'active'], 'integer'],
            [['id_client'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_client' => 'id']],
            [['id_manager'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_manager' => 'id']],
            [['id_manager', 'id_client'], 'uniqueClientManager'],
        ];
    }

    public function uniqueClientManager($attribute, $params)
    {
        if ((new \yii\db\Query())->select('id')->from('{{%shop_client_manager}}')->where('id_manager = :id_manager AND id_client = :id_client', [':id_client' => $this->id_client, ':id_manager' => $this->id_manager])->one()) {
            $this->addError($attribute, 'Такая связь уже есть!');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_client' => 'Клиент',
            'id_manager' => 'Менеджер',
            'active' => 'Работает',
        ];
    }

    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    public function getProfileManager()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id_manager']);
    }

    public function getProfileClient()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id_client']);
    }
}
