<?php

namespace common\modules\mods\mods_reviews\controllers\frontend;

use Yii;
use common\modules\mods\mods_reviews\models\Review;

use frontend\components\Controller;



class ReviewsController extends Controller
{

    public function actionCreate()
    {
        if (Yii::$app->request->post('id') && Yii::$app->request->post('rating') && Yii::$app->request->post('title') &&
            Yii::$app->request->post('text') && Yii::$app->request->post('advantage') && Yii::$app->request->post('disadvantage')) {
            $model = new Review();
            $model->rating = Yii::$app->request->post('rating');
            $model->title = Yii::$app->request->post('title');
            $model->text = Yii::$app->request->post('text');
            $model->advantage = Yii::$app->request->post('advantage');
            $model->disadvantages = Yii::$app->request->post('disadvantage');
            $model->published = 0;
            $model->created_at = date('Y-m-d'); // 'YYYY-MM-DD'
            $model->user_id = Yii::$app->user->id;
            $model->catalog_element_id = Yii::$app->request->post('id');
            $model->save();
            if (count($model->errors)) return false;
        } else {
            return false;
        }
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

}
