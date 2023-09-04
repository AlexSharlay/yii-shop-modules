<?php

namespace common\modules\rbac\rules;

use Yii;
use yii\rbac\Rule;

/**
 * User group rule class.
 */
class GroupRule extends Rule
{
    /**
     * @inheritdoc
     */
    public $name = 'group';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        if (!Yii::$app->user->isGuest) {
            $role = Yii::$app->user->identity->role;

            if ($item->name === 'admin') // админ
            {
                return $role === $item->name;
            }
            elseif ($item->name === 'contentManager') // менеджер
            {
                return $role === 'contentManager';
            }
            elseif ($item->name === 'hr')   // кадровик
            {
                return $role === 'hr';
            }
            elseif ($item->name === 'manager')  // манагер по опту
            {
                return $role === 'manager';
            }
            elseif ($item->name === 'superManager') // босс манагеров по опту
            {
                return $role === 'superManager';
            }
            elseif ($item->name === 'user') // покупатель
            {
                return $role === $item->name;
            }
        }
        return false;
    }
}
