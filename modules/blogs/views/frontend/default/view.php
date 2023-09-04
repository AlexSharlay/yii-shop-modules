<?php

/**
 * Blog page view.
 *
 * @var \yii\web\View $this View
 * @var \common\modules\blogs\models\frontend\Blog $model Model
 */

use common\modules\base\helpers\System;
use common\modules\blogs\Module;
use yii\helpers\Html;
use \yii\widgets\Breadcrumbs;
use yii\widgets\ListView;

\common\modules\mods\mods_seo\components\seo::setMeta([
    'seo_title' => $model['seo_title'],
    'seo_keyword' => $model['seo_keyword'],
    'seo_desc' => $model['seo_desc']
]);

// $bundle
if (in_array($alias, ['certifications'])) {
    $bundle = \frontend\themes\shop\pageAssets\blogs\certifications::register($this);
} else if (in_array($alias, ['delivery'])) {
    $bundle = \frontend\themes\shop\pageAssets\blogs\delivery::register($this);
} else if (in_array($alias, ['stores'])) {
    $bundle = \frontend\themes\shop\pageAssets\blogs\stores::register($this);
} else if (in_array($alias, ['objects'])) {
    $bundle = \frontend\themes\shop\pageAssets\blogs\objects::register($this);
} else if (in_array($alias, ['about-us'])) {
    $bundle = \frontend\themes\shop\pageAssets\blogs\aboutus::register($this);
} else {
    $bundle = \frontend\themes\shop\pageAssets\blogs\view::register($this);
}

$this->params['breadcrumbs'] = [
    [
        'label' => Module::t('blogs', 'BACKEND_INDEX_TITLE'),
        'url' => ['index']
    ],
    $this->title
];

?>
<!--<div class="breadcrumbs">-->
<!--    <ul>-->
<!--        <li><a href="/">Главная</a> ></li>-->
<!--        <li><a href="/news/">Новости</a> ></li>-->
<!--        <li><span class="breadcrumbs_end">--><? // //= $model->title ?><!--</span></li>-->
<!--    </ul>-->
<!--</div>-->

<div class="breadcrumb-line">
    <ul class="breadcrumb breadcrumb_upd">
        <li><a href="/">Главная</a></li>

        <li class="active"><?= $model->title ?></li>
    </ul>
</div>

<section>

    <div class="main_content">
        <div class="warp">
            <h1 class="rubric akcent alpha page_h1">
                <span><?= $model->title ?></span>
            </h1>
            <br/>
            
            <!--            <div class="selector_view_product">-->
            <!--                <div class="toolbar">-->
            <!--                    <p class="anons_date">--><? //= date('m.d.Y', $model->updated_at) ?><!--</p>-->
            <!--                </div>-->
            <!--            </div>-->

            <?php if ($model->image_url) : ?>
                <?= Html::img(
                    $model->urlAttribute('image_url'),
                    ['class' => 'blog-page-img', 'alt' => $model->title]
                ) ?>
            <?php endif; ?>

<!--            <div style="clear: both"></div>-->
            <article>
                <?= $model->content ?>
            </article>

        </div>
    </div>

</section>

