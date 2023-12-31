<?php

namespace common\modules\comments\models\frontend;

/**
 * This is the model class for table "{{%comments_models}}".
 *
 * @property integer $id ID
 * @property string $name Model class name
 * @property integer $status_id Status
 * @property integer $created_at Created time
 * @property integer $updated_at Updated time
 *
 * @property Comments[] $comments Comments
 */
class Model extends \common\modules\comments\models\Model
{
}
