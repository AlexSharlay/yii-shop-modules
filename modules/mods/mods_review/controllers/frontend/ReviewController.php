<?php

namespace common\modules\mods\mods_review\controllers\frontend;

use Yii;
use common\modules\mods\mods_review\models\frontend\Review;
use yii\data\ActiveDataProvider;
use frontend\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ReviewController implements the CRUD actions for Review model.
 */
class ReviewController extends Controller
{

    /**
     * Lists all Review models.
     * @return mixed
     */
    public function actionIndex()
    {

        $model = new Review();

        $dataProvider = new ActiveDataProvider(
            [
                'query' => Review::find()->where('published = 1'),
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]
        );

        $new = false;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $new = true;
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'model' => $model,
            'new' => $new,
        ]);
    }

    public function actionAdd() {

        if (Yii::$app->request->post('star') && Yii::$app->request->post('name') && Yii::$app->request->post('city') && Yii::$app->request->post('desc')) {
            $model = new Review();
            $model->mark = Yii::$app->request->post('star');
            $model->name = Yii::$app->request->post('name');
            $model->city = Yii::$app->request->post('city');
            $model->desc = Yii::$app->request->post('desc');
            $model->published = 0;
            $model->date = date('d.m.Y');
            $model->save();
            return true;
        } else {
            return false;
        }
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * Finds the Review model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Review the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Review::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
