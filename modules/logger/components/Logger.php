<?
namespace common\modules\logger\components;

use Yii;
use common\modules\logger\models\backend\LoggerAction;
use \yii\base\Component;
use DateTime;

class Logger extends Component
{

    public $log = false;

    public function add() {
        if ($this->log && Yii::$app->user->id != 1) {
            $date = new DateTime();
            $log = new LoggerAction();
            $log->create = $date->format('Y-m-d H:i:s');
            $log->module = Yii::$app->controller->module->id;
            $log->controller = Yii::$app->controller->id;
            $log->action = Yii::$app->controller->action->id;
            $log->ip = $_SERVER["REMOTE_ADDR"];
            (!Yii::$app->user->isGuest) ? $log->id_user = Yii::$app->user->id : '';
            $log->headers = json_encode(getallheaders());
            (count($_GET)) ? $log->get = json_encode($_GET) : '';
            (count($_POST)) ? $log->post = json_encode($_POST) : '';
            $log->save();
        }
    }

}
