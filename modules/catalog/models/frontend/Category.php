<?php

namespace common\modules\catalog\models\frontend;

use Yii;
use yii\web\HttpException;
use \frontend\components\MenuCategories;

class Category extends \common\modules\catalog\models\Category
{

    public static function issetCategory($alias)
    {
        $category = Category::find()->select('id')->where('alias=:alias', [':alias' => $alias])->limit(1)->asArray()->one();
        if ($category !== null) {
            return true;
        } else {
            throw new HttpException(404, 'Категория не найдена.');
        }
    }

    public static function getCategoriesForMenu($alias)
    {
        $id_now = Category::find()->select('id')->where('alias=:alias AND published=1', [':alias' => $alias])->limit(1)->asArray()->one()['id'];
        if ($id_now === null) throw new HttpException(404, 'Категория не найдена.');

        $ids = Category::find()
            ->select('c2.id as id2, c3.id as id3')
            ->from('tbl_catalog_category c')
            ->leftJoin('tbl_catalog_category c2', 'c.id_parent = c2.id ')
            ->leftJoin('tbl_catalog_category c3', 'c2.id_parent = c3.id ')
            ->where('c.alias=:alias AND c.published=1', [':alias' => $alias])
            ->limit(1)->asArray()->one();

        $id_parent = null;
        if ($ids['id3']) {
            $id_parent = $ids['id3'];
        } else {
            $id_parent = $ids['id2'];
        }
//            if (!count($id_parent)) throw new HttpException(404,'Категория не найдена.');//////////////////

//            $categories = Category::find()->select(['id','id_parent','title', 'CONCAT("/catalog/", alias) AS url'])->where('published=1')->asArray()->all();
        $categories = Category::find()->select(['id', 'id_parent', 'title', 'sort', 'menu_img', 'ico', 'CONCAT("/", alias) AS url'])->
        where('published=1 AND show_in_menu')->asArray()->orderBy('sort')->all();
        if (!count($categories)) throw new HttpException(404, 'Категория не найдена.');

        $data = MenuCategories::childrenChain($categories, $id_now);

//        $categories = null;
//        $index = 0;
//        $i = 0;
        $categories = MenuCategories::recursiveTree($data, 0);

//        foreach ($categories as $category) {
//            if ($category['id'] == $id_parent) {
//                $index = $i;
//                break;
//            }
//            $i++;
//        }

//        return MenuCategories::recursiveTree($data, 0)[$index]['childs'];
        return $categories;
    }

//    public static function getCategoriesCollection($collection)
//    {
//
//    }

    public static function categoriesForMenu($categories, $id)
    {
        $arr = [];

        usort($categories, function ($a, $b) {
            if ($a['id_parent'] == $b['id_parent']) {
                return 0;
            }
            return ($a['id_parent'] < $b['id_parent']) ? -1 : 1;
        });

        foreach ($categories as $category) {
            if ($category['id_parent'] == 0) {
                $arr[] = [
                    'title' => $category['title'],
                    'alias' => $category['alias'],
                    'active' => ($category['id'] == $id) ? 1 : 0,
                ];
            } else {
                $arr[$category['id_parent']] = [
                    'title' => $category['title'],
                    'alias' => $category['alias'],
                    'active' => ($category['id'] == $id) ? 1 : 0,
                ];
            }
        }
        return $arr;
    }

    public static function getCategoriesForMenuImg($category, $categories)
    {
        $arr = [];
        $url = '';
        foreach ($categories as $item) {
            if (($item['childs']) && ($item['url'] == '/' . $category)) {
                $url = $item['url'];
                foreach ($item['childs'] as $result) {
                    $arr[] = [
                        'title' => $result['title'],
                        'url' => $url . $result['url'] . '/',
                        'ico' => '/statics/web/catalog/category/images/' . $result['ico'],
                    ];
                }
            } else {
                if ($item['childs']) {
                    $url = $item['url'];
                    foreach ($item['childs'] as $item2) {
                        if (($item2['childs']) && ($item2['url'] == '/' . $category)) {
//                            $url .= $item2['url'];
                            $url = $item['url'] . $item2['url'];
                            foreach ($item2['childs'] as $result) {
                                $arr[] = [
                                    'title' => $result['title'],
                                    'url' => $url . $result['url'] . '/',
                                    'ico' => '/statics/web/catalog/category/images/' . $result['ico'],
                                ];
                            }
                        } else {
                            if ($item2['childs']) {
                                $url = $item['url'].$item2['url'];
                                foreach ($item2['childs'] as $item3) {
                                    if (($item3['childs']) && ($item3['url'] == '/' . $category)) {
//                                        $url .= $item3['url'];
                                        $url = $item['url'] . $item2['url'] . $item3['url'];
                                        foreach ($item3['childs'] as $result) {
                                            $arr[] = [
                                                'title' => $result['title'],
                                                'url' => $url . $result['url'] . '/',
                                                'ico' => '/statics/web/catalog/category/images/' . $result['ico'],
                                            ];
                                        }
                                    }

                                }

                            }
                        }
                    }
                }
            }
        }
        return $arr;
    }

}
