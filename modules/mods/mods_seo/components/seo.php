<?php

namespace common\modules\mods\mods_seo\components;

use Yii;

class seo
{

    public static function setMeta($meta = []) {

        $title = '';
        $desc = '';
        $keyword = '';

        if ($meta['seo_title'] == '' && $meta['seo_desc'] == '') {
            $metaModule = \common\modules\mods\mods_seo\models\backend\Seo::find()
                ->select('seo_title, seo_keyword, seo_desc')
                ->where('url = :url', [':url' => Yii::$app->request->url])
                ->asArray()->one();
            if (!is_null($metaModule)) {
                $title = ($metaModule['seo_title']) ? $metaModule['seo_title'] : $meta['seo_title'];
                $desc = $metaModule['seo_desc'] ? $metaModule['seo_desc'] : $meta['seo_desc'];
                $keyword = $metaModule['seo_keyword'] ? $metaModule['seo_keyword'] : $meta['seo_keyword'];
            }

        } else {
            $title = ($meta['seo_title']) ? $meta['seo_title'] : '';
            $desc = ($meta['seo_desc']) ? $meta['seo_desc'] : '';
            $keyword = ($meta['seo_keyword']) ? $meta['seo_keyword'] : '';
        }

        if ($title) {
            Yii::$app->view->title = $title;


        }
        if ($desc) {
            Yii::$app->view->registerMetaTag([
                'name' => 'description',
                'content' => $desc
            ]);
        }
        if ($title) {
            Yii::$app->view->registerMetaTag([
                'name' => 'keywords',
                'content' => $keyword
            ]);
        }
    }

}





