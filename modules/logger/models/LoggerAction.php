<?php

namespace common\modules\logger\models;

use Yii;
use yii\db\ActiveRecord;
use common\modules\logger\traits\ModuleTrait;

/**
 * This is the model class for table "{{%logger_action}}".
 *
 * @property integer $id
 * @property string $module
 * @property string $controller
 * @property string $action
 * @property string $ip
 * @property integer $id_user
 * @property string $create
 * @property string $headers
 * @property string $get
 * @property string $post
 */
class LoggerAction extends ActiveRecord
{

    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%logger_action}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['module', 'controller', 'action', 'ip', 'create'], 'required'],
            [['id_user'], 'integer'],
            [['headers', 'get', 'post'], 'string'],
            [['module', 'controller', 'action', 'create'], 'string', 'max' => 64],
            [['ip'], 'string', 'max' => 16],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'module' => 'Module',
            'controller' => 'Controller',
            'action' => 'Action',
            'ip' => 'Ip',
            'id_user' => 'Id User',
            'create' => 'Create',
            'headers' => 'Headers',
            'get' => 'Get',
            'post' => 'Post',
        ];
    }
}
