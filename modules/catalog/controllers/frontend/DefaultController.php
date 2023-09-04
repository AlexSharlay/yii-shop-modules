<?php

namespace common\modules\catalog\controllers\frontend;

use common\modules\catalog\components\category\CategoryPage;
use common\modules\catalog\components\import\onec\Api;
use common\modules\catalog\components\Tools;
use common\modules\catalog\models\frontend\Category;
use common\modules\catalog\models\frontend\Element;
use common\modules\catalog\components\Search;
use common\modules\catalog\components\export\excell\ExportExcell;
use common\modules\catalog\models\frontend\Manufacturer;
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
                'menu-modal', 'filter-modal',
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
                'menu-modal' => ['get'],
                'filter-modal' => ['get'],
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
    }

    // =======================================================================

    // Страница товара
    public function actionProduct($category, $manufacturer = '', $product)
    {
        $productPage = Element::getProductPage($category, $manufacturer = '', $product);
        $productFull = Element::getProduct($productPage['productId']);
        $categoryUrl = CategoryPage::categoryUrl($category);

        //выбран родительский товар в модели
        $selected_parent_product = false;
        if (!empty($productFull['models'])) {
            $arr = [];
            foreach ($productFull['models'] as $model) {
                $arr[] = $model['id'];
            }
            if (!in_array($productFull['id'], $arr)) $selected_parent_product = true;
        }

//        return $this->render('view', compact('product', 'hits'));
        return $this->render('product', [
            'productPage' => $productPage,
            'productFull' => $productFull,
            'categoryUrl' => $categoryUrl,
            'productId' => $productPage['productId'],
            'guarantee' => $productPage['guarantee'],
            'day' => (Yii::$app->user->isGuest) ? null : $productPage['day'],
            'city' => (Yii::$app->user->isGuest) ? null : $productPage['city'],
            'page' => [
                'seo_title' => $productPage['seo_title'],
                'seo_keyword' => $productPage['seo_keyword'],
                'seo_desc' => $productPage['seo_desc']
            ],
            'selected_parent_product' => $selected_parent_product,
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
        $query = Yii::$app->request->get();
        $strPathInfo = explode('/', $query['category']);
        if (count($strPathInfo) == 2) $category = $strPathInfo[0];
        if (count($strPathInfo) == 3) $category = $strPathInfo[1];

        $categories = Category::getCategoriesForMenu($category);
        $categoriesForMenuImg = Category::getCategoriesForMenuImg($category, $categories);

        $categoryUrl = CategoryPage::categoryUrl($category);


        $categoryProducts = Element::getCategoryProducts();
//        $filterCategory = Category::find()->select('facets')->where('id=:id', [':id' => '110'])->asArray()->one()['facets'];

        $page = Category::find()->where('alias=:alias', [':alias' => $category])->asArray()->one();
        if (count($strPathInfo) == 2)
            $page = Category::find()->where('id_parent=:id_parent', [':id_parent' => $page['id']])->andWhere('alias=:alias', [':alias' => $strPathInfo[1]])->asArray()->one();
        if (count($strPathInfo) == 3)
            $page = Category::find()->where('id_parent=:id_parent', [':id_parent' => $page['id']])->andWhere('alias=:alias', [':alias' => $strPathInfo[2]])->asArray()->one();

//        if (count($_GET) > 1) {
//            $prod = [];
//            $prodSelect = $categoryProducts['products'];
//            foreach (Yii::$app->request->get() as $keyGet => $valueGet) {
//                if ($keyGet <> 'id') {
//                    if (($keyGet <> 'filter') && ($valueGet <> 'clear')) {
//                        if (is_array($valueGet) && ($valueGet['from'] || $valueGet['to'])) {
//                            if (!empty($valueGet['from']) || !empty($valueGet['to'])) {
////                    echo '1. keyGet => ' . $keyGet . '  |  valueGet => ';debug($valueGet);//////////////
//                                foreach ($prodSelect as $key => $product) {
//                                    if ($keyGet == 'price') {
//                                        $value = $product[$keyGet] / 100;
//                                    } elseif ($keyGet == 'bath_weight') {
//                                        $value = $product[$keyGet] / 1000;
//                                    } else {
//                                        $value = $product[$keyGet];
//                                    }
//
//                                    if (empty($valueGet['to'])) {
//                                        if ($valueGet['from'] <= $value) {
//                                            $prod += [$key => $product];
//                                        }
//                                    } else {
//                                        if (($valueGet['from'] <= $value) && ($value <= $valueGet['to'])) {
//                                            $prod += [$key => $product];
//                                        }
//                                    }
//                                }
//                                $prodSelect = $prod;
////                    echo '<br>Всего:' . count($prodSelect) . '<br>';////////////////
//                                $prod = [];
//                            }
//                        }
//                        if (is_array($valueGet) && (!(isset($valueGet['from']) && isset($valueGet['from'])))) {
////                echo '2. keyGet => ' . $keyGet . '  |  valueGet => ';debug($valueGet);//////////////
//                            foreach ($prodSelect as $key => $product) {
//                                foreach ($valueGet as $item) {
//                                    if ($keyGet == 'mfr') {
//                                        if ($product['alias_manufacturer'] == $item) {
//                                            $prod += [$key => $product];
//                                        }
//                                    } else {
//                                        if ($product[$keyGet] == $item) {
//                                            $prod += [$key => $product];
//                                        }
//                                    }
//                                }
//                            }
//                            $prodSelect = $prod;
////                echo '<br>Всего:' . count($prodSelect) . '<br>';////////////////
//                            $prod = [];
//                        }
//                        if (!(is_array($valueGet))) {
////                echo '3. keyGet => ' . $keyGet . '  |  valueGet => ';debug($valueGet);//////////////
//                            foreach ($prodSelect as $key => $product) {
//                                if (array_key_exists($keyGet, $product)) {
//                                    $prod += [$key => $product];
//                                }
//                                $prodSelect = $prod;
//                            }
//                            $prodSelect = $prod;
////                echo '<br>Всего:' . count($prodSelect) . '<br>';////////////////
//                            $prod = [];
//                        }
//                    }
//                }
//            }
//
//            $products = $prodSelect;
//            $totalCount = count($products);
//        }


//        $pages = new \yii\data\Pagination(['totalCount' => $categoryProducts['total'],
//        'pageSize' => $categoryProducts['page']['limit'], 'forcePageParam' => false, 'pageSizeParam' => false, 'route' => $categoryFull]);
        $pages = new \yii\data\Pagination(['totalCount' => $categoryProducts['total'],
            'pageSize' => $categoryProducts['page']['limit'], 'forcePageParam' => false, 'pageSizeParam' => false]);
        $pages->route = $categoryUrl['url'];

        if (empty($page['seo_title'])) $page['seo_title'] = $page['title'] . ' купить оптом в Минске';
        if (empty($page['seo_desc'])) $page['seo_desc'] = $page['title'] . ' купить оптом в Минске на самых выгодных условиях от официального дилера! Доставка по всей Беларуси! ☎ +375 (29) 669-59-11';

        return $this->render('category', compact('categories', 'category', 'page', 'manufacturer', 'categoryProducts', 'categoriesForMenuImg', 'pages', 'categoryUrl'));
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
    public function actionCollectionsPage($category, $collections = '')
    {
        $categories = Category::getCategoriesForMenu($category);
        if (empty($collections)) {
            $page = Category::find()->where('alias=:alias', [':alias' => $category])->asArray()->one();
            $categoriesForMenuImg = Category::getCategoriesForMenuImg($category, $categories);
            $categoryUrl = CategoryPage::categoryUrl($category);
        } else {
            $page = Category::find()->where('alias=:alias', [':alias' => $collections])->asArray()->one();
            $categoriesForMenuImg = Element::find()
                ->select(['ce.title', "CONCAT('/statics/catalog/photo/images_small/', cp.name) as ico",
                    "CONCAT_WS('/', '', cc4.alias, cc3.alias, cc2.alias, cc1.alias, ce.alias, '') AS url"])
                ->from('{{%catalog_element}} ce')
                ->leftJoin('{{%catalog_category}} cc1', 'cc1.id = ce.id_category')
                ->leftJoin('{{%catalog_category}} cc2', 'cc2.id = cc1.id_parent')
                ->leftJoin('{{%catalog_category}} cc3', 'cc3.id = cc2.id_parent')
                ->leftJoin('{{%catalog_category}} cc4', 'cc4.id = cc3.id_parent')
                ->leftJoin('{{%catalog_photo}} cp', 'cp.id_element = ce.id')
                ->where('cc1.alias=:alias AND cc1.published=1', [':alias' => $collections])
                ->andWhere('ce.published=1')
                ->andWhere('cp.is_cover=1')
                ->orderBy('ce.title ASC')
                ->asArray()
                ->all();
            $categoryUrl = CategoryPage::categoryUrl($collections);
        }

        return $this->render('collections', compact([
//            'categories',
            'category', 'categoryUrl', 'page', 'manufacturer', 'categoriesForMenuImg']));
    }


    // Фильтры категории -//- actionGetFilters

    // Товары категории
    public function actionGetCollections()
    {
        echo json_encode(Element::getCollectionsProducts());
    }

    // =======================================================================

    // Страница коллекции
    public function actionCollectionPage($category, $collections, $collection)
    {
        $page = Element::find()->where('alias=:alias', [':alias' => $collection])->asArray()->one();
        $categoryUrl = CategoryPage::categoryUrl($collections);
        $products = Element::getCollectionProducts($collection);

        return $this->render('collection', compact(['categoryUrl', 'page', 'products']));
    }

    // =======================================================================


    public function actionSearch()
    {
//        $post = Yii::$app->request->post('search');
//        return $this->render('search', ['search' => $post]);

        $get = Yii::$app->request->get('search');
        return $this->render('search', ['search' => $get]);
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

    public function actionMenuModal()
    {
        $categories = Category::getCategoriesForMenu('bathtub');
        $this->layout = false;
        return $this->render('menu-modal', compact('categories'));
    }

    public function actionFilterModal()
    {
        $categories = Category::getCategoriesForMenu('bathtub');
        $this->layout = false;
        return $this->render('filter-modal', compact('categories'));
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