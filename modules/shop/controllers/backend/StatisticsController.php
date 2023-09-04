<?
namespace common\modules\shop\controllers\backend;

use Yii;
use common\modules\shop\models\backend\Order;
use common\modules\shop\models\backend\OrderSearch;
use backend\components\Controller;
use common\modules\shop\components\statistics\ManagerOrders;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class StatisticsController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['manager-orders'],
            'roles' => ['BAllCatalogStatics']
        ];
        return $behaviors;
    }

    public function actionManagerOrders()
    {
        $charts = new ManagerOrders;
        return $this->render('manager-orders', [
            'arr' => $charts->get(),
        ]);
    }
}
