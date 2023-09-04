<?php

namespace common\modules\users\models\backend;

use common\modules\users\Module;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * Class User
 * @package module\users\models\backend
 * User administrator model.
 *
 * @property string|null $password Password
 * @property string|null $repassword Repeat password
 *
 * @property Profile $profile Profile
 */
class User extends \common\modules\users\models\User
{
    /**
     * @var string|null Password
     */
    public $password;

    /**
     * @var string|null Repeat password
     */
    public $repassword;

    /**
     * @var string Model status.
     */
    private $_status;

    private $_statusOld;

    /**
     * @return string Model status.
     */
    public function getStatus()
    {
        if ($this->_status === null) {
            $statuses = self::getStatusArray();
            $this->_status = $statuses[$this->status_id];
        }
        return $this->_status;
    }

    /**
     * @return array Status array.
     */
    public static function getStatusArray()
    {
        return [
            self::STATUS_ACTIVE => 'Активен',
            self::STATUS_INACTIVE => 'Не активен',
            self::STATUS_BANNED => 'Забанен'
        ];
    }

    /**
     * @return array Role array.
     */
    public static function getRoleArray()
    {
        return ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // Required
            [['username', 'email'], 'required'],
            [['password', 'repassword'], 'required', 'on' => ['admin-create']],
            // Trim
            [['username', 'email', 'password', 'repassword', 'name', 'surname'], 'trim'],
            // String
            [['password', 'repassword'], 'string', 'min' => 6, 'max' => 30],
            // Unique
            [['username', 'email'], 'unique'],
            // Username
            ['username', 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/'],
            ['username', 'string', 'min' => 2, 'max' => 30],
            // E-mail
            ['email', 'string', 'max' => 100],
            ['email', 'email'],
            // Repassword
            ['repassword', 'compare', 'compareAttribute' => 'password'],
            // Role
            ['role', 'in', 'range' => array_keys(self::getRoleArray())],
            // Status
            ['status_id', 'in', 'range' => array_keys(self::getStatusArray())]
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            'admin-create' => ['username', 'email', 'password', 'repassword', 'status_id', 'role'],
            'admin-update' => ['username', 'email', 'password', 'repassword', 'status_id', 'role']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();

        return array_merge(
            $labels,
            [
                'password' => 'Пароль',
                'repassword' => 'Повторите пароль'
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord || (!$this->isNewRecord && $this->password)) {
                $this->setPassword($this->password);
                $this->generateAuthKey();
                $this->generateToken();
            }
            return true;
        }
        return false;
    }

    public function afterFind()
    {
        $this->_statusOld = $this->status_id;
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->_statusOld != 1 && $this->status_id == 1) {
            Yii::$app->mailer->compose()
                ->setTo($this->email)
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setSubject('shop.BY: Активация аккаунта.')
                ->setHtmlBody('<html><body>Уважаемый пользователь, Ваш аккаунт прошел проверку и успешно активирован. Для использования сайта перейдите по ссылке <a href="/">https://shop.by</a>.</body></html>')
                ->send();
        }

        if ($this->profile !== null) {
            $this->profile->save(false);
        }

        $auth = Yii::$app->authManager;
        $name = $this->role ? $this->role : self::ROLE_DEFAULT;
        $role = $auth->getRole($name);

        if (!$insert) {
            $auth->revokeAll($this->id);
        }

        $auth->assign($role, $this->id);
    }

    public static function getMangersList() {
        $result = [];
        $users = (new \yii\db\Query())
            ->select('p.user_id, p.name, p.surname, p.patronymic')
            ->from('{{%users}} u')
            ->leftJoin('{{%profiles}} p', 'u.id = p.user_id')
            ->where('u.role = "manager"')
            ->orWhere('u.role = "superManager"')
            ->orderBy('p.surname')
            //->where('u.role = "manager"', [':id_user' => Yii::$app->user->id])
            ->all();
        foreach($users as $user) {
            $result[$user['user_id']] = $user['surname'].' '.$user['name'].' '.$user['patronymic'];
        }
        return $result;
    }

    public static function getClientsList() {
        $result = [];
        $users = (new \yii\db\Query())
            ->select('p.user_id, p.firmName')
            ->from('{{%users}} u')
            ->leftJoin('{{%profiles}} p', 'u.id = p.user_id')
            ->where('u.role = "user"')
            //->where('u.role = "manager"', [':id_user' => Yii::$app->user->id])
            ->all();

        foreach($users as $user) {
            $result[$user['user_id']] = $user['firmName'];
        }
        return $result;
    }

}
