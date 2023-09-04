<?php

namespace common\modules\catalog\controllers\frontend;

use common\modules\catalog\components\import\onec\Api;
use common\modules\catalog\components\Tools;
use common\modules\catalog\models\frontend\Category;
use common\modules\catalog\models\frontend\Element;
use common\modules\catalog\components\Search;
use common\modules\catalog\components\export\excell\ExportExcell;
use common\modules\users\models\frontend\User;
use common\modules\users\models\frontend\UserProfile;
use common\modules\users\models\Profile;
use Yii;
use frontend\components\Controller;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use common\modules\shop\components\Helper;

/**
 * CatalogController
 */
class DefaultController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        if (!isset($behaviors['access']['class'])) {
            $behaviors['access']['class'] = AccessControl::className();
        }

        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => [
                'index',
                'product', 'get-product',
                'get-products',
                'category', 'get-filters',
                'add-to-cart',
                'search', 'search-go', 'price',
                'collections-page', 'get-collections',
                'collection-page',
            ],
            'roles' => ['?', '@']
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'index' => ['get', 'post'],
                'product' => ['get'],
                'get-product' => ['get'],
                'get-products' => ['get'],
                'category' => ['get'],
                'get-filters' => ['get'],
                'search' => ['post', 'get'],
                'price' => ['get'],
                'collections-page' => ['get'],
                'get-collections' => ['get'],
                'collection-page' => ['get'],
            ]
        ];

        return $behaviors;
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public static function slug($string)
    {
        return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
    }


    public function actionIndex()
    {

//        $file = fopen($_SERVER['DOCUMENT_ROOT']."/log.txt","a");
//        fwrite($file,serialize(Yii::$app->request->post()).PHP_EOL);
//        fclose($file);

    }

    // =======================================================================

    // Страница товара
    public function actionProduct($category, $manufacturer = '', $product)
    {
        $productPage = Element::getProductPage($category, $manufacturer = '', $product);
        return $this->render('product', [
            'productId' => $productPage['productId'],
            'day' => (Yii::$app->user->isGuest) ? null : $productPage['day'],
            'city' => (Yii::$app->user->isGuest) ? null : $productPage['city'],
            'page' => [
                'seo_title' => $productPage['seo_title'],
                'seo_keyword' => $productPage['seo_keyword'],
                'seo_desc' => $productPage['seo_desc']
            ]
        ]);
    }

    // Запрос товара
    public function actionGetProduct($id, $id_kit = null)
    {
        return json_encode(Element::getProduct($id, $id_kit));
    }

    // =======================================================================

    // Категория без фильтров и без товаров
    public function actionCategory($category, $manufacturer = '')
    {
        $categories = Category::getCategoriesForMenu($category);
        return $this->render('category', [
            'categories' => $categories,
            'category' => $category,
            'page' => Category::find()->where('alias=:alias', [':alias' => $category])->asArray()->one(),
            'manufacturer' => $manufacturer,
        ]);
    }

    // Фильтры категории
    public function actionGetFilters($category)
    {
        Helper::jsonHeader();
        echo Category::find()->select('facets')->where('alias=:alias', [':alias' => $category])->asArray()->one()['facets'];
    }

    // Товары категории
    public function actionGetProducts()
    {
        echo json_encode(Element::getCategoryProducts());
    }

    // =======================================================================

    // Коллекции
    // Категория без фильтров и без товаров
    public function actionCollectionsPage($category, $manufacturer = '')
    {
        $categories = Category::getCategoriesForMenu($category);
        return $this->render('collections', [
            'categories' => $categories,
            'category' => $category,
            'page' => Category::find()->where('alias=:alias', [':alias' => $category])->asArray()->one(),
            'manufacturer' => $manufacturer,
        ]);
    }

    // Фильтры категории -//- actionGetFilters

    // Товары категории
    public function actionGetCollections()
    {
        echo json_encode(Element::getCollectionsProducts());
    }

    // =======================================================================

    // Страница коллекции
    public function actionCollectionPage()
    {
        return $this->render('collection', Element::getCollection());
    }

    // =======================================================================


    public function actionSearch()
    {
        $post = Yii::$app->request->post('search');
        return $this->render('search', ['search' => $post]);
    }

    public function actionSearchGo()
    {
        $search = Yii::$app->request->get('search');
        $page = Yii::$app->request->get('page');
        $sort = Yii::$app->request->get('sort');
        $category = Yii::$app->request->get('category');

        Helper::jsonHeader();
        echo json_encode(Search::searchProducts(trim($search), $page, $sort, $category));
    }

    public function actionPrice($type = 'excell')
    {
        if ($type == 'excell') {
            ExportExcell::run();
        }
    }

    public function beforeAction($action)
    {
        if (in_array($action->actionMethod, ['actionSearch', 'actionSearchGo', 'actionIndex'])) {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    protected function findModel($id)
    {
        if (($model = Element::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}