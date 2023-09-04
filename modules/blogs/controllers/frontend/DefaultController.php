<?php

namespace common\modules\blogs\controllers\frontend;

use common\modules\blogs\models\backend\Category;
use common\modules\blogs\models\frontend\Blog;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use frontend\components\Controller;
use yii\web\Cookie;
use yii\web\HttpException;
use yii\db\Query;

/**
 * Default controller.
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        if (!isset($behaviors['access']['class'])) {
            $behaviors['access']['class'] = AccessControl::className();
        }

        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['view', 'category'],
            'roles' => ['?', '@']
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'index' => ['get'],
                'view' => ['get']
            ]
        ];

        return $behaviors;
    }

    /**
     * Blog list page.
     */
    // Статьи всех категорий
    /*
    function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Blog::find()->published(),
            'pagination' => [
                'pageSize' => $this->module->recordsPerPage
            ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }
    */

    /**
     * Blog page.
     *
     * @param integer $id Blog ID
     * @param string $alias Blog alias
     *
     * @return mixed
     *
     * @throws \yii\web\HttpException 404 if blog was not found
     */
    public function actionView($alias)
    {
        if (($model = Blog::findOne(['alias' => $alias])) !== null) {
            $this->counter($model);

            return $this->render('view', compact('alias', 'model'));
        } else {
            throw new HttpException(404);
        }
    }


    /**
     * Blog category page.
     *
     * @param string $category Blog category alias
     *
     * @return mixed
     *
     * @throws \yii\web\HttpException 404 if blog was not found
     */
    public function actionCategory($category)
    {
        if (($model = Category::find()->where('alias=:alias', [':alias' => $category])->one()) !== null) {
            $dataProvider = new ActiveDataProvider([
                'query' => Blog::find()->where(['category_id' => $model['id']])->andWhere('status_id=1')->orderBy('id DESC'),
                'pagination' => [
                    'pageSize' => $this->module->recordsPerPage
                ]
            ]);
            return $this->render('index', compact('dataProvider', 'model'));
        } else {
            throw new HttpException(404);
        }
    }

    /**
     * Update blog views counter.
     *
     * @param Blog $model Model
     */
    protected function counter($model)
    {
        $cookieName = 'blogs-views';
        $shouldCount = false;
        $views = Yii::$app->request->cookies->getValue($cookieName);

        if ($views !== null) {
            if (is_array($views)) {
                if (!in_array($model->id, $views)) {
                    $views[] = $model->id;
                    $shouldCount = true;
                }
            } else {
                $views = [$model->id];
                $shouldCount = true;
            }
        } else {
            $views = [$model->id];
            $shouldCount = true;
        }

        if ($shouldCount === true) {
            if ($model->updateViews()) {
                Yii::$app->response->cookies->add(new Cookie([
                    'name' => $cookieName,
                    'value' => $views,
                    'expire' => time() + 86400 * 365
                ]));
            }
        }
    }
}
